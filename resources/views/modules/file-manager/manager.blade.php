<x-app-layout page-title="My Documents" :breadcrumbs="[
        ['label' => 'File Manager', 'url' => '#'],
    ]" active="File Manager">

    <style>
        /* ── Modern Elite Header (Unified) ── */
        .jf-header-section {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-bottom: 2rem; padding: 0.5rem 0 1.5rem 0; border-bottom: 1px solid #e2e8f0; position: relative;
        }
        .jf-header-content { flex: 1; }
        .jf-context-row { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.75rem; }
        .jf-v-bar { width: 4px; height: 20px; background: #6366f1; border-radius: 4px; }
        .jf-context-label {
            font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;
            color: #6366f1; background: rgba(99, 102, 241, 0.1); padding: 2px 10px; border-radius: 20px;
        }
        .jf-header-title { font-size: 2.25rem; font-weight: 800; color: #1e293b; letter-spacing: -0.02em; margin-bottom: 0.75rem; line-height: 1.2; }
        .jf-header-desc { font-size: 1rem; color: #64748b; max-width: 700px; line-height: 1.5; }
        .jf-header-desc b { color: #6366f1; font-weight: 700; }
        .jf-header-actions { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem; }

        /* ── High-Density Storage Bar ── */
        .jf-storage-pnl {
            background: #ffffff; border: 1px solid #f1f5f9; border-radius: 1rem; padding: 1rem 1.25rem;
            margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .jf-storage-info { display: flex; flex-direction: column; min-width: 140px; }
        .jf-storage-lbl { font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem; }
        .jf-storage-val { font-size: 0.875rem; font-weight: 700; color: #0f172a; }
        .jf-storage-bar-cnt { flex: 1; height: 6px; background: #f1f5f9; border-radius: 10px; overflow: hidden; position: relative; }
        .jf-storage-bar { height: 100%; border-radius: 10px; transition: width 1s cubic-bezier(0.4, 0, 0.2, 1); }

        /* ── Elite Dark Mode Re-Modernization ── */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) {
            --elite-glass-bg: rgba(30, 41, 59, 0.45);
            --elite-glass-border: rgba(255, 255, 255, 0.08);
            --elite-text-title: #f8fafc;
            --elite-text-desc: #94a3b8;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-header-section { border-bottom-color: var(--elite-glass-border) !important; background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) hr { border-top-color: var(--elite-glass-border) !important; }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }

        :is([data-theme-mode="dark"], .dark) .jf-header-actions a,
        :is([data-theme-mode="dark"], .dark) .jf-header-actions button {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-actions a i,
        :is([data-theme-mode="dark"], .dark) .jf-header-actions button i {
            color: #ffffff !important;s
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-storage-pnl {
            background: var(--elite-glass-bg); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-color: var(--elite-glass-border); box-shadow: 0 4px 20px -5px rgba(0,0,0,0.5);
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-storage-lbl { color: var(--elite-text-desc); }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-storage-val { color: var(--elite-text-title); }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-storage-bar-cnt { background: rgba(255, 255, 255, 0.05); }

        /* ── Elite Search & Filter (High-Density Integrated Pill) ── */
        .jf-search-pill {
            background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 0 0.5rem 0 1rem;
            display: flex; align-items: center; min-height: 56px; box-shadow: 0 4px 20px -5px rgba(0,0,0,0.05);
            margin-bottom: 1.25rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); width: 100%;
            position: relative;
        }
        .jf-search-pill:focus-within { border-color: #6366f1; box-shadow: 0 10px 30px -10px rgba(99,102,241,0.12); }

        .jf-pill-field { display: flex; align-items: center; gap: 0.75rem; padding: 0 0.75rem; min-width: 0; position: relative; height: 100%; }
        .jf-pill-field.flex-1 { flex: 1; }
        .jf-pill-field.flex-none { flex: none; }
        .jf-pill-field i { font-size: 1.1rem; color: #94a3b8; flex-shrink: 0; }
        .jf-pill-input {
            width: 100%; border: none !important; background: transparent !important;
            font-size: 0.875rem; font-weight: 500; color: #1e293b; outline: none !important;
            box-shadow: none !important; padding: 1rem 0;
        }
        .jf-pill-divider { width: 1px; height: 28px; background: #f1f5f9; flex-shrink: 0; }

        .jf-filter-bar {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.25rem 0; margin-bottom: 1.5rem; overflow-x: auto;
        }
        #fm-type-chips { flex: 1; min-width: 0; }
        #fm-view-tools { display: flex; align-items: center; gap: 0.5rem; margin-left: auto; flex-shrink: 0; }

        .fm-chip {
            display: inline-flex; align-items: center; gap: 0.625rem; padding: 0.5rem 1rem;
            border-radius: 12px; font-size: 0.75rem; font-weight: 700; color: #64748b;
            border: 1px solid #f1f5f9; transition: all 0.2s; white-space: nowrap; cursor: pointer;
            background: #fff;
        }
        .fm-chip:hover { background: #f8fafc; color: #1e293b; border-color: #e2e8f0; }
        .fm-chip.active { background: #f1f0ff; color: #4f46e5; border-color: #e0e7ff; box-shadow: 0 2px 8px -2px rgba(99,102,241,0.15); }
        .fm-chip i { font-size: 1rem; }
        .fm-chip .chip-count { font-size: 0.65rem; background: rgba(0,0,0,0.04); padding: 1px 7px; border-radius: 8px; font-weight: 800; color: #94a3b8; }
        .fm-chip.active .chip-count { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }

        /* Dropdown refinements inside pill */
        .jf-pill-field .fm-chip { border: none; background: transparent; padding: 0.5rem 0.875rem; height: 100%; border-radius: 12px; transition: all 0.2s; }
        .jf-pill-field .fm-chip:hover { background: #f1f5f9; }
        .jf-pill-field .fm-dropdown-menu { top: calc(100% + 8px); right: 0; left: auto; }
        .jf-pill-field .fm-chip i { font-size: 1.1rem; }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-search-pill {
            background: var(--elite-glass-bg); backdrop-filter: blur(12px); border-color: var(--elite-glass-border);
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-pill-input { color: var(--elite-text-title); }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-pill-divider { background: rgba(255,255,255,0.08); }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-chip { background: rgba(255,255,255,0.02); border-color: var(--elite-glass-border); color: var(--elite-text-desc); }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-chip:hover { background: rgba(255, 255, 255, 0.05); color: #fff; }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-chip.active {
            background: rgba(99, 102, 241, 0.15); color: #a5b4fc; border-color: rgba(99, 102, 241, 0.2);
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-pill-field .fm-chip:hover { background: rgba(255,255,255,0.05); }

        /* ── Table Modernization ── */
        .fm-list-box { border: 1px solid #f1f5f9; border-radius: 1rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
        .fm-list-table thead th {
            background: #f8fafc; font-size: 0.65rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.05em; color: #64748b; padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9;
        }
        .fm-list-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: all 0.2s; }
        .fm-list-table tbody tr:hover { background: #f8fafc; }

        /* Actions 3-dot approach */
        .jf-action-btn {
            width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
            border-radius: 8px; color: #64748b; transition: all 0.2s; cursor: pointer;
            background: transparent; border: none;
        }
        .jf-action-btn:hover { background: #f1f5f9; color: #1e293b; }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-list-box { border-color: var(--elite-glass-border); }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-list-table thead th {
            background: rgba(30, 41, 59, 0.7); color: var(--elite-text-desc); border-color: var(--elite-glass-border);
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-list-table tbody tr { border-color: rgba(255, 255, 255, 0.03); }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-list-table tbody tr:hover { background: rgba(255, 255, 255, 0.02); }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-action-btn:hover { background: rgba(255, 255, 255, 0.05); color: #fff; }

        /* Dropdown filters */
        .fm-dropdown-chip { position: relative; }
        .fm-dropdown-menu {
            position: absolute; top: calc(100% + 6px); left: 0; min-width: 180px;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 50; padding: 6px 0; display: none;
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-dropdown-menu {
            background: #1e293b; border-color: var(--elite-glass-border); box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        }
        .fm-dropdown-menu.show { display: block; animation: fm-dropdown-in 0.15s ease-out; }
        @keyframes fm-dropdown-in { from { opacity: 0; transform: translateY(-6px); } to { opacity: 1; transform: translateY(0); } }
        .fm-dropdown-item {
            display: flex; align-items: center; gap: 8px; padding: 8px 14px; font-size: 13px;
            color: #475569; cursor: pointer; transition: background 0.15s;
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-dropdown-item { color: var(--elite-text-desc); }
        .fm-dropdown-item:hover { background: #f1f5f9; }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-dropdown-item:hover { background: rgba(255, 255, 255, 0.05); color: #fff; }
        .fm-dropdown-item.selected { color: #4338ca; font-weight: 600; }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-dropdown-item.selected { color: var(--elite-text-title); }

        /* Divider in filter bar */
        .fm-filter-divider { width: 1px; height: 24px; background: #e2e8f0; margin: 0 4px; }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-filter-divider { background: rgba(255,255,255,0.1); }

        /* No results message */
        .fm-no-results { display: none; text-align: center; padding: 48px 16px; color: #94a3b8; }
        .fm-no-results.show { display: block; }
        .fm-no-results i { font-size: 40px; display: block; margin-bottom: 12px; }

        /* Gallery section headings */
        .fm-gallery-folders-heading, .fm-gallery-files-heading { display: block; }
        .fm-gallery-folders-heading.hidden, .fm-gallery-files-heading.hidden { display: none; }


        /* ── Specialized Table & Modal Refinements ── */
        .fm-list-table tbody td { padding: 0.875rem 1rem; font-size: 0.875rem; color: #1e293b; vertical-align: middle; border-bottom: 1px solid rgba(0,0,0,0.03); }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .fm-list-table tbody td { color: #cbd5e1; border-color: rgba(255,255,255,0.03); }
        .fm-hidden-by-filter { display: none !important; }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ti-modal-content { background: #1e293b !important; border-color: var(--elite-glass-border) !important; color: #f8fafc !important; }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ti-modal-header { border-color: var(--elite-glass-border) !important; }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ti-modal-footer { border-color: var(--elite-glass-border) !important; }

        /* ── Mobile Responsive Overrides ── */
        @media (max-width: 768px) {
            /* Hero: stack vertically */
            .jf-header-section {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 1rem;
                padding-bottom: 1.25rem;
                margin-bottom: 1.25rem;
            }

            .jf-header-title {
                font-size: 1.6rem !important;
                margin-bottom: 0.4rem !important;
            }

            .jf-header-desc {
                font-size: 0.875rem !important;
            }

            /* Action buttons row on mobile */
            .jf-header-actions {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
                gap: 0.5rem !important;
                width: 100% !important;
                align-items: center !important;
            }

            .jf-header-actions button {
                flex: 1 !important;
                justify-content: center !important;
                padding: 0.5rem 0.75rem !important;
                font-size: 0.8rem !important;
                min-width: 0 !important;
            }

            /* View toggle: keep compact */
            #file-manager-view-toggle {
                flex: none !important;
                margin-right: 0 !important;
            }

            /* Search pill: stack fields vertically */
            .jf-search-pill {
                flex-direction: column !important;
                align-items: stretch !important;
                padding: 0.5rem !important;
                min-height: auto !important;
                gap: 0.25rem !important;
                border-radius: 12px !important;
            }

            .jf-pill-field {
                padding: 0.4rem 0.75rem !important;
                height: auto !important;
            }

            .jf-pill-divider {
                width: 100% !important;
                height: 1px !important;
                margin: 0 !important;
            }

            /* Filter chips: scroll horizontally */
            .jf-filter-bar {
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                padding-bottom: 4px !important;
            }

            /* Table: hide non-essential columns */
            .fm-list-table thead th:nth-child(3),
            .fm-list-table tbody td:nth-child(3) {
                display: none !important; /* Hide "Modified" column on mobile */
            }

            .fm-list-table thead th,
            .fm-list-table tbody td {
                padding: 0.65rem 0.625rem !important;
                font-size: 0.8rem !important;
            }

            /* Storage bar */
            .jf-storage-pnl {
                gap: 0.75rem !important;
                padding: 0.75rem 1rem !important;
            }

            .jf-storage-info {
                min-width: 100px !important;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Unified Premium Styles --}}
        @include('applicants.partials.candidate-styles')

        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="Secure Storage">
            <x-slot name="titleContent"><strong>My Documents</strong></x-slot>
            <x-slot name="description">
                Manage your personal files, resumes, and official documentation in one secure location. Organized <b>File Management</b> for your professional needs.
            </x-slot>
            <x-slot name="actions">
                <button class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm hover:shadow-md border border-slate-200 dark:border-slate-700/50 text-sm" data-hs-overlay="#create-folder">
                    <i class="ri-folder-add-line me-2 text-amber-500"></i> New Folder
                </button>

                <button type="button" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md text-sm" data-hs-overlay="#upload-file" @if(isset($storageQuotaData) && $storageQuotaData['is_full']) disabled title="Storage limit reached" @endif>
                    <i class="ri-upload-2-line me-2"></i> Upload File
                </button>
            </x-slot>
        </x-modern-header>

        {{-- Unified Storage Status Bar --}}
        @if(isset($storageQuotaData))
            @php
                $quotaPercentage = $storageQuotaData['percentage'];
                $quotaColor = $quotaPercentage >= 90 ? 'bg-red-500' : ($quotaPercentage >= 70 ? 'bg-amber-500' : 'bg-indigo-600');
            @endphp
            <div class="jf-storage-pnl">
                <div class="jf-storage-info">
                    <span class="jf-storage-lbl"><i class="ri-hard-drive-2-line mr-1"></i> Storage Used</span>
                    <span class="jf-storage-val">{{ $storageQuotaData['used_mb'] }} MB / {{ $storageQuotaData['limit_mb'] }} MB</span>
                </div>
                <div class="jf-storage-bar-cnt">
                    <div class="jf-storage-bar {{ $quotaColor }}" style="width: {{ $quotaPercentage }}%"></div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-black {{ $quotaPercentage >= 90 ? 'text-red-500' : 'text-slate-400' }}">{{ $quotaPercentage }}%</span>
                </div>
            </div>
            @if($storageQuotaData['is_full'])
                <div class="mb-4 px-4 py-2 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-800/30 rounded-lg text-xs text-red-600 dark:text-red-400 font-bold flex items-center">
                    <i class="ri-error-warning-line mr-2"></i> Storage limit reached. Delete files to upload more.
                </div>
            @endif
        @endif

    <div class="h-screen">

        @php
            $folderId = request()->query('f');
            $currentFolder = $folderId ? App\Models\FileManager::find($folderId) : null;
            $clientId = auth()->id();

            $drive = App\Models\FileManager::where('parent_id', $folderId)
                ->where('user_id', $clientId)
                ->where('isDeleted', 0)
                ->orderBy('is_folder', 'desc')
                ->orderBy('name')
                ->paginate(24);

            $breadcrumbs = [];
            $current = $currentFolder;
            while ($current) {
                $breadcrumbs[] = $current;
                $current = $current->parent ?? null;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
        @endphp

    {{-- Breadcrumbs: show current folder path --}}
    <nav class="fm-breadcrumb flex flex-wrap items-center gap-1 text-sm mb-4 px-2 py-2 rounded-lg bg-gray-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/50 backdrop-blur-md">
        <a href="{{ route('filemanager.index') }}" class="text-primary hover:underline font-medium">My Documents</a>
        @foreach ($breadcrumbs as $crumb)
            <span class="text-textmuted dark:text-textmuted/70">/</span>
            <a href="{{ route('filemanager.index', ['f' => $crumb->id]) }}" class="text-primary hover:underline font-medium">
                {{ $crumb->name }}
            </a>
        @endforeach
    </nav>

    {{-- ── High-Density Search & Tools Pill ── --}}
    <div class="jf-search-pill" id="fm-search-bar">
        {{-- Search Field --}}
        <div class="jf-pill-field flex-1">
            <i class="ri-search-line"></i>
            <input type="text" id="fm-search-input" class="jf-pill-input" placeholder="Search in My Documents..." autocomplete="off">
        </div>

        <div class="jf-pill-divider"></div>

        {{-- Sort Tools --}}
        <div class="jf-pill-field flex-none">
            <div class="fm-dropdown-chip relative">
                <button class="fm-chip" id="fm-sort-btn" title="Sort by">
                    <i class="ri-arrow-up-down-line text-indigo-500"></i>
                    <div class="flex flex-col items-start leading-none gap-0.5 pe-2">
                        <span class="text-[10px] uppercase font-black text-slate-400">Sort by</span>
                        <span id="fm-sort-label" class="text-xs font-bold text-slate-700 dark:text-slate-300">Name (A–Z)</span>
                    </div>
                    <i class="ri-arrow-down-s-line text-slate-400"></i>
                </button>
                <div class="fm-dropdown-menu" id="fm-sort-menu">
                    <div class="fm-dropdown-item selected" data-sort="name-asc"><i class="ri-sort-alphabet-asc"></i> Name (A–Z)</div>
                    <div class="fm-dropdown-item" data-sort="name-desc"><i class="ri-sort-alphabet-desc"></i> Name (Z–A)</div>
                    <div class="fm-dropdown-item" data-sort="size-asc"><i class="ri-arrow-up-line"></i> Size (Smallest)</div>
                    <div class="fm-dropdown-item" data-sort="size-desc"><i class="ri-arrow-down-line"></i> Size (Largest)</div>
                </div>
            </div>
        </div>

        <div class="jf-pill-divider"></div>

        {{-- Date Tools --}}
        <div class="jf-pill-field flex-none">
            <div class="fm-dropdown-chip relative">
                <button class="fm-chip" id="fm-modified-btn" title="Filter by Date">
                    <i class="ri-calendar-line text-amber-500"></i>
                    <div class="flex flex-col items-start leading-none gap-0.5 pe-2">
                        <span class="text-[10px] uppercase font-black text-slate-400">Modified</span>
                        <span id="fm-modified-label" class="text-xs font-bold text-slate-700 dark:text-slate-300">Any time</span>
                    </div>
                    <i class="ri-arrow-down-s-line text-slate-400"></i>
                </button>
                <div class="fm-dropdown-menu" id="fm-modified-menu">
                    <div class="fm-dropdown-item selected" data-modified="any"><i class="ri-time-line"></i> Any time</div>
                    <div class="fm-dropdown-item" data-modified="today"><i class="ri-calendar-check-line"></i> Today</div>
                    <div class="fm-dropdown-item" data-modified="week"><i class="ri-calendar-event-line"></i> This week</div>
                    <div class="fm-dropdown-item" data-modified="month"><i class="ri-calendar-2-line"></i> This month</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Dedicated Category Filter Bar ── --}}
    <div class="jf-filter-bar" id="fm-filter-bar">
        <div id="fm-type-chips" class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
            <button class="fm-chip active" data-filter="all">
                <i class="ri-apps-line"></i> All
                <span class="chip-count" id="fm-count-all">0</span>
            </button>
            <button class="fm-chip" data-filter="folder">
                <i class="ri-folder-fill" style="color:#f59e0b;"></i> Folders
                <span class="chip-count" id="fm-count-folder">0</span>
            </button>
            <button class="fm-chip" data-filter="document">
                <i class="ri-file-word-line" style="color:#3b82f6;"></i> Documents
                <span class="chip-count" id="fm-count-document">0</span>
            </button>
            <button class="fm-chip" data-filter="pdf">
                <i class="ri-file-pdf-line" style="color:#ef4444;"></i> PDFs
                <span class="chip-count" id="fm-count-pdf">0</span>
            </button>
            <button class="fm-chip" data-filter="spreadsheet">
                <i class="ri-file-excel-line" style="color:#22c55e;"></i> Spreadsheets
                <span class="chip-count" id="fm-count-spreadsheet">0</span>
            </button>
            <button class="fm-chip" data-filter="image">
                <i class="ri-image-line" style="color:#8b5cf6;"></i> Images
                <span class="chip-count" id="fm-count-image">0</span>
            </button>
        </div>

        <div id="fm-view-tools" aria-label="View mode controls">
            <div id="file-manager-view-toggle" class="inline-flex shrink-0 gap-1 bg-gray-100 dark:bg-gray-700 rounded-xl p-1" role="group" aria-label="View mode">
                <button type="button" id="file-manager-view-gallery" class="rounded-lg h-9 py-2 px-3 text-lg text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-white/10" title="Gallery view">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </button>
                <button type="button" id="file-manager-view-list" class="rounded-lg h-9 py-2 px-3 text-lg bg-white dark:bg-white/10 text-gray-900 dark:text-white shadow-sm" title="List view">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- No results message --}}
    <div class="fm-no-results" id="fm-no-results">
        <i class="ri-search-eye-line"></i>
        <p class="text-base font-medium text-gray-500 dark:text-gray-400">No matching files found</p>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Try adjusting your search or filters</p>
    </div>

    {{-- Gallery view (cards) - hidden by default, list is default --}}
    <div id="file-manager-gallery" class="file-manager-view hidden">

        @include('modules.file-manager.partials.folder-grid', [
            'drive' => $drive,
            'clientId' => $clientId,
        ])
        <h3 class="text-lg mx-2 mt-4 fm-gallery-files-heading"><strong>Files</strong></h3>
        <div class="grid grid-cols-12 gap-3 shadow-none">
            @foreach ($drive as $file)
                @if (!$file->is_folder)
                    @include('modules.file-manager.partials.file-grid', [
                        'file' => $file,
                        'clientId' => $clientId,
                    ])
                @endif
            @endforeach
        </div>
        @if ($drive->isEmpty())
            <div class="jf-empty-state">
                <div class="jf-empty-icon">
                    <i class="ri-folder-open-line"></i>
                </div>
                <div class="max-w-md">
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">
                        No Documents Found
                    </h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                        This folder doesn't have any files or folders yet. Start organizing your secure storage now by uploading your first document.
                    </p>
                    <button class="cd-btn cd-btn-primary" data-hs-overlay="#upload-file">
                        <i class="ri-upload-2-line me-2"></i> Upload Your First File
                    </button>
                </div>
            </div>
        @elseif ($drive->where('is_folder', false)->isEmpty())
            <p class="text-textmuted text-sm mx-2 mt-2 opacity-50 font-medium italic">No files here. Use "Upload File" to add one.</p>
        @endif
    </div>

    {{-- List view (table) - default view --}}
    <div id="file-manager-list" class="file-manager-view">
        <div class="box border fm-list-box">
            <div class="box-body p-0">
                <div class="overflow-x-auto">
                    <table class="table min-w-full fm-list-table">
                        <thead class="border-b border-defaultborder dark:border-defaultborder/10 bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-12">Type</th>
                                <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">Name</th>
                                <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-28">Size</th>
                                <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-36">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($drive as $file)
                                @include('modules.file-manager.partials.file-list-view', [
                                    'file' => $file,
                                    'clientId' => $clientId,
                                ])
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12">
                                        <div class="jf-empty-state">
                                            <div class="jf-empty-icon">
                                                <i class="ri-folder-open-line"></i>
                                            </div>
                                            <div class="max-w-md mx-auto">
                                                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">
                                                    No Documents Found
                                                </h3>
                                                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                                                    This folder doesn't have any files or folders yet. Start organizing your secure storage now by uploading your first document.
                                                </p>
                                                <button class="cd-btn cd-btn-primary" data-hs-overlay="#upload-file">
                                                    <i class="ri-upload-2-line me-2"></i> Upload Your First File
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="grid grid-cols-12 gap-3">
        <div class="col-span-12 p-2 hover:shadow-lg">
            {{-- @include('pages.apps.storage.tables.files') --}}
        </div>
    </div>




    {{-- @include('modules.file-manager.v2.upload') --}}

    {{-- Mobile Layout Path: /modules/file-manager/mobile/mobile.blade.php --}}
    <div class="mobile-response">
        {{-- @php
            $folderId = request()->query('f');
            $currentFolder = $folderId ? App\Models\FileManager::find($folderId) : null;

            if (session('manage_portal_id')) {
                $clientId = session()->get('manage_portal_id');
            } else {
                $clientId = Auth::user()->id;

                if (Auth::user()->role == 'Developer') {
                    $clientId = 44; // Same hardcoded ID as desktop API
                }
            }

            $files = App\Models\FileManager::where('parent_id', $folderId)
                ->where('user_id', $clientId)
                ->where('isDeleted', 0)
                ->orderBy('is_folder', 'desc')
                ->orderBy('name')
                ->paginate(24);
        @endphp --}}

        {{-- @include('modules.file-manager.mobile.mobile', [
            'files' => $files,
            'currentFolder' => $currentFolder,
            'clientId' => $clientId,
        ]) --}}
    </div>

    @include('modules.file-manager.partials.rename-file-folder')

    {{-- Storage error alert --}}
    @if($errors->has('file'))
        <div class="mb-4 flex items-start gap-3 rounded-lg border border-red-300 bg-red-50 dark:bg-red-900/20 dark:border-red-700/50 px-4 py-3 text-sm text-red-700 dark:text-red-300">
            <i class="ri-error-warning-fill text-lg mt-0.5 shrink-0"></i>
            <span>{{ $errors->first('file') }}</span>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const msgInput = document.querySelector('input[name="message"]');
            if (msgInput) msgInput.addEventListener('input', function(event) {
                this.value = this.value.replace(/:smile:/g, '😊').replace(/:heart:/g, '❤️');
            });

            // Gallery / List view toggle (delegate from container so clicks are not captured by theme)
            const toggleEl = document.getElementById('file-manager-view-toggle');
            const galleryBtn = document.getElementById('file-manager-view-gallery');
            const listBtn = document.getElementById('file-manager-view-list');
            const galleryView = document.getElementById('file-manager-gallery');
            const listView = document.getElementById('file-manager-list');

            function showGallery() {
                if (!galleryView || !listView) return;
                galleryView.classList.remove('hidden');
                listView.classList.add('hidden');
                if (galleryBtn) {
                    galleryBtn.classList.add('bg-white', 'dark:bg-white/10', 'shadow-sm', 'text-gray-900', 'dark:text-white');
                    galleryBtn.classList.remove('text-gray-600', 'dark:text-gray-400');
                    galleryBtn.setAttribute('aria-pressed', 'true');
                }
                if (listBtn) {
                    listBtn.classList.remove('bg-white', 'dark:bg-white/10', 'shadow-sm', 'text-gray-900', 'dark:text-white');
                    listBtn.classList.add('text-gray-600', 'dark:text-gray-400');
                    listBtn.setAttribute('aria-pressed', 'false');
                }
            }

            function showList() {
                if (!galleryView || !listView) return;
                listView.classList.remove('hidden');
                galleryView.classList.add('hidden');
                if (listBtn) {
                    listBtn.classList.add('bg-white', 'dark:bg-white/10', 'shadow-sm', 'text-gray-900', 'dark:text-white');
                    listBtn.classList.remove('text-gray-600', 'dark:text-gray-400');
                    listBtn.setAttribute('aria-pressed', 'true');
                }
                if (galleryBtn) {
                    galleryBtn.classList.remove('bg-white', 'dark:bg-white/10', 'shadow-sm', 'text-gray-900', 'dark:text-white');
                    galleryBtn.classList.add('text-gray-600', 'dark:text-gray-400');
                    galleryBtn.setAttribute('aria-pressed', 'false');
                }
            }

            if (toggleEl && galleryBtn && listBtn && galleryView && listView) {
                toggleEl.addEventListener('click', function(e) {
                    const t = e.target.closest('button');
                    if (!t) return;
                    e.preventDefault();
                    e.stopPropagation();
                    if (t.id === 'file-manager-view-gallery') showGallery();
                    else if (t.id === 'file-manager-view-list') showList();
                });
            }

            // ═══════════════════════════════════════════════
            // Google Drive-style Filter Logic
            // ═══════════════════════════════════════════════
            const fileTypeMap = {
                folder: [],
                pdf: ['pdf'],
                document: ['doc', 'docx', 'txt', 'rtf', 'odt'],
                spreadsheet: ['xls', 'xlsx', 'csv', 'ods'],
                image: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'tiff', 'ico', 'heic', 'heif'],
            };

            // Build data attributes on DOM items for filtering
            function getFileCategory(item) {
                const isFolder = item.getAttribute('data-is-folder') === '1';
                if (isFolder) return 'folder';
                const fmt = (item.getAttribute('data-format') || '').toLowerCase();
                for (const [cat, exts] of Object.entries(fileTypeMap)) {
                    if (cat === 'folder') continue;
                    if (exts.includes(fmt)) return cat;
                }
                return 'other';
            }

            // Collect all filterable items (list rows + gallery cards)
            function getAllItems() {
                const listRows = document.querySelectorAll('#file-manager-list tbody tr[data-fm-item]');
                const galleryCards = document.querySelectorAll('#file-manager-gallery [data-fm-item]');
                return [...listRows, ...galleryCards];
            }

            let activeFilter = 'all';
            let searchQuery = '';
            let activeDateFilter = 'any';

            function matchesDate(timestamp, filter) {
                if (filter === 'any') return true;
                const date = new Date(timestamp * 1000);
                const now = new Date();
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

                if (filter === 'today') return date >= today;
                if (filter === 'week') {
                    const weekAgo = new Date(today);
                    weekAgo.setDate(weekAgo.getDate() - 7);
                    return date >= weekAgo;
                }
                if (filter === 'month') {
                    const monthAgo = new Date(today);
                    monthAgo.setMonth(monthAgo.getMonth() - 1);
                    return date >= monthAgo;
                }
                return true;
            }

            function updateCounts() {
                const items = getAllItems();
                // Count unique items (avoid double-counting list + gallery)
                const seen = new Set();
                const counts = { all: 0, folder: 0, document: 0, pdf: 0, spreadsheet: 0, image: 0 };
                items.forEach(item => {
                    const id = item.getAttribute('data-fm-id');
                    const view = item.closest('#file-manager-list') ? 'list' : 'gallery';
                    const key = id + '-' + view;
                    if (seen.has(key)) return;
                    seen.add(key);
                    // Only count list items to avoid doubles
                    if (view !== 'list') return;
                    const cat = getFileCategory(item);
                    counts.all++;
                    if (counts[cat] !== undefined) counts[cat]++;
                });
                // If no list items, count gallery
                if (counts.all === 0) {
                    items.forEach(item => {
                        const cat = getFileCategory(item);
                        counts.all++;
                        if (counts[cat] !== undefined) counts[cat]++;
                    });
                }
                Object.keys(counts).forEach(k => {
                    const el = document.getElementById('fm-count-' + k);
                    if (el) el.textContent = counts[k];
                });
            }

            function applyFilters() {
                const items = getAllItems();
                let visibleCount = 0;
                let visibleFolders = 0;
                let visibleFiles = 0;
                const query = searchQuery.toLowerCase().trim();

                items.forEach(item => {
                    const cat = getFileCategory(item);
                    const name = (item.getAttribute('data-fm-name') || '').toLowerCase();
                    const timestamp = parseInt(item.getAttribute('data-fm-date') || 0);

                    const matchesType = activeFilter === 'all' || cat === activeFilter;
                    const matchesSearch = !query || name.includes(query);
                    const matchesDateResult = matchesDate(timestamp, activeDateFilter);

                    if (matchesType && matchesSearch && matchesDateResult) {
                        item.classList.remove('fm-hidden-by-filter');
                        visibleCount++;
                        if (cat === 'folder') visibleFolders++;
                        else visibleFiles++;
                    } else {
                        item.classList.add('fm-hidden-by-filter');
                    }
                });

                // Show/hide section headings in gallery view
                const folderHeading = document.querySelector('.fm-gallery-folders-heading');
                const filesHeading = document.querySelector('.fm-gallery-files-heading');
                if (folderHeading) folderHeading.classList.toggle('hidden', visibleFolders === 0);
                if (filesHeading) filesHeading.classList.toggle('hidden', visibleFiles === 0);

                // Show/hide no results message
                const noResults = document.getElementById('fm-no-results');
                if (noResults) {
                    noResults.classList.toggle('show', visibleCount === 0 && items.length > 0);
                }
            }

            // Type chip click handlers
            document.querySelectorAll('#fm-type-chips .fm-chip').forEach(chip => {
                chip.addEventListener('click', function() {
                    document.querySelectorAll('#fm-type-chips .fm-chip').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    activeFilter = this.getAttribute('data-filter');
                    applyFilters();
                });
            });

            // Search input
            const searchInput = document.getElementById('fm-search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    searchQuery = this.value;
                    applyFilters();
                });
            }

            // Dropdown toggles
            function setupDropdown(btnId, menuId) {
                const btn = document.getElementById(btnId);
                const menu = document.getElementById(menuId);
                if (!btn || !menu) return;
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Close other open menus
                    document.querySelectorAll('.fm-dropdown-menu.show').forEach(m => {
                        if (m !== menu) m.classList.remove('show');
                    });
                    menu.classList.toggle('show');
                });
                menu.querySelectorAll('.fm-dropdown-item').forEach(item => {
                    item.addEventListener('click', function() {
                        menu.querySelectorAll('.fm-dropdown-item').forEach(i => i.classList.remove('selected'));
                        this.classList.add('selected');
                        menu.classList.remove('show');
                    });
                });
            }
            setupDropdown('fm-sort-btn', 'fm-sort-menu');
            setupDropdown('fm-modified-btn', 'fm-modified-menu');

            // Sort logic
            document.querySelectorAll('#fm-sort-menu .fm-dropdown-item').forEach(item => {
                item.addEventListener('click', function() {
                    const sortBy = this.getAttribute('data-sort');
                    const labelText = this.textContent.trim();
                    document.getElementById('fm-sort-label').textContent = labelText;
                    sortItems(sortBy);
                });
            });

            // Modified logic
            document.querySelectorAll('#fm-modified-menu .fm-dropdown-item').forEach(item => {
                item.addEventListener('click', function() {
                    activeDateFilter = this.getAttribute('data-modified');
                    const labelText = this.textContent.trim();
                    document.getElementById('fm-modified-label').textContent = labelText;
                    applyFilters();
                });
            });

            function sortItems(sortBy) {
                // Sort list view rows
                const tbody = document.querySelector('#file-manager-list tbody');
                if (tbody) {
                    const rows = [...tbody.querySelectorAll('tr[data-fm-item]')];
                    rows.sort((a, b) => {
                        const aFolder = a.getAttribute('data-is-folder') === '1';
                        const bFolder = b.getAttribute('data-is-folder') === '1';
                        // Always keep folders on top
                        if (aFolder !== bFolder) return bFolder - aFolder;
                        if (sortBy === 'name-asc') return (a.getAttribute('data-fm-name') || '').localeCompare(b.getAttribute('data-fm-name') || '');
                        if (sortBy === 'name-desc') return (b.getAttribute('data-fm-name') || '').localeCompare(a.getAttribute('data-fm-name') || '');
                        if (sortBy === 'size-asc') return parseInt(a.getAttribute('data-fm-size') || 0) - parseInt(b.getAttribute('data-fm-size') || 0);
                        if (sortBy === 'size-desc') return parseInt(b.getAttribute('data-fm-size') || 0) - parseInt(a.getAttribute('data-fm-size') || 0);
                        return 0;
                    });
                    rows.forEach(r => tbody.appendChild(r));
                }
            }

            // Close dropdowns on outside click
            document.addEventListener('click', function() {
                document.querySelectorAll('.fm-dropdown-menu.show').forEach(m => m.classList.remove('show'));
            });

            // Initialize counts
            updateCounts();
        });

        function displayFileName(input) {
            let fileName = input.files.length ? input.files[0].name : '';
            document.getElementById('file-name-display').innerText = fileName ? `Selected File: ${fileName}` : '';
        }
    </script>

    <script>
        function rename_ff(id, type, value) {
            var form = document.getElementById('form-rename-ff');
            var nameEl = document.getElementById('name');
            if (form && nameEl) {
                form.action = form.action.replace(/\/0$/, '/' + id);
                nameEl.value = value;
            }
        }
        function remove_data(id, context) {
            if (context !== 'file-manager') return;
            Swal.fire({
                title: 'Delete this item?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Yes, delete it',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
            }).then(function (result) {
                if (!result.isConfirmed) return;
                var url = '{{ url("drive/resources") }}/' + id;
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
                document.body.appendChild(form);
                form.submit();
            });
        }
    </script>
    {{-- @include('pages.filemanager.create_files') --}}

    @include('modules.file-manager.partials.create-folder')
    @include('modules.file-manager.partials.upload-file')
</x-app-layout>
