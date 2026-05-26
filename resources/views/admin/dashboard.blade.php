@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\DB;
@endphp
<x-app-layout>

    <x-slot name="pageTitle">Admin Dashboard</x-slot>
    <x-slot name="active">Dashboard</x-slot>

    <!-- Welcome Card -->

    <x-modern-header class="text-2xl" chip="Admin Dashboard" title="Welcome back, {{ Auth::user()->name }}."
        desc='Manage users, staff, and system settings.'>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-x-6 mx-auto mt-4 pb-6 sm:px-6 lg:px-8">

        <!-- Left Column -->
        <div class="xxl:col-span-4 lg:col-span-4 col-span-12">


            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-12 gap-x-6">
                <div class="col-span-6">
                    <div class="box overflow-hidden main-content-card cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-1"
                        onclick="window.location.href='{{ route('moderator.users.index') }}'">
                        <div class="box-body text-center">
                            <span class="avatar avatar-md bg-primarytint3color svg-white avatar-rounded">
                                <i class="bi bi-people text-[20px]"></i>
                            </span>
                            <p class="mb-1 mt-3 font-medium">Total Users</p>
                            <h4 class="font-semibold mb-1">{{ number_format($stats['total_users'], 0) }}</h4>
                            <a href="{{ route('moderator.users.index') }}"
                                class="text-xs text-primary hover:underline">View All</a>
                        </div>
                    </div>
                </div>
                <div class="col-span-6">
                    <div class="box overflow-hidden main-content-card cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-1"
                        onclick="window.location.href='{{ route('admin.staff') }}'">
                        <div class="box-body text-center">
                            <span class="avatar avatar-md bg-warning svg-white avatar-rounded">
                                <i class="bi bi-person-badge text-[20px]"></i>
                            </span>
                            <p class="mb-1 mt-3 font-medium">Staff Members</p>
                            <h4 class="font-semibold mb-1">{{ number_format($stats['total_staff'], 0) }}</h4>
                            <a href="{{ route('admin.staff') }}" class="text-xs text-primary hover:underline">Manage</a>
                        </div>
                    </div>
                </div>
                <!-- Hide company verification card -->
                <!-- <div class="col-span-6">
                    <div class="box overflow-hidden main-content-card cursor-pointer transition-all duration-200 hover:shadow-lg hover:-translate-y-1" onclick="window.location.href='{{ route('moderator.companies.index') }}'">
                        <div class="box-body text-center">
                            <span class="avatar avatar-md bg-success svg-white avatar-rounded">
                                <i class="bi bi-building-check text-[20px]"></i>
                            </span>
                            <p class="mb-1 mt-3 font-medium">Company Verification</p>
                            <h4 class="font-semibold mb-1">{{ number_format($stats['pending_companies'] ?? 0, 0) }} pending</h4>
                            <a href="{{ route('moderator.companies.index') }}" class="text-xs text-primary hover:underline">Approve Companies</a>
                        </div>
                    </div>
                </div> -->
            </div>

            <!-- Sticky Notes -->
            <div class="xxl:col-span-3 col-span-12">
                <style>
                    @import url(https://fonts.googleapis.com/css?family=Satisfy);

                    .sticky-note-container {
                        position: relative;
                        margin: 0 auto;
                        padding-top: 30px;
                        overflow: visible;
                    }

                    .sticky-note-paper {
                        color: #333 !important;
                        position: relative;
                        width: 100%;
                        padding: 30px 20px 20px 20px;
                        font-family: Arial, sans-serif;
                        font-size: 18px;
                        box-shadow: 0 10px 10px 2px rgba(0, 0, 0, 0.3);
                        min-height: 280px;
                        max-height: 320px;
                    }

                    .sticky-note-paper.yellow {
                        background: #eae672 !important;
                        transform: rotate(1deg);
                    }

                    .sticky-note-paper.blue {
                        background: #a8d4f0 !important;
                        transform: rotate(-1deg);
                    }

                    .sticky-note-paper.green {
                        background: #b8e6b8 !important;
                        transform: rotate(1deg);
                    }

                    .sticky-note-paper.pink {
                        background: #f0b8d4 !important;
                        transform: rotate(-1deg);
                    }

                    /* Pin styles */
                    .note-pin {
                        background-color: #aaa !important;
                        display: block !important;
                        height: 32px !important;
                        width: 2px !important;
                        position: absolute !important;
                        left: 50% !important;
                        top: -16px !important;
                        z-index: 10 !important;
                    }

                    .note-pin::after {
                        background-color: #A31 !important;
                        background-image: radial-gradient(25% 25%, circle, hsla(0, 0%, 100%, .3), hsla(0, 0%, 0%, .3)) !important;
                        border-radius: 50% !important;
                        box-shadow: inset 0 0 0 1px hsla(0, 0%, 0%, .1), inset 3px 3px 3px hsla(0, 0%, 100%, .2), inset -3px -3px 3px hsla(0, 0%, 0%, .2), 23px 20px 3px hsla(0, 0%, 0%, .15) !important;
                        content: '' !important;
                        height: 12px !important;
                        left: -5px !important;
                        position: absolute !important;
                        top: -10px !important;
                        width: 12px !important;
                    }

                    .note-pin::before {
                        background-color: hsla(0, 0%, 0%, 0.1) !important;
                        box-shadow: 0 0 .25em hsla(0, 0%, 0%, .1) !important;
                        content: '' !important;
                        height: 24px !important;
                        width: 2px !important;
                        left: 0 !important;
                        position: absolute !important;
                        top: 8px !important;
                        transform: rotate(57.5deg) !important;
                        transform-origin: 50% 100% !important;
                    }

                    .sticky-note-textarea {
                        width: 100%;
                        height: 240px;
                        background: transparent !important;
                        border: none !important;
                        resize: none;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 18px;
                        color: #333 !important;
                        line-height: 1.6;
                        overflow-y: auto;
                    }

                    .sticky-note-textarea:focus {
                        outline: none !important;
                        box-shadow: none !important;
                    }

                    .sticky-note-textarea::placeholder {
                        color: #555 !important;
                        opacity: 0.7;
                    }

                    /* Dark mode overrides for sticky notes */
                    :is([data-theme-mode="dark"], .dark) .sticky-note-paper {
                        color: #333 !important;
                    }

                    :is([data-theme-mode="dark"], .dark) .sticky-note-paper.yellow {
                        background: #eae672 !important;
                    }

                    :is([data-theme-mode="dark"], .dark) .sticky-note-paper.blue {
                        background: #a8d4f0 !important;
                    }

                    :is([data-theme-mode="dark"], .dark) .sticky-note-paper.green {
                        background: #b8e6b8 !important;
                    }

                    :is([data-theme-mode="dark"], .dark) .sticky-note-paper.pink {
                        background: #f0b8d4 !important;
                    }

                    :is([data-theme-mode="dark"], .dark) .sticky-note-textarea {
                        background: transparent !important;
                        background-color: transparent !important;
                        color: #333 !important;
                        border: none !important;
                        border-color: transparent !important;
                        box-shadow: none !important;
                    }

                    :is([data-theme-mode="dark"], .dark) .sticky-note-textarea:focus {
                        background: transparent !important;
                        background-color: transparent !important;
                        color: #333 !important;
                        border: none !important;
                        border-color: transparent !important;
                        box-shadow: none !important;
                        outline: none !important;
                    }

                    :is([data-theme-mode="dark"], .dark) .sticky-note-textarea::placeholder {
                        color: #555 !important;
                    }

                    /* Color palette buttons */
                    .color-btn {
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        border-width: 2px;
                        border-style: solid;
                        cursor: pointer;
                        transition: transform 0.2s;
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
                    }

                    .color-btn:hover {
                        transform: scale(1.1);
                    }

                    .color-btn.yellow {
                        background-color: #eae672;
                        border-color: #ca8a04;
                    }

                    .color-btn.blue {
                        background-color: #a8d4f0;
                        border-color: #60a5fa;
                    }

                    .color-btn.green {
                        background-color: #b8e6b8;
                        border-color: #4ade80;
                    }

                    .color-btn.pink {
                        background-color: #f0b8d4;
                        border-color: #f472b6;
                    }
                </style>
                <div class="box border-0 bg-transparent shadow-none" style="overflow: visible;">
                    <div class="box-header justify-between bg-transparent">
                        <div class="box-title">
                            <span class="bi bi-sticky mx-1"></span>
                            Sticky Notes
                        </div>
                        <span id="sticky-note-status" class="text-xs text-textmuted">
                            <i class="bi bi-check-circle text-success"></i> Saved
                        </span>
                    </div>
                    <div class="box-body p-2" style="overflow: visible;">
                        <div class="sticky-note-container">
                            <div id="sticky-note-paper" class="sticky-note-paper yellow">
                                <span class="note-pin"></span>
                                <textarea id="sticky-note-textarea" class="sticky-note-textarea"
                                    placeholder="Write your notes here..." style="background-color: transparent !important; color: #333 !important; border: none !important;"></textarea>
                            </div>
                        </div>
                        <div class="flex gap-2 justify-center mt-4">
                            <button onclick="changeNotepadColor('yellow')" class="color-btn yellow"
                                title="Yellow"></button>
                            <button onclick="changeNotepadColor('blue')" class="color-btn blue" title="Blue"></button>
                            <button onclick="changeNotepadColor('green')" class="color-btn green"
                                title="Green"></button>
                            <button onclick="changeNotepadColor('pink')" class="color-btn pink" title="Pink"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="xxl:col-span-8 lg:col-span-8 col-span-12">
            <!-- Stats Overview -->
            <div class="grid grid-cols-12 gap-x-6">
                <div class="sm:col-span-12 xl:col-span-12 col-span-12">
                    <div class="box pt-3">
                        <div class="flex gap-3 items-center p-4 justify-around bg-light mx-2 flex-wrap rounded-md">
                            <div class="flex gap-4 items-center flex-wrap">
                                <div
                                    class="avatar avatar-lg flex-shrink-0 bg-successtint1color/10 avatar-rounded svg-successtint1color shadow-sm border border-success border-opacity-25">
                                    <span class="bi bi-person-check text-2xl text-success"></span>
                                </div>
                                <div>
                                    <span class="mb-1 block">Applicants</span>
                                    <div class="flex align-items-end gap-2">
                                        <h4 class="mb-0">{{ number_format($stats['total_applicants'], 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-4 items-center flex-wrap">
                                <div
                                    class="avatar avatar-lg flex-shrink-0 bg-warning/10 avatar-rounded svg-warning shadow-sm border border-warning border-opacity-25">
                                    <span class="bi bi-building text-2xl text-warning"></span>
                                </div>
                                <div>
                                    <span class="mb-1 block">Employers</span>
                                    <div class="flex align-items-end gap-2">
                                        <h4 class="mb-0">{{ number_format($stats['total_employers'], 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-4 items-center flex-wrap">
                                <div
                                    class="avatar avatar-lg flex-shrink-0 bg-info/10 avatar-rounded svg-info shadow-sm border border-info border-opacity-25">
                                    <span class="bi bi-briefcase text-2xl text-info"></span>
                                </div>
                                <div>
                                    <span class="mb-1 block">Active Jobs</span>
                                    <div class="flex align-items-end gap-2">
                                        <h4 class="mb-0">{{ number_format($stats['active_jobs'], 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-4 items-center flex-wrap">
                                <div
                                    class="avatar avatar-lg flex-shrink-0 bg-danger/10 avatar-rounded svg-danger shadow-sm border border-danger border-opacity-25">
                                    <span class="bi bi-file-earmark-person text-2xl text-danger"></span>
                                </div>
                                <div>
                                    <span class="mb-1 block">Applications</span>
                                    <div class="flex align-items-end gap-2">
                                        <h4 class="mb-0">{{ number_format($stats['total_applications'], 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-3">
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="box shadow-none border custom-box">
                <div class="box-body overflow-y-auto">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                                <strong>Admin Dashboard</strong>
                            </h6>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                Monitor and manage the Hiring Hall platform.
                            </span>
                        </div>
                    </div>
                    <hr class="mb-3 !mt-3">

                    <!-- Tabs -->
                    <nav class="-mb-0.5 sm:flex sm:space-x-6 rtl:space-x-reverse" role="tablist">
                        @php
                            $tabs = [
                                ['id' => 'tab-calendar', 'icon' => 'bi-calendar-event', 'label' => 'Calendar', 'slug' => 'calendar'],
                                ['id' => 'tab-overview', 'icon' => 'bi-grid', 'label' => 'Overview', 'slug' => 'overview'],
                                ['id' => 'tab-users', 'icon' => 'bi-people', 'label' => 'Recent Users', 'slug' => 'users'],
                                ['id' => 'tab-jobs', 'icon' => 'bi-briefcase', 'label' => 'Recent Jobs', 'slug' => 'jobs'],
                                ['id' => 'tab-messages', 'icon' => 'bi-chat-dots', 'label' => 'Messages', 'slug' => 'messages'],
                            ];
                            $activeTab = request()->query('tab', 'calendar');
                            $activeIndex = 0;
                            foreach ($tabs as $idx => $t) {
                                if ($t['slug'] === $activeTab) {
                                    $activeIndex = $idx;
                                    break;
                                }
                            }
                        @endphp

                        @foreach ($tabs as $index => $tab)
                            <a class="dashboard-tab w-full sm:w-auto py-4 px-1 inline-flex items-center gap-2 border-b-[3px] text-sm whitespace-nowrap hover:text-primary {{ $index === $activeIndex ? 'border-primary text-primary font-semibold' : 'border-transparent text-defaulttextcolor dark:text-white/50' }}"
                                href="javascript:void(0);" data-tab="{{ $tab['slug'] }}"
                                onclick="switchTab('{{ $tab['slug'] }}')">
                                <span class="bi {{ $tab['icon'] }}"></span>
                                {{ $tab['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>

            <!-- Tab Content -->
            <div id="tab-content-calendar" class="tab-content {{ $activeTab === 'calendar' ? '' : 'hidden' }}">
                @include('admin.partials.calendar')
            </div>

            <div id="tab-content-overview" class="tab-content {{ $activeTab === 'overview' ? '' : 'hidden' }}">>
                @include('admin.partials.overview', ['stats' => $stats])
            </div>

            <div id="tab-content-users" class="tab-content {{ $activeTab === 'users' ? '' : 'hidden' }}">
                @include('admin.partials.recent-users', ['recentUsers' => $recentUsers])
            </div>

            <div id="tab-content-jobs" class="tab-content {{ $activeTab === 'jobs' ? '' : 'hidden' }}">
                @include('admin.partials.recent-jobs', ['recentJobs' => $recentJobs])
            </div>

            <div id="tab-content-messages" class="tab-content {{ $activeTab === 'messages' ? '' : 'hidden' }}">
                @include('admin.partials.recent-messages', ['recentMessages' => $recentMessages])
            </div>
        </div>
    </div>

    <script>
        function switchTab(slug) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Show selected tab content
            document.getElementById('tab-content-' + slug).classList.remove('hidden');

            // Update tab styles
            document.querySelectorAll('.dashboard-tab').forEach(tab => {
                tab.classList.remove('border-primary', 'text-primary', 'font-semibold');
                tab.classList.add('border-transparent', 'text-defaulttextcolor', 'dark:text-white/50');
            });

            // Activate clicked tab
            const activeTab = document.querySelector(`.dashboard-tab[data-tab="${slug}"]`);
            if (activeTab) {
                activeTab.classList.remove('border-transparent', 'text-defaulttextcolor', 'dark:text-white/50');
                activeTab.classList.add('border-primary', 'text-primary', 'font-semibold');
            }

            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', slug);
            window.history.pushState({}, '', url);
        }

        // Sticky Notes functionality - Notepad style with pin
        let saveTimeout = null;
        let currentNoteId = null;
        let currentColor = 'yellow';
        let isDirty = false;

        async function loadNotepad() {
            try {
                const response = await fetch('/sticky-notes', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
                });
                const notes = await response.json();
                const textarea = document.getElementById('sticky-note-textarea');

                if (notes.length > 0) {
                    const note = notes[0];
                    currentNoteId = note.id;
                    currentColor = note.color || 'yellow';
                    textarea.value = note.content || '';
                    applyNotepadColor(currentColor);
                } else {
                    // Create a default note so we always have an ID to PUT to
                    const res = await fetch('/sticky-notes', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({ content: '', color: 'yellow' })
                    });
                    const note = await res.json();
                    currentNoteId = note.id;
                }
                updateStatus('saved');
            } catch (e) {
                console.error('Failed to load notes:', e);
                updateStatus('error');
            }
        }

        function updateStatus(status) {
            const statusEl = document.getElementById('sticky-note-status');
            if (status === 'saving') {
                statusEl.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Saving...';
            } else if (status === 'saved') {
                statusEl.innerHTML = '<i class="bi bi-check-circle text-success"></i> Saved';
            } else if (status === 'error') {
                statusEl.innerHTML = '<i class="bi bi-x-circle text-danger"></i> Error';
            }
        }

        async function saveNotepad() {
            const textarea = document.getElementById('sticky-note-textarea');
            const content = textarea.value;

            updateStatus('saving');

            try {
                if (currentNoteId) {
                    await fetch(`/sticky-notes/${currentNoteId}`, {
                        method: 'PUT',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({ content, color: currentColor })
                    });
                } else {
                    const response = await fetch('/sticky-notes', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({ content, color: currentColor })
                    });
                    const note = await response.json();
                    currentNoteId = note.id;
                }
                isDirty = false;
                updateStatus('saved');
            } catch (e) {
                console.error('Failed to save note:', e);
                updateStatus('error');
            }
        }

        function applyNotepadColor(color) {
            const paper = document.getElementById('sticky-note-paper');
            const textarea = document.getElementById('sticky-note-textarea');
            const colors = { yellow: '#eae672', blue: '#a8d4f0', green: '#b8e6b8', pink: '#f0b8d4' };
            paper.classList.remove('yellow', 'blue', 'green', 'pink');
            paper.classList.add(color);
            paper.style.setProperty('background', colors[color], 'important');
            textarea.style.setProperty('background-color', 'transparent', 'important');
            textarea.style.setProperty('color', '#333', 'important');
            textarea.style.setProperty('border', 'none', 'important');
        }

        async function changeNotepadColor(color) {
            currentColor = color;
            applyNotepadColor(color);
            if (currentNoteId) {
                await saveNotepad();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadNotepad();

            const textarea = document.getElementById('sticky-note-textarea');
            if (textarea) {
                textarea.addEventListener('input', function () {
                    isDirty = true;
                    updateStatus('saving');
                    clearTimeout(saveTimeout);
                    saveTimeout = setTimeout(function () {
                        saveNotepad().then(() => { isDirty = false; });
                    }, 800);
                });
            }

            // Save immediately when navigating away or closing tab
            window.addEventListener('beforeunload', function () {
                if (isDirty && currentNoteId) {
                    clearTimeout(saveTimeout);
                    const content = document.getElementById('sticky-note-textarea').value;
                    const formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content);
                    formData.append('content', content);
                    formData.append('color', currentColor);
                    navigator.sendBeacon(`/sticky-notes/${currentNoteId}`, formData);
                }
            });

            // Save when tab becomes hidden (switching tabs/apps)
            document.addEventListener('visibilitychange', function () {
                if (document.hidden && isDirty) {
                    clearTimeout(saveTimeout);
                    saveNotepad().then(() => { isDirty = false; });
                }
            });
        });
    </script>

</x-app-layout>