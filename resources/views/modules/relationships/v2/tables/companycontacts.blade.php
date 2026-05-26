@include('modules.actions.table-mod')

<div class="table-responsive-n">
    <table id="clientTable" class="table table-sm min-w-full !border border-defaultborder dark:border-defaultborder/10">
        <thead>
            <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                <th class="text-start" style="width: 5px">
                    <input type="checkbox" class="form-check-input mx-3" id="selectAll">
                </th>
                <th class="text-start">Name</th>
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
            serverSide: false,
            ajax: {
                url: "{{ route('api.relationship.contacts') }}",
                type: "POST",
                data: {
                    id: {{ $id }},
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $("#customLoader").removeClass("hidden");
                },
                complete: function() {
                    $("#customLoader").addClass("hidden");
                },
                dataSrc: function(json) {
                    return json.data || [];
                },
                error: function(xhr, error, thrown) {
                    console.error("AJAX Error:", xhr.responseText);
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
                        let photoUrl = row.photo ? row.photo :
                            '/user.png'; // Fallback to default image

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

                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        const encryptedId = row.encrypted_id || row.id;
                        return `<div class="hstack gap-1 text-[.9375rem]">
                            <center>
                                <div class="hs-tooltip ti-main-tooltip [--placement:left]">
                                    <a href="/sales/relationship/list/details/${encryptedId}" target="_blank" 
                                        class="ti-btn ti-btn-sm bg-success/10">
                                        <i class="bi bi-eye text-success"></i>
                                          Engage
                                    </a>
                                    <a href="/relationship/list/edit/${row.company_id}/${encryptedId}" 
                                        class="ti-btn ti-btn-sm bg-info/10">
                                        <i class="bi bi-pencil text-info"></i>
                                          Edit
                                    </a>
                                     <a href="#" onclick="remove_data(${row.id}, 'contact-person')"
                                        class="ti-btn ti-btn-sm bg-danger/10">
                                        <i class="bi bi-trash text-danger"></i>
                                          Delete
                                    </a>
                                </div>
                            </center>
                        </div>`;
                    }
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
        width: 250px !important;
    }
</style>
