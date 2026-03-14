<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function download(Certificate $certificate)
    {
        $certificate->load(['user', 'round', 'group']);

        $pdf = Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('certificate-' . $certificate->certificate_number . '.pdf');
    }
}
