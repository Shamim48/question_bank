<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkMessage extends Model
{
    protected $fillable = [
        'channel', 'subject', 'body', 'status', 'audience_filters',
        'total_recipients', 'sent_count', 'failed_count', 'created_by',
    ];

    protected $casts = [
        'audience_filters' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
