<?php

namespace App\Services;

use App\Models\Student;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;
use Illuminate\Support\Facades\Storage;

class BannerGenerator
{
    public function generate(Student $student): string
    {
        $template = Image::decodePath(config('services.banner.template_path'));

        $photoPath = $student->image && Storage::disk('public')->exists($student->image)
            ? Storage::disk('public')->path($student->image)
            : public_path('images/avatar-placeholder.png');

        $slotSize = config('services.banner.photo_slot.size');
        $photo = Image::decodePath($photoPath)->cover($slotSize, $slotSize);

        $template->insert(
            $photo,
            config('services.banner.photo_slot.x'),
            config('services.banner.photo_slot.y')
        );

        $template->text(
            $student->name,
            config('services.banner.text_slot.x'),
            config('services.banner.text_slot.y'),
            function (FontFactory $font) {
                $font->file(config('services.banner.font_path'));
                $font->size(config('services.banner.text_slot.size'));
                $font->color('#1f2937');
                $font->align('center', 'center');
            }
        );

        $relativePath = 'banners/' . $student->student_id . '.png';
        Storage::disk('public')->put($relativePath, (string) $template->encode(new PngEncoder()));
        $student->update(['banner_path' => $relativePath]);

        return $relativePath;
    }
}
