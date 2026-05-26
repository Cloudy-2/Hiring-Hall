<x-app-layout>
    <x-slot name="title">FAQ's</x-slot>
    <x-slot name="active">FAQ</x-slot>

    <style>
        /* ── Modern Minimalist Header (Interactive Board Style) ── */
        .jf-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
            padding: 0.5rem 0 1.5rem 0;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
        }

        .jf-header-content { flex: 1; }

        .jf-context-row {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 0.75rem;
        }

        .jf-v-bar {
            width: 4px;
            height: 20px;
            background: #6366f1;
            border-radius: 4px;
        }

        .jf-context-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
            padding: 2px 10px;
            border-radius: 20px;
        }

        .jf-header-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .jf-header-desc {
            font-size: 1rem;
            color: #64748b;
            max-width: 700px;
            line-height: 1.5;
        }

        .jf-header-desc b { color: #6366f1; font-weight: 700; }

        .jf-header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
            position: absolute;
            bottom: 0.85rem;
            right: 0.85rem;
        }

        @media (max-width: 992px) {
            .jf-header-section { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
            .jf-header-title { font-size: 1.875rem; }
            .jf-header-actions {
                position: static;
                width: 100%;
                flex-wrap: wrap;
                justify-content: flex-start;
            }
        }

        /* ── Dark Mode Overrides (Elite SaaS) ── */
        :is([data-theme-mode="dark"], .dark) .jf-header-actions a,
        :is([data-theme-mode="dark"], .dark) .jf-header-actions button {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-actions a i,
        :is([data-theme-mode="dark"], .dark) .jf-header-actions button i {
            color: #ffffff !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-section { border-bottom-color: rgba(255, 255, 255, 0.08) !important; background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }
        :is([data-theme-mode="dark"], .dark) hr { border-top-color: rgba(255, 255, 255, 0.08) !important; }
    </style>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Unified Premium Styles --}}
        @include('applicants.partials.candidate-styles')

        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="Support Center" id="wt-faq-hero">
            <x-slot name="titleContent"><strong>Frequently Asked Questions</strong></x-slot>
            <x-slot name="description">
                Find answers to common questions about Hiring Hall. Every topic below has a full answer; the same text powers <b>Ask Hill AI</b> (via the FAQ knowledge file) so the chatbot and this page stay aligned. Need a person? Visit the <b>Help Desk</b>.
            </x-slot>
            <x-slot name="actions">
                @auth
                    @if(auth()->user()->role === 'applicant')
                        <a href="{{ route('applicant.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm">
                            <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                        </a>
                    @elseif(auth()->user()->role === 'employer')
                        <a href="{{ route('employer.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm">
                            <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                        </a>
                    @elseif(in_array(auth()->user()->role, ['admin', 'super_admin', 'moderator']))
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm">
                            <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                        </a>
                    @endif
                @endauth
                <a href="{{ route('tickets.list') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm" title="Help Desk">
                    <i class="ri-customer-service-2-line me-2 text-indigo-500"></i> Help Desk
                </a>
            </x-slot>
        </x-modern-header>

    <div class="gap-x-6 justify-center">
        <div class="xl:col-span-1 col-span-12"></div>
        <div class="xl:col-span-10 col-span-12">
            <div class="box">
                <div class="box-body !p-0">
                    <div class="p-6">

                    @if($faqsByCategory->isEmpty())
                        <div class="jf-empty-state">
                            <div class="jf-empty-icon">
                                <i class="ri-question-answer-line"></i>
                            </div>
                            <div class="max-w-md">
                                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">
                                    No FAQs Found
                                </h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                                    We haven't added any frequently asked questions to this category yet. If you have a specific concern, please contact our support team.
                                </p>
                                <a href="{{ route('tickets.create') }}" class="cd-btn cd-btn-primary">
                                    <i class="ri-customer-service-2-line me-2"></i> Contact Support
                                </a>
                            </div>
                        </div>
                    @else
                        @if($faqsByCategory->count() > 1)
                            <nav aria-label="FAQ categories" class="nav nav-tabs nav-tabs-header mb-8 flex justify-center faq-nav gap-2 flex-wrap" id="wt-faq-tabs" role="tablist">
                                @foreach($faqsByCategory->keys() as $index => $categoryName)
                                    <a class="m-1 text-wrap hs-tab-active:bg-primary/10 hs-tab-active:text-primary active:bg-primary/10 active:text-primary cursor-pointer bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 py-2 px-4 text-[0.85rem] font-bold rounded-xl hover:bg-primary/5 hover:text-primary transition-all border border-transparent hs-tab-active:border-primary/20 {{ $index === 0 ? 'active' : '' }}"
                                       id="tab-faq-{{ $index }}"
                                       role="tab"
                                       data-hs-tab="#tab-pane-faq-{{ $index }}">
                                        {{ $categoryName }}
                                    </a>
                                @endforeach
                            </nav>
                        @endif

                        <div class="tab-content mb-3 p-4 !pt-0" id="wt-faq-content">
                            @foreach($faqsByCategory as $categoryName => $faqs)
                                <div class="tab-pane {{ $loop->first ? 'show active' : '' }} border-0 p-0"
                                     id="tab-pane-faq-{{ $loop->index }}"
                                     aria-labelledby="tab-faq-{{ $loop->index }}"
                                     role="tabpanel">
                                    <div class="accordion accordion-customicon1 accordion-primary accordions-items-seperate">
                                        <div class="hs-accordion-group">
                                            @foreach($faqs as $faq)
                                                <div class="hs-accordion bg-white dark:bg-bodybg border dark:border-defaultborder/10 border-defaultborder mt-[0.5rem] rounded-sm {{ $loop->first ? 'active' : '' }}"
                                                     id="{{ $loop->first ? 'wt-faq-accordion' : 'faq-' . $faq->id }}">
                                                    <button type="button"
                                                            class="hs-accordion-toggle hs-accordion-active:!text-primary hs-accordion-active:border hs-accordion-active:border-transparent dark:border-defaultborder/10 hs-accordion-active:bg-primary/10 dark:hs-accordion-active:border justify-between inline-flex items-center w-full font-semibold text-start text-[0.85rem] transition py-3 px-4 dark:hs-accordion-active:!text-primary dark:text-gray-200 dark:hover:text-white/80"
                                                            aria-controls="faq-collapse-{{ $faq->id }}">
                                                        {{ $faq->question }}
                                                        <svg class="hs-accordion-active:hidden block sm:w-[1.25rem] h-[1.25rem] w-[2.25rem] ms-2 p-[3px] rounded-full text-gray-600 bg-light group-hover:text-defaulttextcolor dark:text-defaulttextcolor/80"
                                                             width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.5 8.85999L14.5 8.85998" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                                            <path d="M8 15.36L8 2.35999" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                                        </svg>
                                                        <svg class="hs-accordion-active:block hidden sm:w-[1.25rem] h-[1.25rem] w-[2.25rem] ms-2 p-[3px] rounded-full text-gray-600 bg-light group-hover:text-defaulttextcolor dark:text-defaulttextcolor/80"
                                                             width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.5 8.85999L14.5 8.85998" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                                            <path d="M8 15.36L8 2.35999" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                                        </svg>
                                                    </button>
                                                    <div id="faq-collapse-{{ $faq->id }}"
                                                         class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 {{ $loop->first ? '' : 'hidden' }}"
                                                         aria-labelledby="faq-{{ $faq->id }}">
                                                        <div class="p-5">
                                                            <div class="text-defaulttextcolor dark:text-defaulttextcolor/80 prose dark:prose-invert max-w-none">
                                                                {!! nl2br(e($faq->answer)) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="xl:col-span-1 col-span-12"></div>
    </div>
    </div> {{-- Close max-w-7xl mx-auto --}}

    @include('applicants.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-faq-hero', 'icon' => 'ri-book-open-line', 'title' => 'Find Answers Fast', 'body' => 'Welcome to our Help Center! This is where you\'ll find answers to common questions about Hiring Hall. All the information here also powers our AI chatbot (Ask Hill AI), so you\'re always getting the latest answers. Can\'t find what you need? Click the Help Desk button to contact our support team.', 'position' => 'bottom'],
            ['target' => 'wt-faq-tabs', 'icon' => 'ri-folder-2-line', 'title' => 'Browse by Topic', 'body' => 'Use these category tabs to organize your search. Whether you\'re looking for info about applications, accounts, security, or job management, jump to the right topic to find your answer faster.', 'position' => 'bottom'],
            ['target' => 'wt-faq-content', 'icon' => 'ri-add-circle-line', 'title' => 'Read the Answer', 'body' => 'Click on any question to expand and read the full answer. Click again to collapse it. Each answer is detailed and explains everything you need to know about that topic. If the first answer isn\'t what you\'re looking for, scroll down to explore more questions.', 'position' => 'top'],
        ],
        'wtKey' => 'faq',
    ])
</x-app-layout>
