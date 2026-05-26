<div class="table-controls flex justify-between items-center mb-3">
    <div class="flex items-center gap-3">
        <select id="bulkAction" class="border rounded px-3 py-2">
            <option value="" disabled selected>Action</option>
            <option value="delete">
                Move to Trash
            </option>
            <option value="export">
                Export to CSV
            </option>
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
