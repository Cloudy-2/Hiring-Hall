<?php

namespace App\Http\Controllers;

use App\Models\SupportTicketAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Preview an attachment (display image or PDF inline)
     */
    public function preview(Request $request, $id)
    {
        // Find attachment by ID
        $attachment = SupportTicketAttachment::findOrFail($id);

        // Check if user can access this attachment
        if (! $this->canAccessAttachment($request, $attachment)) {
            abort(403, 'Unauthorized');
        }

        // Verify file exists
        $disk = Storage::disk('local');
        if (! $disk->exists($attachment->path)) {
            abort(404, 'File not found');
        }

        // Get MIME type
        $mime = $attachment->mime_type ?? $disk->mimeType($attachment->path) ?? 'application/octet-stream';

        // Return direct file response
        return response()->file(
            $disk->path($attachment->path),
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="'.($attachment->name ?? 'file').'"',
                'Cache-Control' => 'max-age=3600, private',
            ]
        );
    }

    /**
     * Get raw attachment file for image preview
     */
    public function previewRaw(Request $request, $id)
    {
        $attachment = SupportTicketAttachment::findOrFail($id);

        if (! $this->canAccessAttachment($request, $attachment)) {
            abort(403, 'Unauthorized');
        }

        $disk = Storage::disk('local');
        if (! $disk->exists($attachment->path)) {
            abort(404, 'File not found');
        }

        $mime = $attachment->mime_type ?? $disk->mimeType($attachment->path) ?? 'application/octet-stream';

        return response()->file(
            $disk->path($attachment->path),
            ['Content-Type' => $mime]
        );
    }

    /**
     * Download an attachment (force download)
     */
    public function download(Request $request, $id)
    {
        // Find attachment by ID
        $attachment = SupportTicketAttachment::findOrFail($id);

        // Check if user can access this attachment
        if (! $this->canAccessAttachment($request, $attachment)) {
            abort(403, 'Unauthorized');
        }

        // Verify file exists
        $disk = Storage::disk('local');
        if (! $disk->exists($attachment->path)) {
            abort(404, 'File not found');
        }

        // Download file
        return $disk->download(
            $attachment->path,
            $attachment->name ?? 'attachment'
        );
    }

    /**
     * Check if user can access this attachment
     */
    private function canAccessAttachment(Request $request, SupportTicketAttachment $attachment): bool
    {
        // Must be authenticated
        if (! $request->user()) {
            return false;
        }

        // Load the ticket relationship
        $attachment->loadMissing('supportTicket');

        if (! $attachment->supportTicket) {
            return false;
        }

        // Check if user owns the ticket or is moderator
        $ticket = $attachment->supportTicket;

        return $ticket->user_id === $request->user()->id || $request->user()->isModerator();
    }
}
