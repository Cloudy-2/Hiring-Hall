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

<div class="table-responsive">
    <table id="clientTable" class="table table-sm min-w-full !border border-defaultborder dark:border-defaultborder/10"
        style="min-height:  120px;;">
        <thead>
            <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                <th class="text-start" style="width: 5px">
                    <input type="checkbox" class="form-check-input mx-3" id="selectAll">
                </th>
                <th scope="col" class="text-start">Date</th>
                <th scope="col" class="text-start">Business</th>
                <th scope="col" class="text-start">Facebook</th>
                <th scope="col" class="text-start">Email</th>
                <th scope="col" class="text-start">Contacts</th>
                <th scope="col" class="text-start">Problem/Issue</th>
                <th scope="col" class="text-start">Location</th>
                <th scope="col" class="text-start">Employees</th>
                <th scope="col" class="text-start">Interested Service</th>
                <th scope="col" class="text-start">Availability</th>
            </tr>
        </thead>
    </table>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        var table = $('#clientTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('lead.facebook.data') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
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
                    data: 'date',
                    name: 'date',
                    className: "text-start"
                },
                {
                    data: 'business_name',
                    name: 'business_name',
                    className: "text-start"
                },
                {
                    data: 'facebook_name',
                    name: 'facebook_name',
                    className: "text-start"
                },
                {
                    data: 'email',
                    name: 'email',
                    className: "text-start"
                },
                {
                    data: 'contact_number',
                    name: 'contact_number',
                    className: "text-start"
                },
                {
                    data: 'company_problem',
                    name: 'company_problem',
                    className: "text-start"
                },
                {
                    data: 'location',
                    name: 'location',
                    className: "text-start"
                },
                {
                    data: 'no_of_employees',
                    name: 'no_of_employees',
                    className: "text-start"
                },
                {
                    data: 'interested_service',
                    name: 'interested_service',
                    className: "text-start"
                },
                {
                    data: 'availability',
                    name: 'availability',
                    className: "text-start"
                },
            ],
            order: [
                [1, "asc"]
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
        width: 100px !important;
    }
</style>
