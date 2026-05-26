<div class="table-controls flex justify-between items-center mb-3">
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
        style="min-height:  120px;;">
        <thead>
            <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                <th class="text-start" style="width: 5px">
                    <input type="checkbox" class="form-check-input mx-3" id="selectAll">
                </th>
                <th class="text-start">Name</th>
                <th class="text-start">Company</th>
                <th class="text-start">Position</th>
                <th class="text-start">Email</th>
                <th class="text-start">Phone</th>
                <th class="text-start" id="action_th">Actions</th>
            </tr>
        </thead>
    </table>
</div>
<script>
    $(document).ready(function() {
        var table = $('#clientTable').DataTable({
            processing: true,
            serverSide: false, // Since we are loading all data at once
            ajax: {
                url: "{{ route('relationship.contacts') }}",
                type: "GET",
                beforeSend: function() {
                    $("#customLoader").removeClass("hidden");
                },
                complete: function() {
                    $("#customLoader").addClass("hidden");
                },
                dataSrc: function(json) {
                    console.log("API Response:", json); // 🔴 LOG RESPONSE TO CONSOLE
                    return json.data || []; // Ensure it returns an array
                },
                error: function(xhr, error, thrown) {
                    console.error("AJAX Error:", xhr.responseText); // 🔴 LOG ERROR
                }
            },
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search here...",
            },
            columns: [{
                    data: 'id',
                    render: function(data) {
                        return `<input type="checkbox" class="rowCheckbox form-check-input mx-3" value="${data}">`;
                    },
                    orderable: false
                }, 
                {
                    data: 'name',
                    render: function(data, type, row) {
                        let photoUrl = row.photo ? row.photo : '/user.png'; // Fallback to default image

                        return `
                            <span style="display: flex; align-items: center;" class="!text-dark">
                                <span class="avatar me-2 avatar-sm avatar-rounded"> 
                                    <img src="${photoUrl}" alt="Profile" onerror="this.onerror=null;this.src='/user.png';">
                                </span>
                                ${data}                        
                            </span>
                        `;
                    }
                },
                {
                    data: 'company',
                    className: "text-start"
                },
                {
                    data: 'position',
                    className: "text-start"
                },
                {
                    data: 'email',
                    className: "text-start"
                },
                {
                    data: 'phone',
                    className: "text-start"
                },
                data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<div class="hstack gap-1 text-[.9375rem]">
                            <center>

                                <span class="custom-tooltip">
                                    <a href="/relationship/list/details/${row.encrypted_id || row.id}" class="ti-btn ti-btn-sm ti-btn-soft-info bg-info/10">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <span class="tooltip-text">Preview</span>
                                </span>

                                <span class="custom-tooltip">
                                    <a onclick="remove_data(${row.id}, 'company')" href="javascript:void(0);" class="ti-btn ti-btn-sm ti-btn-soft-danger bg-danger/10">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <span class="tooltip-text">Delete</span>
                                </span>

                            </center>
                        </div>`;
                    }
            ],
            order: [
                [2, "asc"]
            ],
            initComplete: function() {
                $("#customSearchWrapper").html($("#clientTable_filter"));
                $("#customLengthWrapper").html($("#clientTable_length"));
            }
        });

    });
</script>

<style>
    #action_th {
        width: 80px !important;
    }
</style>
