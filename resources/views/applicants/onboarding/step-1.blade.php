@extends('applicants.onboarding.layout')

@section('step-content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .address-summary {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 6px;
            padding: 10px 12px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e8eef6;
            color: #64748b;
            font-size: 12px;
            line-height: 1.45;
        }

        .address-summary strong {
            color: #334155;
            font-weight: 700;
        }

        .address-builder {
            margin-top: 12px;
            padding: 16px;
            border: 1px solid #e5ebf3;
            border-radius: 16px;
            background: linear-gradient(180deg, #fafcff 0%, #f7f9fd 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
            overflow: visible;
        }

        .address-builder__title {
            font-size: 11px;
            font-weight: 800;
            color: #334155;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .address-builder__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 12px;
        }

        .address-builder__field {
            margin-bottom: 0 !important;
        }

        .address-builder__field label {
            font-size: 12px;
            color: #475569;
            margin-bottom: 6px;
        }

        .address-builder select {
            background: #fff;
            border-color: #dbe4ef;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
            font-size: 13px;
        }

        .address-builder .hint {
            margin-top: 0;
            font-size: 11px;
            line-height: 1.45;
        }

        .country-select-wrapper .ts-wrapper,
        .country-select-wrapper .ts-control {
            width: 100%;
        }

        .country-select-wrapper {
            position: relative;
            z-index: 40;
        }

        .country-select-wrapper .ts-wrapper {
            position: relative;
            z-index: 45;
        }

        .country-select-wrapper .ts-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 9px 12px;
            background: #f8fafc;
            min-height: 44px;
            box-shadow: none;
        }

        .country-select-wrapper .ts-control.focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 4px var(--gold-glow);
            background: #fff;
        }

        .country-select-wrapper .ts-dropdown {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            z-index: 80 !important;
            background: #fff;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.12);
        }

        .address-form-group {
            position: relative;
            z-index: 220;
        }

        .address-form-group .country-select-wrapper {
            z-index: 240;
        }

        .country-dropdown-menu {
            z-index: 9999 !important;
        }

        .onboard-card {
            overflow: visible;
        }

        @media (max-width: 560px) {
            .address-builder__grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="onboard-card">
        <div style="text-align:center; margin-bottom: 28px;">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;background:linear-gradient(135deg,rgba(229,161,0,0.1),rgba(229,161,0,0.05));border-radius:20px;margin-bottom:14px;">
                <i class="ri-hand-heart-line" style="font-size:32px;color:var(--gold);"></i>
            </div>
            <h2>Welcome to Hiring Hall!</h2>
            <p class="subtitle">Let's set up your profile so employers can discover you.<br>This takes about <strong>3 minutes</strong>.</p>
        </div>

        <form method="POST" action="{{ route('applicant.onboarding.store', ['step' => 1]) }}">
            @csrf

            <div class="section-divider"><i class="ri-user-star-line"></i> About You</div>

            <div class="form-row">
                <div class="form-group">
                    <label>Display Name <span class="required">*</span></label>
                    <input type="text" name="display_name" value="{{ old('display_name', $profile->display_name ?? $user->name) }}" required placeholder="How employers will see you">
                </div>
                <div class="form-group">
                    <label>Job Title <span class="required">*</span></label>
                    <select name="job_title" id="job_title" required class="tomselect-basic" placeholder="Search or select your job role...">
                        <option value="">-- Type to search --</option>
                        @forelse($dropdownOptions['expertiseCategories'] ?? [] as $value => $label)
                            <option value="{{ $value }}" {{ old('job_title', $profile->job_title ?? '') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @empty
                            <option value="">No roles available</option>
                        @endforelse
                    </select>
                    <div class="hint"><i class="ri-briefcase-fill"></i> Type to search or select from the list</div>
                </div>
            </div>

            <div class="form-group address-form-group">
                <label>Your Address <span class="required">*</span></label>
                <input type="hidden" name="location" id="location_input" value="{{ old('location', $profile->location) }}">
                <div class="address-summary" id="selected_address_hint"><i class="ri-map-pin-line"></i><span>Selected address:</span> <strong id="selected_address_text">{{ old('location', $profile->location) ?: 'Not selected yet' }}</strong></div>

                <div class="address-builder country-select-wrapper">
                    <div class="form-group address-builder__field">
                        <label>Country <span class="required">*</span></label>
                        <select id="country_select" placeholder="Select a country..."></select>
                    </div>
                </div>

                <div class="address-builder" id="ph_address_section">
                    <div class="address-builder__grid">
                        <div class="form-group address-builder__field">
                            <label>Region</label>
                            <select id="ph_region">
                                <option value="">Select region</option>
                            </select>
                        </div>
                        <div class="form-group address-builder__field">
                            <label>Province</label>
                            <select id="ph_province" disabled>
                                <option value="">Select province</option>
                            </select>
                        </div>
                    </div>
                    <div class="address-builder__grid">
                        <div class="form-group address-builder__field">
                            <label>City / Municipality</label>
                            <select id="ph_city" disabled>
                                <option value="">Select city/municipality</option>
                            </select>
                        </div>
                        <div class="form-group address-builder__field">
                            <label>Barangay</label>
                            <select id="ph_barangay" disabled>
                                <option value="">Select barangay</option>
                            </select>
                        </div>
                    </div>
                    <div class="hint" id="ph_address_status"><i class="ri-information-line"></i> Select values from top to bottom to set your Address</div>
                </div>

                <div class="address-builder" id="manual_address_section" style="display:none;">
                    <div class="form-group address-builder__field">
                        <label>Full Address</label>
                        <input type="text" id="manual_address_input" placeholder="Type your complete address">
                    </div>
                    <div class="hint"><i class="ri-information-line"></i> For non-Philippines addresses, type your full address manually.</div>
                </div>
            </div>

            <div class="form-group">
                <label>Work Mode <span class="required">*</span></label>
                <select name="work_mode" required>
                    <option value="" disabled {{ !old('work_mode', $profile->work_mode) ? 'selected' : '' }}>Select work mode</option>
                    @foreach(($dropdownOptions['workModes'] ?? []) as $value => $label)
                        <option value="{{ $value }}" {{ old('work_mode', $profile->work_mode) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Headline <span class="required">*</span></label>
                <input type="text" name="headline" value="{{ old('headline', $profile->headline) }}" required placeholder="e.g. 'Detail-oriented VA with 5+ years in admin support'" maxlength="120">
                <div class="hint"><i class="ri-magic-line"></i> First thing employers see — make it memorable!</div>
            </div>

            <div class="form-group">
                <label>About You</label>
                <textarea name="about" rows="4" placeholder="Share your story — what drives you, your strengths, and what makes you stand out...">{{ old('about', $profile->about) }}</textarea>
            </div>

            <div class="onboard-actions">
                <div></div>
                <button type="submit" class="btn-next">
                    Continue <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        (function () {
            const locationInput = document.getElementById('location_input');
            const selectedAddressText = document.getElementById('selected_address_text');
            const countryEl = document.getElementById('country_select');
            const phSection = document.getElementById('ph_address_section');
            const manualSection = document.getElementById('manual_address_section');
            const manualAddressInput = document.getElementById('manual_address_input');
            const regionEl = document.getElementById('ph_region');
            const provinceEl = document.getElementById('ph_province');
            const cityEl = document.getElementById('ph_city');
            const barangayEl = document.getElementById('ph_barangay');
            const statusEl = document.getElementById('ph_address_status');
            const form = document.querySelector('form');

            if (!locationInput || !countryEl || !phSection || !manualSection || !manualAddressInput || !regionEl || !provinceEl || !cityEl || !barangayEl || !statusEl || !form) {
                return;
            }

            const apiBase = 'https://psgc.gitlab.io/api';
            const cache = new Map();

            const setStatus = (message, isError = false) => {
                statusEl.innerHTML = `<i class="${isError ? 'ri-error-warning-line' : 'ri-information-line'}"></i> ${message}`;
                statusEl.style.color = isError ? '#dc2626' : '#94a3b8';
            };

            const resetSelect = (el, placeholder, disabled = true) => {
                el.innerHTML = '';
                const option = document.createElement('option');
                option.value = '';
                option.textContent = placeholder;
                el.appendChild(option);
                el.value = '';
                el.disabled = disabled;
            };

            const fillSelect = (el, items, placeholder) => {
                resetSelect(el, placeholder, false);
                items.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.code;
                    option.textContent = item.name;
                    el.appendChild(option);
                });
            };

            const fetchList = async (path) => {
                if (cache.has(path)) return cache.get(path);
                const response = await fetch(`${apiBase}${path}`);
                if (!response.ok) throw new Error(`Request failed: ${response.status}`);
                const data = await response.json();
                cache.set(path, data);
                return data;
            };

            const selectedText = (el) => {
                if (!el.value) return '';
                const option = el.options[el.selectedIndex];
                return option ? option.textContent.trim() : '';
            };

            const isPhilippinesSelected = () => countryEl.value === 'PH';

            const renderSelectedAddress = (text) => {
                if (!selectedAddressText) return;
                selectedAddressText.textContent = text || 'Not selected yet';
            };

            const updateAddressMode = () => {
                const isPH = isPhilippinesSelected();
                const hasCountry = !!countryEl.value;

                phSection.style.display = hasCountry && isPH ? 'block' : 'none';
                manualSection.style.display = hasCountry && !isPH ? 'block' : 'none';

                if (!hasCountry) {
                    setStatus('Select a country to continue.', false);
                    locationInput.value = '';
                    renderSelectedAddress('Not selected yet');
                    return;
                }

                if (isPH) {
                    setStatus('Select Region, Province, City/Municipality, then Barangay to set your Address.');
                }
            };

            const updateAddressField = () => {
                if (!countryEl.value) {
                    locationInput.value = '';
                    renderSelectedAddress('Not selected yet');
                    return;
                }

                if (!isPhilippinesSelected()) {
                    const manualAddress = manualAddressInput.value.trim();
                    locationInput.value = manualAddress;
                    renderSelectedAddress(manualAddress || 'Not selected yet');
                    return;
                }

                const regionName = selectedText(regionEl);
                const provinceName = selectedText(provinceEl);
                const cityName = selectedText(cityEl);
                const barangayName = selectedText(barangayEl);

                if (!regionName || !cityName) {
                    locationInput.value = '';
                    renderSelectedAddress('Not selected yet');
                    return;
                }

                const parts = [barangayName, cityName, provinceName, regionName, 'Philippines'].filter(Boolean);
                const fullAddress = parts.join(', ');

                locationInput.value = fullAddress;
                renderSelectedAddress(fullAddress);
            };

            const loadCountries = async () => {
                const fallback = [
                    { value: 'PH', text: 'Philippines' },
                    { value: 'US', text: 'United States' },
                    { value: 'CA', text: 'Canada' },
                    { value: 'AU', text: 'Australia' },
                ];

                let countries = fallback;

                try {
                    const response = await fetch('https://restcountries.com/v3.1/all?fields=name,cca2');
                    if (!response.ok) throw new Error('Failed to load countries');
                    const data = await response.json();
                    countries = data
                        .map(c => ({ value: c.cca2, text: c.name?.common || c.cca2 }))
                        .filter(c => c.value && c.text)
                        .sort((a, b) => a.text.localeCompare(b.text));
                } catch (error) {
                    // Fallback list above keeps form usable if country API is unavailable.
                }

                countryEl.innerHTML = '<option value="">Select a country...</option>';
                countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.value;
                    option.textContent = country.text;
                    countryEl.appendChild(option);
                });

                const existingLocation = (locationInput.value || '').trim();
                const defaultCountry = /philippines/i.test(existingLocation) ? 'PH' : '';

                if (window.TomSelect) {
                    const ts = new TomSelect(countryEl, {
                        searchField: 'text',
                        sortField: { field: 'text', direction: 'asc' },
                        create: false,
                        dropdownParent: 'body',
                        placeholder: 'Select a country...'
                    });

                    if (ts.dropdown) {
                        ts.dropdown.classList.add('country-dropdown-menu');
                    }

                    if (defaultCountry) {
                        ts.setValue(defaultCountry, true);
                        // setValue doesn't trigger change event, so load regions manually if PH
                        if (defaultCountry === 'PH') {
                            setTimeout(() => loadRegions(), 100);
                        }
                    }
                } else if (defaultCountry) {
                    countryEl.value = defaultCountry;
                    if (defaultCountry === 'PH') {
                        setTimeout(() => loadRegions(), 100);
                    }
                }

                if (!defaultCountry && existingLocation) {
                    manualAddressInput.value = existingLocation;
                }

                updateAddressMode();
                // Don't call updateAddressField() here - it will clear the saved location value
                // because the dropdowns haven't been populated yet. updateAddressField() will be
                // called when the user changes a dropdown or submits the form.
            };

            const loadRegions = async () => {
                try {
                    const regions = await fetchList('/regions/');
                    fillSelect(regionEl, regions, 'Select region');
                    setStatus('Select Region, Province, City/Municipality, then Barangay to set your Address.');
                    
                    // If there's a saved location and it's a Philippines address, restore dropdown selections
                    const existingLocation = (locationInput.value || '').trim();
                    if (existingLocation && isPhilippinesSelected()) {
                        await restoreAddressFromSaved(existingLocation, regions);
                    }
                } catch (error) {
                    setStatus('PSGC service unavailable right now. Please try again in a moment.', true);
                }
            };

            const restoreAddressFromSaved = async (savedLocation, regionsData) => {
                try {
                    // Format: "Barangay, City/Municipality, Province, Region, Philippines"
                    const parts = savedLocation.split(',').map(p => p.trim()).filter(Boolean);
                    if (parts.length < 3) return; // Need at least Region, City, and something else
                    
                    // Parse based on array length since format includes variable elements
                    const savedRegion = parts[parts.length - 2]; // Second to last (before Philippines)
                    
                    let savedBarangay = null;
                    let savedCity = null;
                    let savedProvince = null;
                    
                    if (parts.length === 5) {
                        // Format: [Barangay, City, Province, Region, Philippines]
                        savedBarangay = parts[0];
                        savedCity = parts[1];
                        savedProvince = parts[2];
                    } else if (parts.length === 4) {
                        // Format: [City, Province, Region, Philippines] OR [Barangay, City, Region, Philippines]
                        // We'll try City first, Province second
                        savedCity = parts[0];
                        savedProvince = parts[1];
                    } else if (parts.length === 3) {
                        // Format: [City, Region, Philippines]
                        savedCity = parts[0];
                    }
                    
                    // Step 1: Find and set region
                    const regionMatch = regionsData.find(r => r.name.toLowerCase().trim() === savedRegion.toLowerCase().trim());
                    if (!regionMatch) return;
                    
                    regionEl.value = regionMatch.code;
                    
                    // Step 2: Load provinces to determine if region has provinces or direct cities
                    let provinces = [];
                    try {
                        provinces = await fetchList(`/regions/${regionMatch.code}/provinces/`);
                    } catch (e) {
                        provinces = [];
                    }
                    
                    fillSelect(provinceEl, provinces, 'Select province');
                    
                    // Step 3: Determine and load cities
                    let cities = [];
                    
                    if (savedProvince && provinces.length > 0) {
                        // Try to find matching province
                        const provinceMatch = provinces.find(p => p.name.toLowerCase().trim() === savedProvince.toLowerCase().trim());
                        if (provinceMatch) {
                            provinceEl.value = provinceMatch.code;
                            try {
                                cities = await fetchList(`/provinces/${provinceMatch.code}/cities-municipalities/`);
                            } catch (e) {
                                cities = [];
                            }
                        }
                    }
                    
                    // If no province or province didn't match, try direct cities from region
                    if (cities.length === 0) {
                        try {
                            cities = await fetchList(`/regions/${regionMatch.code}/cities-municipalities/`);
                        } catch (e) {
                            cities = [];
                        }
                    }
                    
                    fillSelect(cityEl, cities, 'Select city/municipality');
                    
                    // Step 4: Find and set city
                    let cityCodeForBarangays = null;
                    if (cities.length > 0 && savedCity) {
                        const cityMatch = cities.find(c => c.name.toLowerCase().trim() === savedCity.toLowerCase().trim());
                        if (cityMatch) {
                            cityEl.value = cityMatch.code;
                            cityCodeForBarangays = cityMatch.code;
                        }
                    }
                    
                    // Step 5: Load and set barangays
                    if (cityCodeForBarangays) {
                        try {
                            const barangays = await fetchList(`/cities-municipalities/${cityCodeForBarangays}/barangays/`);
                            fillSelect(barangayEl, barangays, 'Select barangay');
                            
                            if (savedBarangay && barangays.length > 0) {
                                const barangayMatch = barangays.find(b => b.name.toLowerCase().trim() === savedBarangay.toLowerCase().trim());
                                if (barangayMatch) {
                                    barangayEl.value = barangayMatch.code;
                                }
                            }
                        } catch (e) {
                            // Silently fail for barangays
                        }
                    }
                } catch (error) {
                    // Silently fail - user can manually reselect if needed
                }
            };

            regionEl.addEventListener('change', async () => {
                if (!isPhilippinesSelected()) return;
                resetSelect(provinceEl, 'Select province');
                resetSelect(cityEl, 'Select city/municipality');
                resetSelect(barangayEl, 'Select barangay');

                if (!regionEl.value) return;

                try {
                    const provinces = await fetchList(`/regions/${regionEl.value}/provinces/`);

                    if (provinces.length) {
                        fillSelect(provinceEl, provinces, 'Select province');
                        setStatus('Province list loaded. Next: select a province.');
                        return;
                    }

                    resetSelect(provinceEl, 'No province (direct-admin region)', true);
                    const cities = await fetchList(`/regions/${regionEl.value}/cities-municipalities/`);
                    fillSelect(cityEl, cities, 'Select city/municipality');
                    setStatus('City list loaded. Next: select a city/municipality.');
                } catch (error) {
                    setStatus('Unable to load provinces/cities. Please reselect your region.', true);
                }
            });

            provinceEl.addEventListener('change', async () => {
                if (!isPhilippinesSelected()) return;
                resetSelect(cityEl, 'Select city/municipality');
                resetSelect(barangayEl, 'Select barangay');

                if (!provinceEl.value) return;

                try {
                    const cities = await fetchList(`/provinces/${provinceEl.value}/cities-municipalities/`);
                    fillSelect(cityEl, cities, 'Select city/municipality');
                    setStatus('City list loaded. Next: select a city/municipality.');
                } catch (error) {
                    setStatus('Unable to load cities. Please reselect your province.', true);
                }
            });

            cityEl.addEventListener('change', async () => {
                if (!isPhilippinesSelected()) return;
                resetSelect(barangayEl, 'Select barangay');
                updateAddressField();

                if (!cityEl.value) return;

                try {
                    const barangays = await fetchList(`/cities-municipalities/${cityEl.value}/barangays/`);
                    fillSelect(barangayEl, barangays, 'Select barangay');
                    setStatus('Barangay list loaded. Selecting one will complete the auto-filled address.');
                    updateAddressField();
                } catch (error) {
                    setStatus('Unable to load barangays. You can still submit with Region, Province, and City.', true);
                }
            });

            barangayEl.addEventListener('change', updateAddressField);
            countryEl.addEventListener('change', () => {
                updateAddressMode();
                updateAddressField();
            });
            manualAddressInput.addEventListener('input', updateAddressField);

            form.addEventListener('submit', function (event) {
                updateAddressField();

                if (!countryEl.value) {
                    event.preventDefault();
                    setStatus('Please select a country first.', true);
                    return;
                }

                if (!isPhilippinesSelected() && !manualAddressInput.value.trim()) {
                    event.preventDefault();
                    setStatus('Please type your full address.', true);
                    return;
                }

                if (!locationInput.value) {
                    event.preventDefault();
                    setStatus('Please complete at least Region and City/Municipality to set your Address.', true);
                }
            });

            loadCountries();
            
            // Add event listener to load regions when country changes manually
            countryEl.addEventListener('change', async () => {
                if (isPhilippinesSelected()) {
                    await loadRegions();
                }
                updateAddressMode();
                updateAddressField();
            });
        })();
    </script>
@endsection
