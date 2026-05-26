<x-app-layout>

    <x-slot name="pageTitle">Search Candidates</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Search Candidates</x-slot>

    @include('employers.partials.employer-styles')

    @php
        $logoBgs = ['#4f46e5','#0d9488','#dc2626','#7c3aed','#ea580c','#0284c7','#db2777'];
        $availabilityColors = [
            'full_time'  => ['bg'=>'#f0fdf4','text'=>'#16a34a'],
            'part_time'  => ['bg'=>'#fefce8','text'=>'#ca8a04'],
            'freelance'  => ['bg'=>'#eef2ff','text'=>'#4f46e5'],
            'contract'   => ['bg'=>'#f5f3ff','text'=>'#7c3aed'],
            'internship' => ['bg'=>'#e0f2fe','text'=>'#0284c7'],
        ];
    @endphp

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Hero ═══ --}}
        <div class="col-span-12">
            <div class="cd-page-hero">
                <div>
                    <h1 class="cd-page-hero-title"><i class="ri-user-search-line me-2"></i>Search Candidates</h1>
                    <p class="cd-page-hero-sub">Browse and save talented candidates for your open roles</p>
                </div>
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
                    <a href="{{ route('employer.saved-candidates.index') }}" class="cd-hero-btn cd-hero-btn-primary"><i class="ri-bookmark-fill"></i> Saved Candidates</a>
                    <a href="{{ route('employer.dashboard') }}" class="cd-hero-btn cd-hero-btn-ghost"><i class="ri-dashboard-line"></i> Dashboard</a>
                </div>
            </div>
        </div>

        {{-- ═══ Sidebar Filters ═══ --}}
        <div class="xxl:col-span-3 lg:col-span-4 col-span-12">
            <div class="cd-section" style="padding:0;overflow:hidden">
                <form method="GET" action="{{ route('candidates') }}" id="candidate-filter-form">
                    {{-- Preserve search bar values --}}
                    <input type="hidden" name="keyword" value="{{ $searchKeyword ?? '' }}">
                    <input type="hidden" name="location" value="{{ $searchLocation ?? '' }}">
                    <input type="hidden" name="experience" value="{{ $experienceRange ?? '' }}">

                    {{-- Filter header --}}
                    <div style="padding:0.85rem 1rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #f3f4f6;background:#f9fafb">
                        <span style="font-weight:700;font-size:0.875rem;display:flex;align-items:center;gap:6px"><i class="ri-filter-3-fill" style="color:#4f46e5"></i> Filters</span>
                        <button type="submit" class="cd-btn cd-btn-primary cd-btn-sm"><i class="ri-check-line me-1"></i>Apply</button>
                    </div>

                    {{-- Availability --}}
                    <div style="padding:0.85rem 1rem;border-bottom:1px solid #f3f4f6">
                        <h6 style="font-weight:600;font-size:0.8125rem;margin-bottom:0.5rem;display:flex;align-items:center;gap:6px"><i class="ri-time-fill" style="color:#4f46e5;font-size:0.9rem"></i> Availability</h6>
                        @foreach($dropdownOptions['availability'] ?? collect() as $option)
                        <div class="form-check mb-1" style="display:flex;align-items:center">
                            <input class="form-check-input me-2" type="checkbox" name="availability[]" value="{{ $option->value }}" id="avail-{{ $option->id }}"
                                   @if(in_array($option->value, (array)($availabilityFilter ?? []))) checked @endif>
                            <label class="form-check-label" for="avail-{{ $option->id }}" style="font-size:0.8125rem">{{ $option->label }}</label>
                        </div>
                        @endforeach
                    </div>

                    {{-- Job Type --}}
                    <div style="padding:0.85rem 1rem;border-bottom:1px solid #f3f4f6">
                        <h6 style="font-weight:600;font-size:0.8125rem;margin-bottom:0.5rem;display:flex;align-items:center;gap:6px"><i class="ri-briefcase-fill" style="color:#4f46e5;font-size:0.9rem"></i> Job Type</h6>
                        @foreach($dropdownOptions['job_type'] ?? collect() as $option)
                        <div class="form-check mb-1" style="display:flex;align-items:center">
                            <input class="form-check-input me-2" type="checkbox" name="job_type[]" value="{{ $option->value }}" id="jtype-{{ $option->id }}"
                                   @if(in_array($option->value, (array)($jobTypeFilter ?? []))) checked @endif>
                            <label class="form-check-label" for="jtype-{{ $option->id }}" style="font-size:0.8125rem">{{ $option->label }}</label>
                        </div>
                        @endforeach
                    </div>

                    {{-- Expertise --}}
                    <div style="padding:0.85rem 1rem;border-bottom:1px solid #f3f4f6">
                        <h6 style="font-weight:600;font-size:0.8125rem;margin-bottom:0.5rem;display:flex;align-items:center;gap:6px"><i class="ri-award-fill" style="color:#4f46e5;font-size:0.9rem"></i> Expertise</h6>
                        @foreach(($dropdownOptions['expertise_categories'] ?? collect())->take(5) as $option)
                        <div class="form-check mb-1" style="display:flex;align-items:center">
                            <input class="form-check-input me-2" type="checkbox" name="expertise[]" value="{{ $option->value }}" id="exp-{{ $option->id }}"
                                   @if(in_array($option->value, (array)($expertiseFilter ?? []))) checked @endif>
                            <label class="form-check-label" for="exp-{{ $option->id }}" style="font-size:0.8125rem">{{ $option->label }}</label>
                        </div>
                        @endforeach
                    </div>

                    {{-- Languages --}}
                    <div style="padding:0.85rem 1rem;border-bottom:1px solid #f3f4f6">
                        <h6 style="font-weight:600;font-size:0.8125rem;margin-bottom:0.5rem;display:flex;align-items:center;gap:6px"><i class="ri-translate-2" style="color:#4f46e5;font-size:0.9rem"></i> Languages</h6>
                        @foreach($dropdownOptions['languages'] ?? collect() as $option)
                        <div class="form-check mb-1" style="display:flex;align-items:center">
                            <input class="form-check-input me-2" type="checkbox" name="languages[]" value="{{ $option->value }}" id="lang-{{ $option->id }}"
                                   @if(in_array($option->value, (array)($languagesFilter ?? []))) checked @endif>
                            <label class="form-check-label" for="lang-{{ $option->id }}" style="font-size:0.8125rem">{{ $option->label }}</label>
                        </div>
                        @endforeach
                    </div>

                    {{-- Salary Range --}}
                    <div style="padding:0.85rem 1rem;border-bottom:1px solid #f3f4f6">
                        <h6 style="font-weight:600;font-size:0.8125rem;margin-bottom:0.5rem;display:flex;align-items:center;gap:6px"><i class="ri-money-dollar-circle-fill" style="color:#4f46e5;font-size:0.9rem"></i> Expected Salary</h6>
                        <div style="display:flex;gap:6px;margin-bottom:6px">
                            <input type="number" name="salary_min" class="cd-search" placeholder="Min" value="{{ $salaryMin ?? '' }}" style="flex:1;padding:0.35rem 0.5rem;font-size:0.8rem">
                            <input type="number" name="salary_max" class="cd-search" placeholder="Max" value="{{ $salaryMax ?? '' }}" style="flex:1;padding:0.35rem 0.5rem;font-size:0.8rem">
                        </div>
                        <select name="salary_currency" class="cd-toolbar-select" style="width:100%;font-size:0.8rem">
                            <option value="">Any Currency</option>
                            @foreach($dropdownOptions['salary_currency'] ?? collect() as $option)
                                <option value="{{ $option->value }}" {{ ($salaryCurrency ?? '') === $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Reset --}}
                    <div style="padding:0.75rem 1rem;text-align:center;background:#f9fafb">
                        <a href="{{ route('candidates') }}" class="cd-btn cd-btn-outline cd-btn-sm"><i class="ri-refresh-line me-1"></i>Reset Filters</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══ Main Content ═══ --}}
        <div class="xxl:col-span-9 lg:col-span-8 col-span-12">

            {{-- Search Bar --}}
            <div class="cd-section" style="margin-bottom:1rem;padding:0.75rem 1rem">
                <form method="GET" action="{{ route('candidates') }}" style="display:flex;flex-wrap:wrap;gap:0.5rem;align-items:center">
                    <div class="cd-search-wrap" style="flex:2;min-width:180px">
                        <i class="ri-search-line cd-search-icon"></i>
                        <input name="keyword" type="text" class="cd-search" placeholder="Search by name, title, or skill" value="{{ $searchKeyword ?? '' }}">
                    </div>
                    <div class="cd-search-wrap" style="flex:1;min-width:140px">
                        <i class="ri-map-pin-line cd-search-icon"></i>
                        <input name="location" type="text" class="cd-search" placeholder="Location" value="{{ $searchLocation ?? '' }}">
                    </div>
                    <select name="experience" class="cd-toolbar-select" style="flex:1;min-width:130px">
                        <option value="">Any Experience</option>
                        <option value="0-1" {{ ($experienceRange ?? '') === '0-1' ? 'selected' : '' }}>0 – 1 yr</option>
                        <option value="1-2" {{ ($experienceRange ?? '') === '1-2' ? 'selected' : '' }}>1 – 2 yrs</option>
                        <option value="2-3" {{ ($experienceRange ?? '') === '2-3' ? 'selected' : '' }}>2 – 3 yrs</option>
                        <option value="3-5" {{ ($experienceRange ?? '') === '3-5' ? 'selected' : '' }}>3 – 5 yrs</option>
                        <option value="5+" {{ ($experienceRange ?? '') === '5+' ? 'selected' : '' }}>5+ yrs</option>
                    </select>
                    <button type="submit" class="cd-btn cd-btn-primary"><i class="ri-search-line me-1"></i> Search</button>
                </form>
            </div>

            {{-- Results count --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;padding:0 0.25rem">
                <span style="font-size:0.875rem;font-weight:600;color:#374151">
                    <i class="ri-user-line me-1" style="color:#4f46e5"></i>{{ $profiles->total() }} Candidate{{ $profiles->total() !== 1 ? 's' : '' }} found
                </span>
            </div>

            {{-- Candidate Cards --}}
            @if($profiles->isEmpty())
                <div class="cd-section">
                    <div class="cd-empty">
                        <i class="ri-user-search-line"></i>
                        <p>No candidates found matching your search criteria.</p>
                        <a href="{{ route('candidates') }}" class="cd-btn cd-btn-outline">Clear Filters</a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-12 gap-4">
                    @foreach($profiles as $idx => $profile)
                        <div class="xl:col-span-6 col-span-12">
                            <div class="cd-section" style="height:100%;display:flex;flex-direction:column;padding:1rem">

                                {{-- Top row: avatar + save button --}}
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:0.75rem">
                                    <div style="display:flex;align-items:center;gap:10px">
                                        @if($profile->user?->profile_photo_url)
                                            <img src="{{ $profile->user->profile_photo_url }}" alt="{{ $profile->display_name }}"
                                                 style="width:44px;height:44px;border-radius:10px;object-fit:cover;flex-shrink:0">
                                        @else
                                            <div style="width:44px;height:44px;border-radius:10px;background:{{ $logoBgs[$idx % count($logoBgs)] }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.875rem;flex-shrink:0">
                                                {{ strtoupper(substr($profile->display_name ?? $profile->user?->name ?? 'C', 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-bold dark:text-white">{{ $profile->display_name ?? $profile->user?->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $profile->title ?? $profile->headline ?? '—' }}</div>
                                        </div>
                                    </div>
                                    {{-- Save toggle --}}
                                    <form method="POST" action="{{ route('employer.saved-candidates.toggle', $profile) }}" class="inline candidate-save-form">
                                        @csrf
                                        @php $isSaved = in_array($profile->id, $savedCandidateIds ?? []); @endphp
                                        <button type="submit" class="candidate-save-toggle"
                                                data-saved="{{ $isSaved ? '1' : '0' }}"
                                                style="width:30px;height:30px;border-radius:8px;border:1px solid {{ $isSaved ? '#dc2626' : '#e5e7eb' }};background:{{ $isSaved ? '#dc2626' : '#fff' }};color:{{ $isSaved ? '#fff' : '#ef4444' }};display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;font-size:0.875rem"
                                                title="{{ $isSaved ? 'Unsave' : 'Save candidate' }}">
                                            <i class="{{ $isSaved ? 'ri-bookmark-fill' : 'ri-bookmark-line' }}"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- Location & experience --}}
                                <div style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:0.5rem;font-size:0.8125rem">
                                    @if($profile->location)
                                        <span style="color:#0891b2"><i class="ri-map-pin-2-line me-1"></i>{{ $profile->location }}</span>
                                    @endif
                                    @if($profile->years_experience !== null)
                                        <span style="color:#6b7280">· {{ $profile->years_experience }} yr{{ $profile->years_experience != 1 ? 's' : '' }} exp</span>
                                    @endif
                                </div>

                                {{-- Availability badge --}}
                                @if($profile->availability)
                                    @php $ac = $availabilityColors[$profile->availability] ?? ['bg'=>'#f9fafb','text'=>'#6b7280']; @endphp
                                    <div style="margin-bottom:0.5rem">
                                        <span class="cd-status-pill" style="background:{{ $ac['bg'] }};color:{{ $ac['text'] }}">
                                            <i class="ri-time-line me-1"></i>{{ Str::headline(str_replace('_', ' ', $profile->availability)) }}
                                        </span>
                                    </div>
                                @endif

                                {{-- About snippet --}}
                                @if($profile->about)
                                    <p style="font-size:0.8125rem;color:#6b7280;margin-bottom:0.5rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                        {{ $profile->about }}
                                    </p>
                                @endif

                                {{-- Footer --}}
                                <div style="display:flex;align-items:center;gap:0.5rem;margin-top:auto;padding-top:0.75rem;border-top:1px solid #f3f4f6">
                                    <a href="{{ route('candidates.details', ['candidate' => $profile->id]) }}" class="cd-btn cd-btn-primary cd-btn-sm">
                                        View Profile <i class="ri-arrow-right-line ms-1"></i>
                                    </a>
                                    @if($profile->cv_path)
                                        <a href="{{ route('candidates.download-cv', $profile) }}" class="cd-btn cd-btn-outline cd-btn-sm" title="Download CV">
                                            <i class="ri-download-line"></i>
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="cd-pagination" style="margin-top:1rem">{{ $profiles->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Save/Unsave JS --}}
    <script>
        (function () {
            function setSavedStyles(btn, saved) {
                var icon = btn.querySelector('i');
                if (saved) {
                    btn.dataset.saved = '1';
                    btn.style.background = '#dc2626';
                    btn.style.color = '#fff';
                    btn.style.borderColor = '#dc2626';
                    if (icon) icon.className = 'ri-bookmark-fill';
                } else {
                    btn.dataset.saved = '0';
                    btn.style.background = '#fff';
                    btn.style.color = '#ef4444';
                    btn.style.borderColor = '#e5e7eb';
                    if (icon) icon.className = 'ri-bookmark-line';
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('form.candidate-save-form').forEach(function (form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        var btn = form.querySelector('.candidate-save-toggle');
                        if (!btn) { form.submit(); return; }
                        var token = document.querySelector('meta[name="csrf-token"]')?.content;
                        btn.disabled = true;
                        fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        }).then(function (r) { return r.json(); }).then(function (data) {
                            setSavedStyles(btn, data.status === 'saved');
                        }).catch(function () {}).finally(function () { btn.disabled = false; });
                    });
                });
            });
        })();
    </script>

</x-app-layout>
