@include('modules.actions.table-mod')

<!-- Include DataTables CSS & jQuery (matches raw/list behavior) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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
    $(document).on('click', '#clientTable tbody tr', function (e) {
        let $row = $(this);
        let link = $row.data('href');
        if (!$(e.target).closest('button, input[type="checkbox"], a').length) {
            window.location.href = link;
        }
    });

    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#clientTable')) {
            $('#clientTable').DataTable().destroy();
        }

        var table = $('#clientTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('relationship.company') }}",
                beforeSend: function () {
                    $("#customLoader").show();
                },
                complete: function () {
                    $("#customLoader").hide();
                }
            },
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search here...",
            },
            columns: [{
                data: 'id',
                render: function (data) {
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
                render: function (data, type, row) {
                    const encryptedId = row.encrypted_id || row.id;
                    return `
                    <div class="hs-dropdown ti-dropdown">
                        <button type="button" class="ti-btn ti-btn-sm ti-btn-light hs-dropdown-toggle" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <div class="hs-dropdown-menu ti-dropdown-menu hidden">
                            <button type="button" onclick="previewRelationship('${encodeURIComponent(JSON.stringify(row))}')" class="ti-dropdown-item flex items-center gap-2 w-full text-start">
                                <i class="bi bi-eye text-info"></i>
                                <span>Preview</span>
                            </button>
                            <div class="ti-dropdown-divider"></div>
                            <button type="button" onclick="remove_data(${row.id}, 'company')" class="ti-dropdown-item flex items-center gap-2 text-danger">
                                <i class="bi bi-trash"></i>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>`;
                }
            }
            ],
            order: [
                [1, "asc"]
            ],
            rowCallback: function (row, data) {
                const encryptedId = data.encrypted_id || data.id;
                $(row).attr('data-href', `/relationship/list/details/${encryptedId}`); // Set row link
                $(row).addClass('cursor-pointer');
            },
            drawCallback: function () {
                if (window.HSStaticMethods && window.HSStaticMethods.autoInit) {
                    window.HSStaticMethods.autoInit();
                }
            },
            initComplete: function () {
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

    .cursor-pointer {
        cursor: pointer;
    }
</style>