<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company', 'email', 'phone', 'message',
        'attachment', 'is_read', 'is_responded', 'responded_at',
    ];

    protected $casts = [
        'is_read'       => 'boolean',
        'is_responded'  => 'boolean',
        'responded_at'  => 'datetime',
    ];

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment ? Storage::url($this->attachment) : null;
    }
}
