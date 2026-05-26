@extends('applicants.onboarding.layout')

@section('step-content')
    <div class="onboard-card">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:6px;">
            <div
                style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;background:linear-gradient(135deg,rgba(229,161,0,0.1),rgba(229,161,0,0.05));border-radius:14px;flex-shrink:0;">
                <i class="ri-tools-fill" style="font-size:24px;color:var(--gold);"></i>
            </div>
            <div>
                <h2>Skills & Expertise</h2>
                <p class="subtitle" style="margin-bottom:0;">Highlight what makes you stand out — employers filter by these.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('applicant.onboarding.store', ['step' => 2]) }}">
            @csrf

            {{-- Expertise Categories (Specializations) --}}
            <div class="section-divider"><i class="ri-focus-3-line"></i> Specializations</div>
            <div class="form-group">
                <div class="hint" style="margin-bottom:10px;"><i class="ri-add-circle-line"></i> Add any additional specializations beyond your selected job role</div>
                @php
                    $specializations = old('specializations', []);
                    if (!is_array($specializations))
                        $specializations = [];
                    if (empty($specializations))
                        $specializations = [''];
                @endphp
                <div id="specializations-container" class="tag-list">
                    @foreach($specializations as $idx => $specialization)
                        <div class="tag-item" data-index="{{ $idx }}">
                            <input type="text" name="specializations[{{ $idx }}]" value="{{ $specialization }}"
                                placeholder="e.g. Administrative, Sales, Customer Service">
                            <span class="remove-tag" onclick="removeTag(this, 'specializations-container')" title="Remove"><i
                                    class="ri-close-line"></i></span>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="add-tag-btn"
                    onclick="addTag('specializations-container', 'specializations', 'e.g. Data Entry')">
                    <i class="ri-add-line"></i> Add specialization
                </button>
            </div>

            {{-- Skills --}}
            <div class="section-divider"><i class="ri-lightbulb-line"></i> Skills</div>
            <div class="form-group">
                <div class="hint" style="margin-bottom:10px;"><i class="ri-add-circle-line"></i> Type each skill and click
                    "Add" to add more</div>
                @php
                    $skills = old('skills', $profile->skills ? json_decode($profile->skills, true) : []);
                    if (!is_array($skills))
                        $skills = [];
                    if (empty($skills))
                        $skills = [''];
                @endphp
                <div id="skills-container" class="tag-list">
                    @foreach($skills as $idx => $skill)
                        <div class="tag-item" data-index="{{ $idx }}">
                            <input type="text" name="skills[{{ $idx }}]" value="{{ $skill }}"
                                placeholder="e.g. Calendar Management">
                            <span class="remove-tag" onclick="removeTag(this, 'skills-container')" title="Remove"><i
                                    class="ri-close-line"></i></span>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="add-tag-btn"
                    onclick="addTag('skills-container', 'skills', 'e.g. Email Management')">
                    <i class="ri-add-line"></i> Add skill
                </button>
            </div>

            {{-- Tools --}}
            <div class="section-divider"><i class="ri-apps-2-line"></i> Tools & Software</div>
            <div class="form-group">
                @php
                    $tools = old('tools_used', $profile->tools_used ? json_decode($profile->tools_used, true) : []);
                    if (!is_array($tools))
                        $tools = [];
                    if (empty($tools))
                        $tools = [''];
                @endphp
                <div id="tools-container" class="tag-list">
                    @foreach($tools as $idx => $tool)
                        <div class="tag-item" data-index="{{ $idx }}">
                            <input type="text" name="tools_used[{{ $idx }}]" value="{{ $tool }}"
                                placeholder="e.g. Slack, Trello">
                            <span class="remove-tag" onclick="removeTag(this, 'tools-container')" title="Remove"><i
                                    class="ri-close-line"></i></span>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="add-tag-btn"
                    onclick="addTag('tools-container', 'tools_used', 'e.g. Google Workspace')">
                    <i class="ri-add-line"></i> Add tool
                </button>
            </div>

            {{-- Languages --}}
            <div class="section-divider"><i class="ri-translate-2"></i> Languages</div>
            <div class="form-group">
                @php
                    $langs = old('languages', $profile->languages ? json_decode($profile->languages, true) : []);
                    if (!is_array($langs))
                        $langs = [];
                    if (empty($langs))
                        $langs = [''];
                @endphp
                <div id="languages-container" class="tag-list">
                    @foreach($langs as $idx => $lang)
                        <div class="tag-item" data-index="{{ $idx }}">
                            <input type="text" name="languages[{{ $idx }}]" value="{{ $lang }}"
                                placeholder="e.g. English - Fluent">
                            <span class="remove-tag" onclick="removeTag(this, 'languages-container')" title="Remove"><i
                                    class="ri-close-line"></i></span>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="add-tag-btn"
                    onclick="addTag('languages-container', 'languages', 'e.g. Filipino - Native')">
                    <i class="ri-add-line"></i> Add language
                </button>
            </div>

            <div class="onboard-actions">
                <a href="{{ route('applicant.onboarding.show', ['step' => 1]) }}" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Back
                </a>
                <button type="submit" class="btn-next">
                    Continue <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        function addTag(containerId, fieldName, placeholder) {
            const container = document.getElementById(containerId);
            const items = container.querySelectorAll('.tag-item');
            const idx = items.length > 0 ? Math.max(...Array.from(items).map(el => parseInt(el.dataset.index || 0))) + 1 : 0;
            const tag = document.createElement('div');
            tag.className = 'tag-item';
            tag.dataset.index = idx;
            tag.innerHTML = `
                <input type="text" name="${fieldName}[${idx}]" value="" placeholder="${placeholder}">
                <span class="remove-tag" onclick="removeTag(this, '${containerId}')" title="Remove"><i class="ri-close-line"></i></span>
            `;
            container.appendChild(tag);
            tag.querySelector('input').focus();
        }

        function removeTag(el, containerId) {
            const container = document.getElementById(containerId);
            const item = el.closest('.tag-item');
            if (container.querySelectorAll('.tag-item').length > 1) {
                item.style.transition = 'all 0.25s ease';
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                setTimeout(() => item.remove(), 250);
            }
        }
    </script>
@endsection
