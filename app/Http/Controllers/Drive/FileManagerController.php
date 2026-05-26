<?php

namespace App\Http\Controllers\Drive;

use App\Http\Controllers\Controller;
use App\Mail\SendCustomEmail;
use App\Models\Contact;
use App\Models\ContactPerson;
use App\Models\FileManager;
use App\Models\FileSubmitted;
use App\Models\ProjectBidding;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $userId = session('manage_portal_id') ?? Auth::id();
        $user = Auth::user();

        $storageQuotaData = null;
        if ($user && FileManager::isStorageRestricted($user->role)) {
            $usedBytes = FileManager::usedStorageBytes($userId);
            $limitBytes = FileManager::STORAGE_LIMIT_BYTES;
            $storageQuotaData = [
                'used_bytes' => $usedBytes,
                'limit_bytes' => $limitBytes,
                'used_mb' => round($usedBytes / 1048576, 2),
                'limit_mb' => round($limitBytes / 1048576, 0),
                'percentage' => min(100, round(($usedBytes / $limitBytes) * 100, 1)),
                'is_full' => $usedBytes >= $limitBytes,
            ];
        }

        return view('modules.file-manager.manager', compact('storageQuotaData'));
    }

    public function create_folder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generate unique short link
        do {
            $new_link = Str::random(7);
        } while (FileManager::where('link', $new_link)->exists());

        $folder_id = $request->parent_id ?? Auth::user()->id; // Default if No Folder Exist

        // Create folder on Google Drive
        // $googleDriveFolderId = $this->googleDriveService->createFolder($request->name, $folder_id);
        $googleDriveFolderId = null; // Placeholder for Google Drive folder ID

        if (session('manage_portal_id')) {
            $uploader = session()->get('manage_orignal_id');
            $user_id = session()->get('manage_portal_id');
        } else {
            $uploader = Auth::user()->id;
            $user_id = $uploader;
        }

        // Save folder details in DB
        $store = FileManager::create([
            'link' => $new_link,
            'name' => $request->name,
            'user_id' => $user_id,
            'parent_id' => $request->parent_id,
            'is_folder' => true,
            'google_drive_id' => $googleDriveFolderId,
            'uploader_id' => $uploader,
        ]);

        return redirect()->back()->with('success', 'Folder created successfully!');
    }

    public function upload_file(Request $request): \Illuminate\Http\RedirectResponse|JsonResponse
    {
        if ($request->input('upload_action') === 'finalize') {
            return $this->finalizeChunkedUpload($request);
        }

        if ($request->has('upload_id') && $request->has('chunk_index')) {
            return $this->storeChunkedUpload($request);
        }

        $request->validate([
            'file' => 'required|file|max:51200',
            'parent_id' => 'nullable',
        ]);

        $file = $request->file('file');
        $parentId = $request->input('parent_id') ?: null;
        $userId = session('manage_portal_id') ?? Auth::id();
        $uploaderId = session('manage_orignal_id') ?? Auth::id();

        // Enforce 500 MB storage limit for employer and applicant roles
        $user = Auth::user();
        if ($user && FileManager::isStorageRestricted($user->role)) {
            $usedBytes = FileManager::usedStorageBytes($userId);
            $availableBytes = FileManager::STORAGE_LIMIT_BYTES - $usedBytes;

            if ($file->getSize() > $availableBytes) {
                $limitMb = round(FileManager::STORAGE_LIMIT_BYTES / 1048576, 0);
                $usedMb = round($usedBytes / 1048576, 2);

                return redirect()->back()->withErrors([
                    'file' => "Storage limit reached. You have used {$usedMb} MB of your {$limitMb} MB quota. Please delete some files to free up space.",
                ]);
            }
        }

        do {
            $newLink = Str::random(7);
        } while (FileManager::where('link', $newLink)->exists());

        $path = $file->store('drive/'.$userId, 'local');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        FileManager::create([
            'link' => $newLink,
            'name' => $originalName,
            'path' => $path,
            'size' => $file->getSize(),
            'format' => strtolower($extension ?: 'file'),
            'mime_type' => $file->getMimeType(),
            'user_id' => $userId,
            'parent_id' => $parentId,
            'is_folder' => false,
            'isDeleted' => 0,
            'google_drive_id' => null,
            'uploader_id' => $uploaderId,
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully!');
    }

    private function storeChunkedUpload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:51200',
            'upload_id' => 'required|string|max:100',
            'chunk_index' => 'required|integer|min:0',
            'total_chunks' => 'required|integer|min:1',
            'file_name' => 'required|string|max:255',
            'file_size' => 'required|integer|min:1',
            'file_mime' => 'nullable|string|max:255',
            'parent_id' => 'nullable',
        ]);

        $file = $request->file('file');
        $userId = session('manage_portal_id') ?? Auth::id();
        $uploaderId = session('manage_orignal_id') ?? Auth::id();
        $parentId = $request->input('parent_id') ?: null;
        $uploadId = $request->input('upload_id');
        $totalSize = (int) $request->input('file_size');

        $user = Auth::user();
        if ($user && FileManager::isStorageRestricted($user->role)) {
            $usedBytes = FileManager::usedStorageBytes($userId);
            $availableBytes = FileManager::STORAGE_LIMIT_BYTES - $usedBytes;

            if ($totalSize > $availableBytes) {
                $limitMb = round(FileManager::STORAGE_LIMIT_BYTES / 1048576, 0);
                $usedMb = round($usedBytes / 1048576, 2);

                return response()->json([
                    'success' => false,
                    'message' => "Storage limit reached. You have used {$usedMb} MB of your {$limitMb} MB quota. Please delete some files to free up space.",
                ], 422);
            }
        }

        $chunkDirectory = 'tmp/drive-chunks/'.$userId.'/'.$uploadId;
        $chunkName = str_pad((string) $request->integer('chunk_index'), 6, '0', STR_PAD_LEFT).'.part';
        $chunkPath = $chunkDirectory.'/chunks/'.$chunkName;

        Storage::disk('local')->putFileAs($chunkDirectory.'/chunks', $file, $chunkName);
        Storage::disk('local')->put($chunkDirectory.'/meta.json', json_encode([
            'file_name' => $request->input('file_name'),
            'file_size' => $totalSize,
            'file_mime' => $request->input('file_mime', $file->getMimeType()),
            'total_chunks' => (int) $request->input('total_chunks'),
            'parent_id' => $parentId,
            'user_id' => $userId,
            'uploader_id' => $uploaderId,
        ]));

        return response()->json([
            'success' => true,
            'upload_id' => $uploadId,
            'chunk_index' => (int) $request->input('chunk_index'),
            'total_chunks' => (int) $request->input('total_chunks'),
            'chunk_path' => $chunkPath,
        ]);
    }

    private function finalizeChunkedUpload(Request $request): JsonResponse
    {
        $request->validate([
            'upload_id' => 'required|string|max:100',
        ]);

        $userId = session('manage_portal_id') ?? Auth::id();
        $uploaderId = session('manage_orignal_id') ?? Auth::id();
        $uploadId = $request->input('upload_id');
        $chunkDirectory = 'tmp/drive-chunks/'.$userId.'/'.$uploadId;
        $metaPath = $chunkDirectory.'/meta.json';

        if (! Storage::disk('local')->exists($metaPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Upload metadata was not found. Please restart the upload.',
            ], 404);
        }

        $meta = json_decode(Storage::disk('local')->get($metaPath), true);
        if (! is_array($meta) || empty($meta['file_name']) || empty($meta['total_chunks'])) {
            return response()->json([
                'success' => false,
                'message' => 'Upload metadata is invalid. Please restart the upload.',
            ], 422);
        }

        $chunkFiles = Storage::disk('local')->files($chunkDirectory.'/chunks');
        if (count($chunkFiles) < (int) $meta['total_chunks']) {
            return response()->json([
                'success' => false,
                'message' => 'The upload is still in progress. Please try again once all chunks have finished uploading.',
            ], 409);
        }

        sort($chunkFiles, SORT_NATURAL);

        do {
            $newLink = Str::random(7);
        } while (FileManager::where('link', $newLink)->exists());

        $originalName = $meta['file_name'];
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $storedFileName = Str::uuid()->toString().($extension ? '.'.$extension : '');
        $finalPath = 'drive/'.$userId.'/'.$storedFileName;

        Storage::disk('local')->makeDirectory('drive/'.$userId);

        $finalAbsolutePath = Storage::disk('local')->path($finalPath);
        $destination = fopen($finalAbsolutePath, 'w+b');
        if ($destination === false) {
            Storage::disk('local')->deleteDirectory($chunkDirectory);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create the destination file for this upload.',
            ], 500);
        }

        try {
            foreach ($chunkFiles as $chunkFile) {
                $source = fopen(Storage::disk('local')->path($chunkFile), 'rb');
                if ($source === false) {
                    throw new \RuntimeException('Unable to open an uploaded chunk for reading.');
                }

                stream_copy_to_stream($source, $destination);
                fclose($source);
            }
        } catch (\Throwable $throwable) {
            fclose($destination);
            Storage::disk('local')->delete($finalPath);
            Storage::disk('local')->deleteDirectory($chunkDirectory);

            return response()->json([
                'success' => false,
                'message' => 'Unable to assemble the uploaded file. Please retry the upload.',
            ], 500);
        }

        fclose($destination);

        $finalSize = Storage::disk('local')->size($finalPath);
        if ((int) $finalSize !== (int) $meta['file_size']) {
            Storage::disk('local')->delete($finalPath);
            Storage::disk('local')->deleteDirectory($chunkDirectory);

            return response()->json([
                'success' => false,
                'message' => 'The assembled file size does not match the original upload. Please retry the upload.',
            ], 500);
        }

        FileManager::create([
            'link' => $newLink,
            'name' => $originalName,
            'path' => $finalPath,
            'size' => $finalSize,
            'format' => strtolower($extension ?: 'file'),
            'mime_type' => $meta['file_mime'] ?? mime_content_type($finalAbsolutePath),
            'user_id' => $userId,
            'parent_id' => $meta['parent_id'] ?? null,
            'is_folder' => false,
            'isDeleted' => 0,
            'google_drive_id' => null,
            'uploader_id' => $meta['uploader_id'] ?? $uploaderId,
        ]);

        Storage::disk('local')->deleteDirectory($chunkDirectory);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully!',
            'redirect' => route('filemanager.index'),
        ]);
    }

    public function download_file(FileManager $file)
    {
        if ($file->user_id !== Auth::id() && (session('manage_portal_id') ?? Auth::id()) !== $file->user_id) {
            abort(403);
        }
        if ($file->is_folder || ! $file->path) {
            abort(404);
        }
        if (! Storage::disk('local')->exists($file->path)) {
            abort(404);
        }

        return Storage::disk('local')->download($file->path, $file->name);
    }

    public function preview_file(FileManager $file)
    {
        if ($file->user_id !== Auth::id() && (session('manage_portal_id') ?? Auth::id()) !== $file->user_id) {
            abort(403);
        }
        if ($file->is_folder || ! $file->path) {
            abort(404);
        }
        if (! Storage::disk('local')->exists($file->path)) {
            abort(404);
        }

        $path = Storage::disk('local')->path($file->path);
        $mime = $file->mime_type ?: mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.basename($file->name).'"',
        ]);
    }

    public function rename(Request $request, FileManager $file): \Illuminate\Http\RedirectResponse
    {
        $userId = session('manage_portal_id') ?? Auth::id();
        if ($file->user_id !== $userId) {
            abort(403);
        }

        $request->validate(['name' => 'required|string|max:255']);

        $file->update(['name' => $request->input('name')]);

        return redirect()->back()->with('success', 'Renamed successfully.');
    }

    public function destroy(FileManager $file): \Illuminate\Http\RedirectResponse
    {
        $userId = session('manage_portal_id') ?? Auth::id();
        if ($file->user_id !== $userId) {
            abort(403);
        }

        if (! $file->is_folder && $file->path && Storage::disk('local')->exists($file->path)) {
            Storage::disk('local')->delete($file->path);
        }
        $file->update(['isDeleted' => 1]);

        return redirect()->back()->with('success', 'Deleted successfully.');
    }

    // public function index_v2()
    // {
    //     return view('modules.file-manager.v2.index');
    // }

    // public function api_files(Request $request)
    // {
    //     $folderId = $request->id;
    //     if (session('manage_portal_id')) {
    //         $clientId = session()->get('manage_portal_id');
    //     } else {
    //         $clientId = Auth::user()->id;

    //         if (Auth::user()->role == 'Developer') {
    //             $clientId = 44;
    //         }
    //     }
    //     try {
    //         $files = FileManager::where('parent_id', $folderId)
    //             ->where('user_id', $clientId)
    //             ->where('is_folder', 0)
    //             ->where('isDeleted', 0)
    //             ->orderBy('id', 'desc')
    //             ->when(Auth::user()->email == 'demo@hillbcs.com', function ($query) {
    //                 $query->limit(2);
    //             })
    //             ->get()
    //             ->map(function ($file) {
    //                 $size = $file->size;
    //                 if ($size >= 1073741824) {
    //                     $readableSize = number_format($size / 1073741824, 2) . ' GB';
    //                 } elseif ($size >= 1048576) {
    //                     $readableSize = number_format($size / 1048576, 2) . ' MB';
    //                 } elseif ($size >= 1024) {
    //                     $readableSize = number_format($size / 1024, 2) . ' KB';
    //                 } elseif ($size > 1) {
    //                     $readableSize = $size . ' bytes';
    //                 } elseif ($size == 1) {
    //                     $readableSize = '1 byte';
    //                 } else {
    //                     $readableSize = '0 bytes';
    //                 }

    //                 $uploader = User::where('id', $file->uploader_id)->first();

    //                 return [
    //                     'id' => $file->id,
    //                     'name' => $file->name,
    //                     'format' => strtoupper($file->format),
    //                     'type' => $file->format,
    //                     'size' => $readableSize,
    //                     'link' => $file->google_drive_id ? "file-manager/preview/" . $file->google_drive_id : $file->path,
    //                     'created_at' => date_format($file->created_at, 'm/d/Y'),
    //                     'google_drive_id' => $file->google_drive_id,
    //                     'uploaded' => $uploader->name ?? '-',
    //                 ];
    //             });

    //         return response()->json(['data' => $files], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function xindex($parent_id = null)
    // {
    //     $files = FileManager::where('parent_id', $parent_id)
    //         ->where('user_id', Auth::user()->id)
    //         ->where('isDeleted', 0)
    //         ->get();
    //     $parent = $parent_id ? FileManager::findOrFail($parent_id) : null;
    //     return view('pages.apps.storage.index', compact('files', 'parent'));
    // }

    // public function rename(Request $request, $id)
    // {
    //     FileManager::where('id', $id)->update([
    //         'name' => $request->name,
    //     ]);

    //     return redirect()->back()->with('success', 'Rename successfully!');
    // }

    // public function submitted(Request $request)
    // {
    //     // Validate input
    //     $validatedData = $request->validate([
    //         'file_ids' => 'required|array',
    //         'file_ids.*' => 'exists:t_file_manager,id',
    //         'proj_id' => 'required|exists:t_project_bidding,id',
    //     ]);

    //     $userId = Auth::id(); // Get authenticated user ID

    //     $contact_ids = '';

    //     DB::transaction(function () use ($validatedData, $request, $userId) {
    //         FileSubmitted::where('user_id', $userId)->where('project_id', $request->proj_id)->delete();
    //         foreach ($validatedData['file_ids'] as $fileId) {
    //             FileSubmitted::create([
    //                 'file_id' => $fileId,
    //                 'project_id' => $request->proj_id,
    //                 'user_id' => $userId,
    //             ]);
    //         }

    //         // Fetch project
    //         $project = ProjectBidding::findOrFail($request->proj_id);
    //         $bidders = is_array($project->proj_bidders) ? $project->proj_bidders : [];

    //         if (empty($bidders)) {
    //             return response()->json(['message' => 'No bidders found.'], 404);
    //         }

    //         // Fetch all contact persons at once (avoid N+1 query issue)
    //         $contactPersons = ContactPerson::whereIn('id', $bidders)->get();

    //         if ($contactPersons->isEmpty()) {
    //             return response()->json(['message' => 'No contact persons found for bidders.'], 404);
    //         }

    //         $ext_email = User::where('assign_id', $userId)->first()->email ?? 'hillbcservices@gmail.com';

    //         foreach ($contactPersons as $person) {
    //             Mail::to($person->email)
    //                 ->cc([Auth::user()->email, $ext_email]) // 👈 add one or multiple CC recipients here // 'hillbcservices@gmail.com',
    //                 ->send(new SendCustomEmail(
    //                     $request->proj_id,
    //                     $person->id,
    //                     $person->company_id,
    //                     null,
    //                     $project->proj_name
    //                 ));
    //         }
    //     });

    //     return response()->json([
    //         'message' => 'Files submitted successfully and notifications sent.',
    //         'id' => $contact_ids,
    //     ]);
    // }

    // public function folder(Request $request)
    // {
    //     do {
    //         $new_link = Str::random(7);
    //     } while (FileManager::where('link', $new_link)->exists());

    //     FileManager::create([
    //         'link' => $new_link,
    //         'name' => $request->name,
    //         'user_id' => Auth::user()->id,
    //         'parent_id' => $request->parent_id,
    //         'is_folder' => true,
    //     ]);

    //     return redirect()->back()->with('success', 'Folder Created successfully!');
    // }

    // public function destroy(FileManager $file)
    // {
    //     if (!$file->is_folder) {
    //         Storage::delete($file->path);
    //     }
    //     $file->delete();
    //     return redirect('/file-manager/list')->with('success', 'Deleted successfully!');
    // }

    // public function updatePrivacy(Request $request)
    // {
    //     $folder = FileManager::findOrFail($request->id);
    //     $folder->privacy = $request->privacy;
    //     $folder->save();

    //     return back()->with('success', 'Privacy updated successfully!');
    // }

    // public function preview($id)
    // {
    //     return view('pages.apps.storage.preview', compact('id'));
    // }
}
