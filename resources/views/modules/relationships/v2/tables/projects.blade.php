@include('modules.actions.table-mod')

<div class="table-responsive-n">
    <table id="projectTable" class="table table-sm min-w-full !border border-defaultborder dark:border-defaultborder/10"
        style="min-height:  0px;;">
        <thead>
            <tr class="border-b border-defaultborder dark:border-defaultborder/10">
                <th class="text-start" id="action_th_s">&ensp;Code</th>
                <th class="text-start">Project</th>
                <th class="text-start" id="action_th_prj">Stage</th>
                <th class="text-start" id="action_th_prj">Action</th>
            </tr>
        </thead>
    </table>
</div>
<script>
    $(document).ready(function() {
        var table = $('#projectTable').DataTable({
            processing: true,
            serverSide: false, 
            ajax: {
                url: "{{ route('api.relationship.projects') }}",
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
            columns: [
                {
                    data: 'code',
                    render: function(data, type, row) {
                        return  `<span class="mx-3">#${data}</span>`
                    }
                },
                {
                    data: 'name',
                    render: function(data, type, row) {
                        let photoUrl = row.photo ? row.photo :
                            '/user.png';

                        return `
                            <span style="display: flex; align-items: center;" class="!text-dark">
                                ${data}                        
                            </span>
                        `;
                    }
                },
                {
                    data: 'stage',
                    className: "text-start"
                },
                {
                    data: 'id',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<div class="hstack gap-1 text-[.9375rem]">
                            <center>
                                <div class="hs-tooltip ti-main-tooltip [--placement:left]">
                                    <a href="/hbcs/clients/details/${row.encrypted_id || row.id}" target="_blank" 
                                        class="ti-btn ti-btn-sm bg-info/10">
                                        <i class="bi bi-eye text-info"></i>
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
            pageLength: 5, 
            initComplete: function() {
                $("#customSearchWrapper").html($("#projectTable_filter"));
                $("#customLengthWrapper").html($("#projectTable_length"));
            }
        });
    });
</script>

<style>
    #action_th_s{
        width: 80px !important;
        text-align: center !important;
    }

    #action_th_prj {
        width: 100px !important;
    }
</style>
