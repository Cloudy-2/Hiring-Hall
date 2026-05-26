<x-app-layout>

    <x-slot name="pageTitle">Profit Tracker Calculator</x-slot>
    <x-slot name="return">{"link": "/users/manage", "text": "back"}</x-slot>
    <x-slot name="url_1">{"link": "/developer/routes", "text": "Routes & Privilege"}</x-slot>
    <x-slot name="active">Details</x-slot>

    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="col-md-7">
 <div class="border p-3">

            <!-- ===== KPI HEADER (2 columns) ===== -->


            <!-- Calculations Card -->


            <!-- ===== FORM (ALL ATTRIBUTES) ===== -->
            <form id="profit-tracker-form" method="POST" action="#">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off">

                <div class="grid grid-cols-12 gap-4 mt-2">
                    <!-- Date -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="date" class="form-label">Date <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" id="date" name="date" class="ti-form-input rounded-sm ps-11"
                                required>
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Company -->
                    <div class="col-span-12 md:col-span-8">
                        <label for="company" class="form-label">Company Name <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="company" name="company"
                                placeholder="e.g. Ace High Concrete Supply" class="ti-form-input rounded-sm ps-11"
                                required>
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-building"></i>
                            </div>
                        </div>
                    </div>

                    <!-- City -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="city" class="form-label">City / Location</label>
                        <div class="relative">
                            <input type="text" id="city" name="city" placeholder="e.g. Los Angeles, CA"
                                class="ti-form-input rounded-sm ps-11">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Project -->
                    <div class="col-span-12 md:col-span-8">
                        <label for="project" class="form-label">Project Name <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="project" name="project"
                                placeholder="e.g. Parking Lot Resurfacing" class="ti-form-input rounded-sm ps-11"
                                required>
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-briefcase"></i>
                            </div>
                        </div>
                    </div>


                    <!-- Contract $ -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="contract_amount" class="form-label">Contract Amount ($) <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="number" id="contract_amount" name="contract_amount" placeholder="0.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" required>
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>

                    <!-- SF -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="square_feet" class="form-label">Square Feet (SF)</label>
                        <div class="relative">
                            <input type="number" id="square_feet" name="square_feet" placeholder="e.g. 2500"
                                class="ti-form-input rounded-sm ps-11" step="1" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-aspect-ratio"></i>
                            </div>
                        </div>
                    </div>

                    <!-- # of Units -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="num_units" class="form-label">Number of Units</label>
                        <div class="relative">
                            <input type="number" id="num_units" name="num_units" placeholder="e.g. 10"
                                class="ti-form-input rounded-sm ps-11" step="1" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-123"></i>
                            </div>
                        </div>
                    </div>

                    <!-- PW -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="pw" class="form-label">PW (Rate per Hour)</label>
                        <div class="relative">
                            <input type="number" id="pw" name="pw" placeholder="0.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-droplet"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Comi -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="commission" class="form-label">Commission ($)</label>
                        <div class="relative">
                            <input type="number" id="commission" name="commission" placeholder="e.g. 200.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-cash"></i>
                            </div>
                        </div>
                    </div>

                    <!-- HRS -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="hours" class="form-label">Total Hours Worked</label>
                        <div class="relative">
                            <input type="number" id="hours" name="hours" placeholder="e.g. 40"
                                class="ti-form-input rounded-sm ps-11" step="0.1" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Labor Mode Toggle -->
                    <div class="col-span-12 md:col-span-4">
                        <label class="form-label">Labor Mode</label>
                        <div class="flex items-center gap-3">
                            <label class="inline-flex items-center gap-1 text-sm">
                                <input type="radio" name="labor_mode" value="input" id="labor_mode_input"
                                    class="ti-form-checkbox" checked>
                                <span>Use Labor (In-House)</span>
                            </label>
                            <label class="inline-flex items-center gap-1 text-sm">
                                <input type="radio" name="labor_mode" value="derived" id="labor_mode_derived"
                                    class="ti-form-checkbox">
                                <span>Use HRS × PW</span>
                            </label>
                        </div>
                    </div>

                    <!-- Labor In-House -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="labor_inhouse" class="form-label">Labor (In-House)</label>
                        <div class="relative">
                            <input type="number" id="labor_inhouse" name="labor_inhouse" placeholder="0.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-person-workspace"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Sub's -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="subs" class="form-label">Subcontractor's Cost</label>
                        <div class="relative">
                            <input type="number" id="subs" name="subs" placeholder="0.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Food/RBNB -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="food_rbnb" class="form-label">Food / AirBnB Cost</label>
                        <div class="relative">
                            <input type="number" id="food_rbnb" name="food_rbnb" placeholder="0.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-house-door"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Material (Base) -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="material" class="form-label">Material Cost (Base)</label>
                        <div class="relative">
                            <input type="number" id="material" name="material" placeholder="0.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                        <p id="material-hint" class="mt-1 text-xs text-gray-500"></p>
                    </div>

                    <!-- Base per SF -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="per_sf" class="form-label">Base Cost Per SF</label>
                        <div class="relative">
                            <input type="number" id="per_sf" name="per_sf" placeholder="0.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-rulers"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Manual per SF (override) -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="manual_per_sf" class="form-label">Manual Cost Per SF (optional)</label>
                        <div class="relative">
                            <input type="number" id="manual_per_sf" name="manual_per_sf" placeholder="0.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" min="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-rulers"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Use this to discount or adjust per-SF manually.</p>
                    </div>

                    <!-- Per-SF Mode & Min toggle -->
                    <div class="col-span-12 md:col-span-6">
                        <label class="form-label">Per-SF Mode</label>
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="inline-flex items-center gap-1 text-sm">
                                <input type="radio" name="persf_mode" value="base" id="persf_mode_base"
                                    class="ti-form-checkbox" checked>
                                <span>Use Base per SF</span>
                            </label>
                            <label class="inline-flex items-center gap-1 text-sm">
                                <input type="radio" name="persf_mode" value="manual" id="persf_mode_manual"
                                    class="ti-form-checkbox">
                                <span>Use Manual per SF</span>
                            </label>
                            <label class="inline-flex items-center gap-1 text-sm">
                                <input type="checkbox" id="enforce_min" class="ti-form-checkbox" checked>
                                <span>Enforce Min per SF</span>
                            </label>
                        </div>
                    </div>

                    <!-- Min per sf (guardrail) -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="minixsf" class="form-label">Min per SF (guardrail)</label>
                        <div class="relative">
                            <input type="number" id="minixsf" name="minixsf" placeholder="0.00"
                                class="ti-form-input rounded-sm ps-11" step="0.01" min="0" value="0">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-shield-check"></i>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">If enforced, effective per-SF = max(chosen per-SF, Min
                            per SF).</p>
                    </div>

                    <!-- Total Cost (computed) -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="total_cost" class="form-label">Total Cost ($)</label>
                        <div class="relative">
                            <input type="number" id="total_cost" name="total_cost" placeholder="Auto-calculated"
                                class="ti-form-input rounded-sm ps-11 bg-gray-50" step="0.01" readonly>
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-calculator"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Profit (computed) -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="profit" class="form-label">Profit ($)</label>
                        <div class="relative">
                            <input type="number" id="profit" name="profit" placeholder="Auto-calculated"
                                class="ti-form-input rounded-sm ps-11 bg-gray-50" step="0.01" readonly>
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-graph-up"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Profit % (computed) -->
                    <div class="col-span-12 md:col-span-4">
                        <label for="profit_percent" class="form-label">Profit Percentage (%)</label>
                        <div class="relative">
                            <input type="number" id="profit_percent" name="profit_percent"
                                placeholder="Auto-calculated" class="ti-form-input rounded-sm ps-11 bg-gray-50"
                                step="0.01" readonly>
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-percent"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Team -->
                    <div class="col-span-12 md:col-span-6">
                        <label for="team" class="form-label">Team Members</label>
                        <div class="relative">
                            <input type="text" id="team" name="team" placeholder="e.g. John, Alex, Maria"
                                class="ti-form-input rounded-sm ps-11">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none z-20">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="col-span-12">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="ti-form-input rounded-sm w-full"
                            placeholder="Optional remarks or breakdowns..."></textarea>
                    </div>
                </div>

                <div class="flex justify-between items-center gap-2 mt-4">
                    <div class="text-xs text-gray-500">
                        Formula: <strong>Total Cost</strong> = Labor + Subs + Food/RBNB + (Material + SF×perSF) + Comi
                    </div>
                    <div class="flex gap-2">
                        <button type="reset"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="mx-1">Cancel</span>
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="bi bi-save2"></i> <span class="mx-1">Save Record</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        </div>
        <div class="col-md-5">
            <div id="profit-kpis" class="grid grid-cols-12 gap-4 mb-3">
                <div class="col-span-12 md:col-span-6">
                    <div class="rounded-lg border p-4 bg-white flex items-center justify-between">
                        <div>
                            <div class="text-xs uppercase tracking-wide text-gray-500">Profit ($)</div>
                            <div id="kpi-profit" class="text-3xl font-semibold">—</div>
                            <div id="kpi-badges" class="flex flex-wrap gap-2 mt-2 text-xs">
                                <span id="badge-contract-sf" class="hidden px-2 py-1 rounded bg-gray-100">Contract/SF:
                                    —</span>
                                <span id="badge-cost-sf" class="hidden px-2 py-1 rounded bg-gray-100">Cost/SF: —</span>
                                <span id="badge-profit-sf" class="hidden px-2 py-1 rounded bg-gray-100">Profit/SF:
                                    —</span>
                                <span id="badge-profit-hr" class="hidden px-2 py-1 rounded bg-gray-100">Profit/Hour:
                                    —</span>
                                <span id="badge-sf-discount"
                                    class="hidden px-2 py-1 rounded bg-amber-100 text-amber-800">Per-SF Adjusted</span>
                            </div>
                        </div>
                        <i class="bi bi-graph-up-arrow text-2xl"></i>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <div class="rounded-lg border p-4 bg-white flex items-center justify-between">
                        <div>
                            <div class="text-xs uppercase tracking-wide text-gray-500">Profit (%)</div>
                            <div id="kpi-profit-percent" class="text-3xl font-semibold">—</div>
                            <div class="text-xs text-gray-500 mt-1">Of Contract Amount</div>
                        </div>
                        <i class="bi bi-percent text-2xl"></i>
                    </div>
                </div>
            </div>
            <div id="calc-card" class="rounded-lg border p-4 bg-white mb-3">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Calculations</div>
                    <i class="bi bi-calculator text-xl"></i>
                </div>
                <div class="space-y-1 text-sm">
                    <div>
                        <span class="font-medium">Effective Per SF</span>:
                        <span id="calc-effpersf-eq" class="text-gray-500">—</span>
                        <span class="ml-2" id="calc-effpersf-val">—</span>
                    </div>
                    <div>
                        <span class="font-medium">SF Cost</span>:
                        <span id="calc-sfcost-eq" class="text-gray-500">per SF × SF = —</span>
                        <span class="ml-2" id="calc-sfcost-val">—</span>
                    </div>
                    <div>
                        <span class="font-medium">Total Material</span>:
                        <span id="calc-totalmat-eq" class="text-gray-500">(Material Base) + (SF × per SF) = —</span>
                        <span class="ml-2" id="calc-totalmat-val">—</span>
                    </div>
                    <div>
                        <span class="font-medium">Labor Used</span>:
                        <span id="calc-labor-eq" class="text-gray-500">—</span>
                        <span class="ml-2" id="calc-labor-val">—</span>
                    </div>
                    <div>
                        <span class="font-medium">Total Cost</span>:
                        <span id="calc-totalcost-eq" class="text-gray-500">Labor + Sub + Food + Total Material + Comi =
                            —</span>
                        <span class="ml-2" id="calc-totalcost-val">—</span>
                    </div>
                    <div>
                        <span class="font-medium">Profit ($)</span>:
                        <span id="calc-profit-eq" class="text-gray-500">Contract − Total Cost = —</span>
                        <span class="ml-2" id="calc-profit-val">—</span>
                    </div>
                    <div>
                        <span class="font-medium">Profit %</span>:
                        <span id="calc-profitpct-eq" class="text-gray-500">(Profit ÷ Contract) × 100 = —</span>
                        <span class="ml-2" id="calc-profitpct-val">—</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="segment-1" role="tabpanel" aria-labelledby="segment-item-1">
       
    </div>

    <!-- ===== REAL-TIME CALCULATOR ===== -->
    <script>
        (function() {
            const $ = (id) => document.getElementById(id);

            // Inputs
            const F = {
                contract: $('contract_amount'),
                hrs: $('hours'),
                pw: $('pw'),
                subs: $('subs'),
                food: $('food_rbnb'),
                sf: $('square_feet'),
                perSf: $('per_sf'),
                manualPerSf: $('manual_per_sf'),
                minPerSf: $('minixsf'),
                comi: $('commission'),
                laborIn: $('labor_inhouse'),
                laborModeInput: $('labor_mode_input'),
                laborModeDerived: $('labor_mode_derived'),
                persfModeBase: $('persf_mode_base'),
                persfModeManual: $('persf_mode_manual'),
                enforceMin: $('enforce_min'),
                materialBase: $('material'),
            };

            // Outputs
            const outTotalCost = $('total_cost');
            const outProfit = $('profit');
            const outProfitPct = $('profit_percent');

            // Header KPIs & badges
            const kpiProfit = $('kpi-profit');
            const kpiProfitPct = $('kpi-profit-percent');
            const badgeContractSf = $('badge-contract-sf');
            const badgeCostSf = $('badge-cost-sf');
            const badgeProfitSf = $('badge-profit-sf');
            const badgeProfitHr = $('badge-profit-hr');
            const badgeSfDiscount = $('badge-sf-discount');

            // Calculation card elements
            const calc = {
                effPerSfEq: $('calc-effpersf-eq'),
                effPerSfVal: $('calc-effpersf-val'),
                sfEq: $('calc-sfcost-eq'),
                sfVal: $('calc-sfcost-val'),
                matEq: $('calc-totalmat-eq'),
                matVal: $('calc-totalmat-val'),
                laborEq: $('calc-labor-eq'),
                laborVal: $('calc-labor-val'),
                totalEq: $('calc-totalcost-eq'),
                totalVal: $('calc-totalcost-val'),
                profitEq: $('calc-profit-eq'),
                profitVal: $('calc-profit-val'),
                profitPctEq: $('calc-profitpct-eq'),
                profitPctVal: $('calc-profitpct-val'),
            };

            const materialHint = $('material-hint');

            const num = (v) => {
                const n = parseFloat(v);
                return isNaN(n) ? 0 : n;
            };

            const money = (v) =>
                isFinite(v) ?
                new Intl.NumberFormat(undefined, {
                    style: 'currency',
                    currency: 'USD',
                    maximumFractionDigits: 2,
                }).format(v) :
                '—';

            function compute() {
                const contract = num(F.contract?.value);
                const hrs = num(F.hrs?.value);
                const pw = num(F.pw?.value);
                const subs = num(F.subs?.value);
                const food = num(F.food?.value);
                const sf = num(F.sf?.value);

                const perSfBase = num(F.perSf?.value);
                const perSfManual = num(F.manualPerSf?.value);
                const minPerSf = num(F.minPerSf?.value);
                const enforceMin = F.enforceMin?.checked === true;

                const comi = num(F.comi?.value);
                const laborIn = num(F.laborIn?.value);
                const matBase = num(F.materialBase?.value);

                const useDerivedLabor = F.laborModeDerived?.checked === true;
                const labor = useDerivedLabor ? (hrs * pw) : laborIn;

                // Choose per-SF mode
                const useManualPerSf = F.persfModeManual?.checked === true;
                const chosenPerSf = useManualPerSf ? perSfManual : perSfBase;

                // Apply min rule if enforced
                const effPerSf = enforceMin ? Math.max(chosenPerSf, minPerSf) : chosenPerSf;

                // Discount badge (manual lower than base and not overridden by min)
                const manualIsDiscount = useManualPerSf && (perSfManual < perSfBase) && (!enforceMin || effPerSf ===
                    perSfManual);
                if (manualIsDiscount) {
                    badgeSfDiscount.classList.remove('hidden');
                    badgeSfDiscount.textContent =
                        `Per-SF Adjusted ↓ (${perSfBase.toFixed(2)} → ${perSfManual.toFixed(2)})`;
                } else {
                    badgeSfDiscount.classList.add('hidden');
                }

                // SF cost & materials
                const sfCost = sf * effPerSf;
                const totalMaterial = matBase + sfCost;

                // Totals
                const totalCost = labor + subs + food + totalMaterial + comi;
                const profit = contract - totalCost;
                const profitPct = contract > 0 ? (profit / contract) * 100 : 0;

                // Write outputs
                outTotalCost.value = totalCost.toFixed(2);
                outProfit.value = profit.toFixed(2);
                outProfitPct.value = profitPct.toFixed(2);

                // Header KPIs (colorized)
                kpiProfit.textContent = money(profit);
                kpiProfitPct.textContent = profitPct.toFixed(2) + '%';
                kpiProfit.classList.remove('text-emerald-600', 'text-rose-600');
                kpiProfitPct.classList.remove('text-emerald-600', 'text-rose-600');
                const pos = profit >= 0;
                kpiProfit.classList.add(pos ? 'text-emerald-600' : 'text-rose-600');
                kpiProfitPct.classList.add(pos ? 'text-emerald-600' : 'text-rose-600');

                // Badges
                const contractPerSf = sf > 0 ? contract / sf : 0;
                const costPerSf = sf > 0 ? totalCost / sf : 0;
                const profitPerSf = sf > 0 ? profit / sf : 0;
                const profitPerHour = hrs > 0 ? profit / hrs : 0;

                if (sf > 0) {
                    badgeContractSf.classList.remove('hidden');
                    badgeContractSf.textContent = `Contract/SF: ${money(contractPerSf)}`;
                    badgeCostSf.classList.remove('hidden');
                    badgeCostSf.textContent = `Cost/SF: ${money(costPerSf)}`;
                    badgeProfitSf.classList.remove('hidden');
                    badgeProfitSf.textContent = `Profit/SF: ${money(profitPerSf)}`;
                } else {
                    badgeContractSf.classList.add('hidden');
                    badgeCostSf.classList.add('hidden');
                    badgeProfitSf.classList.add('hidden');
                }

                if (hrs > 0) {
                    badgeProfitHr.classList.remove('hidden');
                    badgeProfitHr.textContent = `Profit/Hour: ${money(profitPerHour)}`;
                } else {
                    badgeProfitHr.classList.add('hidden');
                }

                // Hints & equations
                if (materialHint) {
                    materialHint.textContent =
                        `Total Material = Material Base (${money(matBase)}) + (SF × per-SF) (${sf} × ${effPerSf.toFixed(2)})`;
                }

                // Calc card lines
                if (calc.effPerSfEq) {
                    const modeText = useManualPerSf ? `Manual per-SF (${perSfManual || 0})` :
                        `Base per-SF (${perSfBase || 0})`;
                    const minText = enforceMin ? `; Min=${minPerSf || 0} ⇒ max(${chosenPerSf || 0}, ${minPerSf || 0})` :
                        '';
                    calc.effPerSfEq.textContent = `${modeText}${minText}`;
                }
                if (calc.effPerSfVal) calc.effPerSfVal.textContent = `= ${effPerSf.toFixed(2)}`;

                if (calc.sfEq) calc.sfEq.textContent = `per-SF × SF = ${effPerSf.toFixed(2)} × ${sf}`;
                if (calc.sfVal) calc.sfVal.textContent = `= ${money(sfCost)}`;

                if (calc.matEq) calc.matEq.textContent =
                    `(Material Base) + (SF × per-SF) = ${money(matBase)} + ${money(sfCost)}`;
                if (calc.matVal) calc.matVal.textContent = `= ${money(totalMaterial)}`;

                if (calc.laborEq) calc.laborEq.textContent = useDerivedLabor ? `(HRS × PW) = ${hrs} × ${pw}` :
                    `Labor (In-House)`;
                if (calc.laborVal) calc.laborVal.textContent = `= ${money(labor)}`;

                if (calc.totalEq)
                    calc.totalEq.textContent =
                    `Labor + Sub + Food + Total Material + Comi = ${money(labor)} + ${money(subs)} + ${money(food)} + ${money(totalMaterial)} + ${money(comi)}`;
                if (calc.totalVal) calc.totalVal.textContent = `= ${money(totalCost)}`;

                if (calc.profitEq) calc.profitEq.textContent =
                    `Contract − Total Cost = ${money(contract)} − ${money(totalCost)}`;
                if (calc.profitVal) calc.profitVal.textContent = `= ${money(profit)}`;

                if (calc.profitPctEq) calc.profitPctEq.textContent =
                    `(Profit ÷ Contract) × 100 = ${money(profit)} ÷ ${money(contract)} × 100`;
                if (calc.profitPctVal) calc.profitPctVal.textContent = `= ${profitPct.toFixed(2)}%`;
            }

            // Watch inputs
            [
                'contract_amount',
                'hours',
                'pw',
                'subs',
                'food_rbnb',
                'square_feet',
                'per_sf',
                'manual_per_sf',
                'minixsf',
                'commission',
                'labor_inhouse',
                'material'
            ].forEach((id) => $(id)?.addEventListener('input', compute));

            // Watch toggles
            ['labor_mode_input', 'labor_mode_derived', 'persf_mode_base', 'persf_mode_manual', 'enforce_min']
            .forEach((id) => $(id)?.addEventListener('change', compute));

            // Reset handler
            document.getElementById('profit-tracker-form')?.addEventListener('reset', () => {
                setTimeout(() => {
                    kpiProfit.textContent = '—';
                    kpiProfitPct.textContent = '—';
                    outTotalCost.value = '';
                    outProfit.value = '';
                    outProfitPct.value = '';

                    ['badge-contract-sf', 'badge-cost-sf', 'badge-profit-sf', 'badge-profit-hr',
                        'badge-sf-discount'
                    ].forEach(id => {
                        const el = $(id);
                        if (el) {
                            el.classList.add('hidden');
                            el.textContent = '—';
                        }
                    });

                    [
                        'calc-effpersf-eq', 'calc-effpersf-val',
                        'calc-sfcost-eq', 'calc-sfcost-val',
                        'calc-totalmat-eq', 'calc-totalmat-val',
                        'calc-labor-eq', 'calc-labor-val',
                        'calc-totalcost-eq', 'calc-totalcost-val',
                        'calc-profit-eq', 'calc-profit-val',
                        'calc-profitpct-eq', 'calc-profitpct-val',
                    ].forEach((id) => {
                        const el = $(id);
                        if (el) el.textContent = '—';
                    });

                    if ($('material-hint')) $('material-hint').textContent = '';

                    // defaults
                    if (F.laborModeInput) F.laborModeInput.checked = true;
                    if (F.persfModeBase) F.persfModeBase.checked = true;
                    if (F.enforceMin) F.enforceMin.checked = true;
                }, 0);
            });

            compute();
        })();
    </script>

</x-app-layout>
