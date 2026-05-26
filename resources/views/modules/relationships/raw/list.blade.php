<!-- Include DataTables CSS & jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('v1/style-table.css') }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


<div class="table-controls flex justify-between items-center mb-3">
    <!-- Bulk Actions Dropdown -->
    <div class="flex items-center gap-3">
        <select id="bulkAction" class="border rounded px-3 py-2">
            <option value="" disabled selected>Action</option>
            <option value="delete">🗑️ Move to Trash</option>
            <option value="export">📥 Export to CSV</option>
        </select>
        <button id="applyAction" class="bg-white text-dark px-4 py-2 rounded">
            <i class="bi bi-send"></i>
        </button>
        <div id="customSearchWrapper"></div>
    </div>

    <div class="flex items-center gap-3">
        <div id="customLengthWrapper"></div>
    </div>
</div>

<div class="table-responsive-n">
    <table id="clientTable" class="table table-sm min-w-full !border border-defaultborder dark:border-defaultborder/10"
        style="min-height: 460px;">
        <thead>
            <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                <th class="text-start">
                    <input type="checkbox" class="form-check-input mx-3" id="selectAll">
                </th>
                <th scope="col" class="text-start">Company</th>
                <th scope="col" class="text-start" id="type_th">Type</th>
                <th scope="col" class="text-start">Zip Code</th>
                <th scope="col" class="text-start">City</th>
                <th scope="col" class="text-start">State</th>
                <th scope="col" class="text-start">Phone</th>
                <th scope="col" class="text-start" id="action_th">Actions</th>
            </tr>
        </thead>
    </table>
</div>


