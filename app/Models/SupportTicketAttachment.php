<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class SupportTicketAttachment extends Model
{
    use SoftDeletes;

    protected $fillable = ['support_ticket_id', 'support_ticket_reply_id', 'path', 'name', 'mime_type', 'size'];

    public function supportTicket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function supportTicketReply(): BelongsTo
    {
        return $this->belongsTo(SupportTicketReply::class);
    }

    public function isImage(): bool
    {
        $mime = $this->mime_type ?? '';
        if (str_starts_with($mime, 'image/')) {
            return true;
        }

        // Fallback to extension check for better reliability
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        $ext = strtolower(pathinfo($this->name ?? '', PATHINFO_EXTENSION));
        if (in_array($ext, $extensions)) {
            return true;
        }

        if (! $this->path) {
            return false;
        }
        try {
            $detectedMime = Storage::disk('local')->exists($this->path)
                ? Storage::disk('local')->mimeType($this->path)
                : null;

            return $detectedMime && str_starts_with($detectedMime, 'image/');
        } catch (\Throwable) {
            return false;
        }
    }

    public function getContentMimeType(): ?string
    {
        if ($this->mime_type && str_starts_with($this->mime_type, 'image/')) {
            return $this->mime_type;
        }
        if ($this->path && Storage::disk('local')->exists($this->path)) {
            try {
                return Storage::disk('local')->mimeType($this->path) ?: null;
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }

    /**
     * Permanently remove the stored file when force-deleting.
     * Soft-deletes leave the file on disk so it can be restored.
     */
    public function forceDelete(): ?bool
    {
        if ($this->path && Storage::disk('local')->exists($this->path)) {
            Storage::disk('local')->delete($this->path);
        }

        return parent::forceDelete();
    }
}
