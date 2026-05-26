@php
    $sizeFormatted = function ($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    };
@endphp
<tr class="group border-b border-defaultborder dark:border-defaultborder/10 hover:bg-gray-50 dark:hover:bg-white/5"
    data-fm-item
    data-fm-id="{{ $file->id }}"
    data-fm-name="{{ $file->name }}"
    data-is-folder="{{ $file->is_folder ? '1' : '0' }}"
    data-format="{{ strtolower($file->format ?? '') }}"
    data-fm-size="{{ $file->size ?? 0 }}"
    data-fm-date="{{ $file->updated_at->timestamp }}">

    <td class="px-4 py-3 align-middle">
        @if ($file->is_folder)
            <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-900/20">
                <i class="ri-folder-5-fill text-amber-500 text-lg"></i>
            </div>
        @else
            @php
                $fmt = strtolower($file->format ?? '');
                $iconClass = 'ri-file-3-line text-slate-400';
                $bgClass = 'bg-slate-50 dark:bg-slate-900/20';
                if (in_array($fmt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) { $iconClass = 'ri-image-2-line text-purple-500'; $bgClass = 'bg-purple-50 dark:bg-purple-900/20'; }
                elseif ($fmt === 'pdf') { $iconClass = 'ri-file-pdf-2-line text-red-500'; $bgClass = 'bg-red-50 dark:bg-red-900/20'; }
                elseif (in_array($fmt, ['doc', 'docx', 'txt'])) { $iconClass = 'ri-file-word-2-line text-blue-500'; $bgClass = 'bg-blue-50 dark:bg-blue-900/20'; }
                elseif (in_array($fmt, ['xls', 'xlsx', 'csv'])) { $iconClass = 'ri-file-excel-2-line text-emerald-500'; $bgClass = 'bg-emerald-50 dark:bg-emerald-900/20'; }
            @endphp
            <div class="w-9 h-9 flex items-center justify-center rounded-lg {{ $bgClass }}">
                <i class="{{ $iconClass }} text-lg"></i>
            </div>
        @endif
    </td>
    <td class="px-3 py-3 align-middle">
        <div class="flex flex-col">
            @if ($file->is_folder)
                <a href="{{ route('filemanager.index', ['f' => $file->id]) }}" class="font-bold text-sm text-slate-700 dark:text-slate-200 hover:text-indigo-600 transition-colors">{{ $file->name }}</a>
            @else
                <span class="font-bold text-sm text-slate-700 dark:text-slate-200">{{ $file->name }}</span>
            @endif
            <span class="text-[10px] text-slate-400 uppercase font-black tracking-wider">{{ $file->is_folder ? 'Folder' : ($file->format ?? 'Unknown') }}</span>
        </div>
    </td>
    <td class="px-3 py-3 align-middle">
        <span class="text-xs font-bold text-slate-500 dark:text-slate-400">
            {{ $file->is_folder ? '—' : $sizeFormatted($file->size ?? 0) }}
        </span>
    </td>
    <td class="px-3 py-3 align-middle text-right">
        <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
            <button id="fm-actions-{{ $file->id }}" type="button" class="jf-action-btn hs-dropdown-toggle">
                <i class="ri-more-2-fill text-lg"></i>
            </button>
            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-xl p-2 mt-2 dark:bg-slate-800 dark:border dark:border-slate-700/50 z-[1001]" aria-labelledby="fm-actions-{{ $file->id }}">
                <div class="px-3 py-2 border-b border-slate-50 dark:border-slate-700/50 mb-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Options</p>
                </div>
                @if ($file->is_folder)
                    <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 focus:outline-none dark:text-slate-300 dark:hover:bg-slate-700/50" href="{{ route('filemanager.index', ['f' => $file->id]) }}">
                        <i class="ri-folder-open-line text-base text-amber-500"></i> Open Folder
                    </a>
                @else
                    <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 focus:outline-none dark:text-slate-300 dark:hover:bg-slate-700/50" href="{{ route('drive.file.preview', $file) }}" target="_blank">
                        <i class="ri-eye-line text-base text-indigo-500"></i> Preview File
                    </a>
                    <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 focus:outline-none dark:text-slate-300 dark:hover:bg-slate-700/50" href="{{ route('drive.file.download', $file) }}" download>
                        <i class="ri-download-2-line text-base text-emerald-500"></i> Download
                    </a>
                @endif
                <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-xs font-bold text-amber-600 hover:bg-amber-50 focus:outline-none dark:text-amber-400 dark:hover:bg-amber-900/20" href="javascript:void(0);"
                    data-hs-overlay="#rename-files-folder"
                    onclick="rename_ff({{ $file->id }}, '{{ $file->is_folder ? 'Folder' : 'File' }}', {{ json_encode($file->name) }})">
                    <i class="ri-edit-line text-base"></i> Rename
                </a>
                <div class="my-1 border-t border-slate-50 dark:border-slate-700/50"></div>
                <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-xs font-bold text-red-600 hover:bg-red-50 focus:outline-none dark:text-red-400 dark:hover:bg-red-900/20" href="javascript:void(0);"
                    onclick="remove_data({{ $file->id }}, 'file-manager')">
                    <i class="ri-delete-bin-line text-base"></i> Delete
                </a>
            </div>
        </div>
    </td>
</tr>
