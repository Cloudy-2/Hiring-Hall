@include('modules.actions.table-mod')

<div class="table-responsive-n">
    <table id="clientTable" class="table table-sm min-w-full !border border-defaultborder dark:border-defaultborder/10">
        <thead>
            <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                <th class="text-start" style="width: 5px">
                    <input type="checkbox" class="form-check-input mx-3" id="selectAll">
                </th>
                <th scope="col" class="text-start">Company</th>
                <th scope="col" class="text-start" id="type_th" style=" color: #364051 !important">Type</th>
                <th scope="col" class="text-start">Zip Code</th>
                <th scope="col" class="text-start">City</th>
                <th scope="col" class="text-start">State</th>
                <th scope="col" class="text-start">Phone</th>
                <th scope="col" class="text-start" id="action_th">Actions</th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(document).on('click', '#clientTable tbody tr', function(e) {
        let $row = $(this);
        let link = $row.data('href');
        if (!$(e.target).closest('button, input[type="checkbox"], a').length) {
            window.location.href = link;
        }
    });

    $(document).ready(function() {
        var table = $('#clientTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('api.relationships') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $("#customLoader").show();
                },
                complete: function() {
                    $("#customLoader").hide();
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
                    data: 'company_name',
                    name: 'company_name',
                    className: "text-start",

                },
                {
                    data: 'type',
                    name: 'type',
                    className: "text-start text-muted"
                },
                {
                    data: 'zip',
                    name: 'zip',
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
                        const encryptedId = row.encrypted_id || row.id;
                        return `<div class="hstack gap-1 text-[.9375rem]">
                            <center>

                                <span class="custom-tooltip">
                                    <a href="/relationship/list/details/${encryptedId}" class="ti-btn ti-btn-sm ti-btn-soft-info bg-info/10">
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
                }
            ],
            order: [
                [7, "asc"]
            ],
            rowCallback: function(row, data) {
                const encryptedId = data.encrypted_id || data.id;
                $(row).attr('data-href', `/relationship/list/details/${encryptedId}`); // Set row link
            },
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