<!-- JavaScript -->
<script>
    $(document).ready(function() {
        var table = $('#clientTable').DataTable({
            processing: true, // Disables the built-in loader
            serverSide: false,
            ajax: {
                url: "{{ route('clients.data') }}",
                beforeSend: function() {
                    $("#customLoader").show(); // Show custom loader before request
                },
                complete: function() {
                    $("#customLoader").hide(); // Hide custom loader after request
                }
            },
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search here...",
            },
            columns: [{
                    data: 'id',
                    render: function(data) {
                        return `<input type="checkbox" class="rowCheckbox form-check-input mx-3" style="border: 1px solid #202020" value="${data}">`;
                    }
                },
                {
                    data: 'company_name',
                    name: 'company_name',
                    className: "text-start"
                },
                {
                    data: 'type',
                    name: 'type',
                    className: "text-start text-muted"
                },
                {
                    data: 'zip_code',
                    name: 'zip_code',
                    className: "text-start"
                },
                {
                    data: 'city',
                    name: 'city',
                    className: "text-start"
                },
                {
                    data: 'state',
                    name: 'state',
                    className: "text-start"
                },
                {
                    data: 'phone',
                    name: 'phone',
                    className: "text-start"
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                    <div class="hstack gap-1 text-[.9375rem]">
                        <center>
                            <div class="hs-tooltip ti-main-tooltip [--placement:left]">
                                <a href="javascript:void(0);" class="editClient ti-btn ti-btn-icon ti-btn-sm bg-info/10" data-id="${row.id}">
                                    <i class="ri-edit-line text-info"></i>
                                    <span class="hs-tooltip-content ti-main-tooltip-content py-1 px-2 !bg-info 
                                        !text-xs !font-medium !text-white shadow-sm dark:bg-slate-700 hidden"
                                        role="tooltip" data-popper-placement="top"> Edit </span>
                                </a>
                            </div>
                            <div class="hs-tooltip ti-main-tooltip [--placement:left]">
                                <a href="javascript:void(0);" class="deleteClient ti-btn ti-btn-icon ti-btn-sm bg-danger/10" data-id="${row.id}">
                                    <i class="ri-delete-bin-line text-danger"></i>
                                    <span class="hs-tooltip-content ti-main-tooltip-content py-1 px-2 !bg-danger 
                                        !text-xs !font-medium !text-white shadow-sm dark:bg-slate-700 hidden"
                                        role="tooltip" data-popper-placement="top"> Delete </span>
                                </a>
                            </div>
                        </center>
                    </div>
                `;
                    }
                }
            ],
            order: [
                [7, "asc"]
            ],
            initComplete: function() {
                $("#customSearchWrapper").html($("#clientTable_filter"));
                $("#customLengthWrapper").html($("#clientTable_length"));
            }
        });
        // Select/Deselect All Checkboxes
        $("#selectAll").on("click", function() {
            $(".rowCheckbox").prop("checked", this.checked);
        });

        // Apply Bulk Action
        $("#applyAction").on("click", function() {
            let selectedIds = $(".rowCheckbox:checked").map(function() {
                return this.value;
            }).get();
            let action = $("#bulkAction").val();

            if (selectedIds.length === 0 || !action) {
                alert("Select at least one record and an action.");
                return;
            }

            if (action === "delete") {
                if (confirm("Are you sure you want to delete selected clients?")) {
                    $.ajax({
                        url: "{{ route('clients.delete') }}",
                        type: "POST",
                        data: {
                            ids: selectedIds,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            alert(response.success);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            alert("Error: " + xhr.responseText);
                        }
                    });
                }
            } else if (action === "export") {
                alert("Exporting: " + selectedIds.join(", "));
            }
        });

        // Delete Single Client
        $(document).on("click", ".deleteClient", function() {
            let clientId = $(this).data("id");
            if (confirm("Delete this client?")) {
                $.ajax({
                    url: "{{ route('clients.delete') }}",
                    type: "POST",
                    data: {
                        ids: [clientId],
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        alert(response.success);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        alert("Error: " + xhr.responseText);
                    }
                });
            }
        });

        function boldNumbersInInfo() {
            let info = $('.dataTables_info').html();
            info = info.replace(/(\d+)/g, '<strong>$1</strong>'); // Wrap numbers in <strong>
            $('.dataTables_info').html(info);
        }

        // Reapply bold effect every time the table updates
        $('#clientTable').on('draw.dt', function() {
            boldNumbersInInfo();
        });

        // Edit Button Click (Just Alert for Now)
        $(document).on("click", ".editClient", function() {
            let clientId = $(this).data("id");
            alert("Edit client with ID: " + clientId);
        });
    });
</script>



{{-- @php
    use Carbon\Carbon;
    use App\Models\Clients;
    use App\Models\Lead;
    use App\Models\Contact;
    use Illuminate\Support\Facades\Auth;

    // Determine client ID based on user role
    $user = Auth::user();
    $clientId = null;

    if ($user->role === 'Virtual Assistant') {
        $clientId = $user->company;
    } elseif ($user->role === 'Sub-Client') {
        $clientId = Clients::where('email', $user->email)->value('id');
    } else {
        $clientId = Lead::where('email', $user->email)->value('id');
    }

    // Fetch contacts related to the client
    $clients = Contact::where('client_id', $clientId)
        ->get()
        ->map(function ($client) {
            return [
                'id' => $client->id ?: 'N/A',
                'lead_source' => $client->lead_source ?: 'N/A',
                'name' => $client->company_name ?: 'N/A',
                'type' => $client->type ?: 'N/A',
                'phone' => $client->phone ?: 'N/A',
                'zip_code' => $client->zip_code ?: 'N/A',
                'city' => $client->city ?: 'N/A',
                'state' => $client->state ?: 'N/A',
                'status' => $client->status ?: 'N/A',
                'created' => $client->due_date ? date('D, M. d, Y', strtotime($client->due_date)) : 'N/A',
                'priority' => $client->priority ?: 'N/A',
                'assigned' => is_array($client->assigned_to) 
                    ? $client->assigned_to 
                    : explode(',', $client->assigned_to ?? ''),
            ];
        });
@endphp


<!-- Include DataTables CSS & JS -->

<!-- Table -->
<div class="table-controls flex justify-between items-center mb-3">
    <!-- Bulk Actions Dropdown -->
    <div class="flex items-center gap-3">
        <select id="bulkAction" class="border rounded px-3 py-2">
            <option value="" disabled selected>Action</option>
            <option value="delete">🗑️ Move to Trash</option>
            <option value="export">📥 Export to CSV</option>
        </select>
        <button id="applyAction" class="bg-white text-dark px-4 py-2 rounded">
            <i class="bi bi-send"></i>
        </button>
        <div id="customSearchWrapper"></div>
    </div>

    <div class="flex items-center gap-3">
        <div id="customLengthWrapper"></div>
    </div>
</div>


<div class="table-responsive">
    <table id="clientTable"
        class="table whitespace-nowrap table-sm min-w-full !border border-defaultborder dark:border-defaultborder/10">
        <thead>
            <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                <th class="text-start" style="padding-left: 15px !important;">
                    <input type="checkbox" class="form-check-input" id="selectAll">
                </th>
                <th scope="col" class="text-start">Company</th>
                <th scope="col" class="text-start" id="type_th">Type</th>
                <th scope="col" class="text-start">Zip Code</th>
                <th scope="col" class="text-start">City</th>
                <th scope="col" class="text-start">State</th>
                <th scope="col" class="text-start">Phone</th>
                <th scope="col" class="text-start" id="date_th">Date Created</th>
                <th scope="col" class="text-start" id="action_th">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $index => $client)
                <tr class="!border-b !border-defaultborder dark:border-defaultborder/10 cursor-pointer">
                    <td class="text-start" style="padding-left: 15px !important;">
                        <input type="checkbox" class="rowCheckbox form-check-input" value="{{ $client['id'] }}">
                    </td>
                    <td class="text-start">{{ $client['name'] }}</td>
                    <td class="text-start" id="text-muted">{{ $client['type'] }}</td>
                    <td class="text-start">{{ $client['zip_code'] }}</td>
                    <td class="text-start">{{ $client['city'] }}</td>
                    <td class="text-start">{{ $client['state'] }}</td>
                    <td class="text-start">{{ $client['phone'] }}</td>
                    <td>{{ $client['created'] }}</td>
                    <td>
                        <div class="hstack gap-1 text-[.9375rem]">
                            <center>

                                <div class="hs-tooltip ti-main-tooltip [--placement:left]">
                                    <a href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-sm bg-info/10"><i
                                            class="ri-edit-line text-info"></i>
                                        <span
                                            class="hs-tooltip-content ti-main-tooltip-content py-1 px-2 !bg-info !text-xs !font-medium !text-white shadow-sm dark:bg-slate-700 hidden"
                                            role="tooltip"
                                            style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(289px, -146px);"
                                            data-popper-placement="top"> Edit </span>
                                    </a>
                                </div>
                                <div class="hs-tooltip ti-main-tooltip [--placement:left]">
                                    <a href="javascript:void(0);" class="ti-btn ti-btn-icon ti-btn-sm bg-success/10"><i
                                            class="ri-user-add-line text-success"></i>
                                        <span
                                            class="hs-tooltip-content ti-main-tooltip-content py-1 px-2 !bg-success !text-xs !font-medium !text-white shadow-sm dark:bg-slate-700 hidden"
                                            role="tooltip"
                                            style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(289px, -146px);"
                                            data-popper-placement="top"> Add </span>
                                    </a>
                                </div>
                            </center>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        var table = $('#clientTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('clients.data') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'company_name', name: 'company_name' },
                { data: 'type', name: 'type' },
                { data: 'zip_code', name: 'zip_code' },
                { data: 'city', name: 'city' },
                { data: 'state', name: 'state' },
                { data: 'phone', name: 'phone' },
                { data: 'due_date', name: 'due_date' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[7, "asc"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search here.."
            }
        });
    });
    // $(document).ready(function() {
    //     var table = $('#clientTable').DataTable({
    //         "paging": true,
    //         "searching": true,
    //         "ordering": true,
    //         "info": true,
    //         "lengthChange": true,
    //         "order": [
    //             [7, "asc"]
    //         ],
    //         "language": {
    //             "search": "_INPUT_",
    //             "searchPlaceholder": "Search here.."
    //         },
    //         "initComplete": function() {
    //             // Move entries per page dropdown to the custom div
    //             $("#clientTable_length").appendTo("#customLengthWrapper");

    //             // Move the search box to the custom div
    //             $("#clientTable_filter").appendTo("#customSearchWrapper");

    //             // Apply bold effect to numbers in info
    //             boldNumbersInInfo();
    //         }
    //     });

    //     // Select/Deselect All Checkboxes
    //     $("#selectAll").on("click", function() {
    //         $(".rowCheckbox").prop("checked", this.checked);
    //     });

    //     // Apply Bulk Action
    //     $("#applyAction").on("click", function() {
    //         let selectedIds = [];
    //         $(".rowCheckbox:checked").each(function() {
    //             selectedIds.push($(this).val());
    //         });

    //         let action = $("#bulkAction").val();
    //         if (selectedIds.length === 0 || action === "") {
    //             Swal.fire({
    //                 title: "Attention",
    //                 text: "Kindly select at least one record and an associated action.",
    //                 icon: "warning"
    //             });
    //             return;
    //         }

    //         if (action === "delete") {
    //             //alert("Deleting: " + selectedIds.join(", "));
    //             // AJAX delete logic
    //         } else if (action === "export") {
    //             alert("Exporting: " + selectedIds.join(", "));
    //             // Export logic
    //         }
    //     });

    //     // Function to bold numbers in DataTables info text
    //     function boldNumbersInInfo() {
    //         let info = $('.dataTables_info').html();
    //         info = info.replace(/(\d+)/g, '<strong>$1</strong>'); // Wrap numbers in <strong>
    //         $('.dataTables_info').html(info);
    //     }

    //     // Reapply bold effect every time the table updates
    //     $('#clientTable').on('draw.dt', function() {
    //         boldNumbersInInfo();
    //     });
    // });
</script> --}}

