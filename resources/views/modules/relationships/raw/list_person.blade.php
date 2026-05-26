@php
    if (Auth::user()->role == 'Virtual Assistant') {
        $clientId = Auth::user()->company;
    } elseif (Auth::user()->role == 'Sub-Client') {
        $client = App\Models\Clients::where('email', Auth::user()->email)->first();
        $clientId = $client->id;
    } else {
        $lead = App\Models\Lead::where('email', Auth::user()->email)
            ->select('id')
            ->first();
        $clientId = $lead->id;
    }

    $clients = App\Models\Clients::select(
        'id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'position',
        'location',
        'created_at',
        'photo',
        'company_id',
    )
        ->where('company_id', $id)
        ->where('lead_id', $clientId)
        ->get()
        ->map(function ($client) {
            $credit_total = App\Models\Credit::where('client_id', $client->id)->where('type', 'add')->sum('amount');

            $credit_charge = App\Models\Credit::where('client_id', $client->id)->where('type', 'charge')->sum('amount');

            $remaining_credit = $credit_total - $credit_charge;
            $percentage = $credit_total > 0 ? ($remaining_credit / $credit_total) * 100 : 0;

            // Determine progress bar color
            $progressClass = 'bg-success'; // Default
            $progressClassText = 'text-success'; // Default

            if ($percentage < 20) {
                $progressClass = 'bg-danger';
                $progressClassText = 'text-danger';
            } elseif ($percentage >= 20 && $percentage <= 60) {
                $progressClass = 'bg-primary';
                $progressClassText = 'text-primary';
            }

            $user = App\Models\User::where('email', $client->email)->first();

            $company = App\Models\Contact::where('id', $client->company_id)->first();

            return [
                'name' => $client->first_name . ' ' . $client->last_name,
                'id' => $user->id ?? '',
                'created_at' => '', //$client->created_at->format('M d, Y - h:i A') ?? ,
                'email' => $client->email,
                'phone' => $client->phone ?: 'N/A',
                'position' => $client->position ?: 'N/A',
                'location' => $client->location ?: 'N/A',
                'photo' => $client->photo,
                'company' => $company->company_name ?? 'N/A',
                'company_address' => $company->city_zip_code . ', ' . $company->state ?? 'N/A',

                // Credit Data
                'credit_total' => number_format($credit_total, 0),
                'credit_charge' => $credit_charge,
                'remaining_credit' => number_format($remaining_credit, 0),
                'percentage' => number_format($percentage, 0),
                'progressClass' => $progressClass,
                'progressClassText' => $progressClassText,
            ];
        });
@endphp

<script src="/assets/libs/gridjs/gridjs.umd.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const clients = @json($clients);

        const data = clients.map(client => [
            [client.name, client.created_at, client.photo ? `${ asset("storage") }/${client.photo}` :
                "/user.png", client.location
            ],
            [client.email, client.phone], 
            [client.company, client.position], 
            [client.remaining_credit, client.credit_total, client.percentage, client.progressClass,
                client.progressClassText
            ], 
            client.id, 
            client.photo ? `${ asset("storage") }/${client.photo}` : "/user.png",

        ]);

        console.log("Grid Data:", data); 

        new gridjs.Grid({
            columns: [{
                    name: "Contact Information",
                    formatter: (cell, row) => gridjs.html(`
                        <div class="flex items-center gap-2">
                            <div class="leading-none">
                                <span class="avatar avatar-rounded avatar-md">
                                    <img src="${cell[2]}" 
                                        alt="Profile Image" 
                                        onerror="this.src='/user.png'">
                                </span>
                            </div>
                            <div>
                                <a data-bs-toggle="offcanvas" data-hs-overlay="#offcanvasExample">
                                    <span class="block font-medium text-[14px]">${cell[0]}</span>
                                </a>
                                <div class="hs-tooltip ti-main-tooltip">
                                    <span class="block text-textmuted dark:text-textmuted/50 text-[11px]">
                                        <i class="bi me-1 bi-pin-map align-middle text-[14px] text-textmuted dark:text-textmuted/50 inline-block"></i>
                                        ${cell[3]}
                                        <span class="hs-tooltip-content ti-main-tooltip-content py-1 px-2 !bg-primary !border-primary !text-xs !font-medium !text-white shadow-sm hidden" role="tooltip" style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(660px, -531px);" data-popper-placement="top">
                                            Last Updated
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    `)
                },
                {
                    name: "Contact Information",
                    formatter: (cell) => gridjs.html(`
                        <span class="block mb-1">
                            <i class="ri-mail-line me-2 align-middle text-[14px] text-textmuted dark:text-textmuted/50 inline-block"></i>
                            ${cell[0]}
                        </span>
                        <span class="block mb-1">
                            <i class="ri-phone-line me-2 align-middle text-[14px] text-textmuted dark:text-textmuted/50 inline-block"></i>
                            ${cell[1]}
                        </span>
                    `)
                },
                {
                    name: "Email / Phone",
                    formatter: (cell) => gridjs.html(`
                        <span class="block mb-1">
                            <i class="ri-building-line me-2 align-middle text-[14px] text-textmuted dark:text-textmuted/50 inline-block"></i>
                            ${cell[0]}
                        </span>
                            <span class="block mb-1">
                            <i class="bi bi-info-circle me-2 text-[13px] align-middle"></i>
                            ${cell[1]}
                        </span>
                    `)
                },
                {
                    name: "Status",
                    width: "100px",
                    formatter: (cell) => gridjs.html(`
                        <div style="margin-top: 6px;">
                                <center>
                            <span class="badge leading-none bg-primary/10 text-primary w-full !mb-0">
                                    <i class="bi bi-check text-[18px] "></i>
                                    Active
                            </span>
                                </center>
                        </div>
                    `)
                },
                {
                    name: gridjs.html(`<div style="text-align:center;">Actions</div>`),
                    width: "220px",
                    formatter: (cell) => gridjs.html(`
                        <div class="btn-list">
                            <center>
                                <a href="/chat/${cell}" class="ti-btn ti-btn-sm ti-btn-soft-success !mb-0">
                                    <i class="bi bi-chat-dots"></i>
                                    Chat
                                </a>
                                <a href="/client/view/${cell}" class="ti-btn ti-btn-sm ti-btn-soft-info !mb-0">
                                    <i class="ri-eye-line"></i>
                                    View
                                </a>
                                <button class="ti-btn ti-btn-sm ti-btn-soft-warning !mb-0">
                                    <i class="ri-pencil-line"></i>
                                    Edit
                                </button>
                            </center>
                        </div> 
                    `)
                }
            ],
            pagination: true,
            search: true,
            sort: true,
            data: () => {
                return new Promise(resolve => {
                    setTimeout(() => resolve(data), 500);
                });
            }
        }).render(document.getElementById("grid-loading"));
    });
</script>
