<div id="upload-file"
    class="hs-overlay hidden size-full rounded-md fixed top-0 start-0 overflow-x-hidden overflow-y-auto pointer-events-none ti-modal">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="ti-modal-content flex-grow">
            <div class="ti-modal-header">
                <h6 class="modal-title text-[1rem] font-semibold text-xl">
                    <strong>Upload File</strong>
                </h6>
                <button type="button" id="upload-file-close-btn" class="hs-dropdown-toggle ti-modal-close-btn" data-hs-overlay="#upload-file">
                    <span class="sr-only">Close</span>
                    <svg class="w-3.5 h-3.5" width="8" height="8" viewBox="0 0 8 8" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0.258206 1.00652C0.351976 0.912791 0.479126 0.860131 0.611706 0.860131C0.744296 0.860131 0.871447 0.912791 0.965207 1.00652L3.61171 3.65302L6.25822 1.00652C6.30432 0.958771 6.35952 0.920671 6.42052 0.894471C6.48152 0.868271 6.54712 0.854471 6.61352 0.853901C6.67992 0.853321 6.74572 0.865971 6.80722 0.891111C6.86862 0.916251 6.92442 0.953381 6.97142 1.00032C7.01832 1.04727 7.05552 1.1031 7.08062 1.16454C7.10572 1.22599 7.11842 1.29183 7.11782 1.35822C7.11722 1.42461 7.10342 1.49022 7.07722 1.55122C7.05102 1.61222 7.01292 1.6674 6.96522 1.71352L4.31871 4.36002L6.96522 7.00648C7.05632 7.10078 7.10672 7.22708 7.10552 7.35818C7.10442 7.48928 7.05182 7.61468 6.95912 7.70738C6.86642 7.80018 6.74102 7.85268 6.60992 7.85388C6.47882 7.85498 6.35252 7.80458 6.25822 7.71348L3.61171 5.06702L0.965207 7.71348C0.870907 7.80458 0.744606 7.85498 0.613506 7.85388C0.482406 7.85268 0.357007 7.80018 0.264297 7.70738C0.171597 7.61468 0.119017 7.48928 0.117877 7.35818C0.116737 7.22708 0.167126 7.10078 0.258206 7.00648L2.90471 4.36002L0.258206 1.71352C0.164476 1.61976 0.111816 1.4926 0.111816 1.36002C0.111816 1.22744 0.164476 1.10028 0.258206 1.00652Z"
                            fill="currentColor" />
                    </svg>
                </button>
            </div>
            <form id="upload-file-form" action="{{ route('drive.file.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="ti-modal-body space-y-4">
                    <div class="form-group">
                        <label class="form-label">Choose file (uploads in chunks for large files)</label>
                        <input type="file" name="file" id="upload-file-input" class="form-control form-control-lg" required accept="*/*">
                    </div>
                    <input type="hidden" name="parent_id" value="{{ request()->query('f') }}">

                    {{-- Progress bar (hidden until upload starts) --}}
                    <div id="upload-progress-wrap" class="hidden">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <i class="bi bi-cloud-upload mr-1"></i>
                                <span id="upload-progress-filename" class="truncate max-w-xs inline-block align-bottom"></span>
                            </span>
                            <span id="upload-progress-pct" class="text-sm font-semibold text-blue-600 dark:text-blue-400">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                            <div id="upload-progress-bar"
                                class="bg-blue-600 h-2.5 rounded-full transition-all duration-150"
                                style="width: 0%"></div>
                        </div>
                        <p id="upload-progress-status" class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Uploading…</p>
                    </div>

                    {{-- Error message area --}}
                    <div id="upload-error-wrap" class="hidden">
                        <p id="upload-error-msg" class="text-sm text-red-600 dark:text-red-400 flex items-start gap-1">
                            <i class="ri-error-warning-line mt-0.5 shrink-0"></i>
                            <span></span>
                        </p>
                    </div>
                </div>
                <div class="ti-modal-footer">
                    <button type="button" id="upload-cancel-btn"
                        class="hs-dropdown-toggle inline-flex gap-2 rounded-md border bg-white dark:bg-gray-800 border-slate-300 dark:border-gray-600 px-2 py-2 text-sm font-medium text-danger hover:bg-gray-100 dark:hover:bg-gray-700"
                        data-hs-overlay="#upload-file">
                        <span>Cancel</span>
                    </button>
                    <button type="submit" id="upload-submit-btn"
                        class="inline-flex gap-1 rounded-md border bg-blue-600 border-slate-300 px-2 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed">
                        <i class="bi bi-upload align-middle"></i>
                        <span id="upload-submit-label">Upload</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    const form        = document.getElementById('upload-file-form');
    const fileInput   = document.getElementById('upload-file-input');
    const submitBtn   = document.getElementById('upload-submit-btn');
    const submitLabel = document.getElementById('upload-submit-label');
    const cancelBtn   = document.getElementById('upload-cancel-btn');
    const closeBtn    = document.getElementById('upload-file-close-btn');

    const progressWrap = document.getElementById('upload-progress-wrap');
    const progressBar  = document.getElementById('upload-progress-bar');
    const progressPct  = document.getElementById('upload-progress-pct');
    const progressFile = document.getElementById('upload-progress-filename');
    const progressStatus = document.getElementById('upload-progress-status');

    const errorWrap  = document.getElementById('upload-error-wrap');
    const errorMsg   = document.getElementById('upload-error-msg').querySelector('span');

    const chunkSize = 5 * 1024 * 1024;
    const concurrency = 4;

    let activeControllers = [];
    let uploadCancelled = false;

    function resetModal() {
        progressWrap.classList.add('hidden');
        errorWrap.classList.add('hidden');
        progressBar.style.width = '0%';
        progressPct.textContent = '0%';
        progressStatus.textContent = 'Uploading…';
        progressStatus.classList.remove('text-green-600', 'dark:text-green-400', 'text-red-600', 'dark:text-red-400');
        progressStatus.classList.add('text-gray-500', 'dark:text-gray-400');
        submitBtn.disabled = false;
        submitLabel.textContent = 'Upload';
        fileInput.value = '';
        activeControllers = [];
    }

    function cancelUpload() {
        uploadCancelled = true;
        activeControllers.forEach((controller) => controller.abort());
        activeControllers = [];
        resetModal();
    }

    function showError(message) {
        progressWrap.classList.add('hidden');
        errorWrap.classList.remove('hidden');
        errorMsg.textContent = message;
        submitBtn.disabled = false;
        submitLabel.textContent = 'Upload';
    }

    function getUploadId() {
        if (window.crypto && typeof window.crypto.randomUUID === 'function') {
            return window.crypto.randomUUID();
        }

        return 'upload-' + Date.now() + '-' + Math.random().toString(36).slice(2);
    }

    function setProgress(uploadedBytes, totalBytes, statusText) {
        const pct = totalBytes > 0 ? Math.min(100, Math.round((uploadedBytes / totalBytes) * 100)) : 0;

        progressWrap.classList.remove('hidden');
        progressBar.style.width = pct + '%';
        progressPct.textContent = pct + '%';
        if (statusText) {
            progressStatus.textContent = statusText;
        }
    }

    async function postJson(formData, controller) {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData,
            signal: controller.signal,
        });

        let payload = {};
        try {
            payload = await response.json();
        } catch (error) {
            payload = {};
        }

        if (!response.ok || payload.success === false) {
            throw new Error(payload.message || payload.error || ('Upload failed (HTTP ' + response.status + '). Please try again.'));
        }

        return payload;
    }

    async function uploadChunk(file, uploadId, chunkIndex, totalChunks, parentId, totalBytes) {
        const start = chunkIndex * chunkSize;
        const end = Math.min(start + chunkSize, file.size);
        const chunk = file.slice(start, end);

        const controller = new AbortController();
        activeControllers.push(controller);

        const formData = new FormData();
        formData.append('file', chunk, file.name);
        formData.append('upload_id', uploadId);
        formData.append('chunk_index', String(chunkIndex));
        formData.append('total_chunks', String(totalChunks));
        formData.append('file_name', file.name);
        formData.append('file_size', String(totalBytes));
        formData.append('file_mime', file.type || 'application/octet-stream');
        if (parentId) {
            formData.append('parent_id', parentId);
        }

        try {
            return await postJson(formData, controller);
        } finally {
            activeControllers = activeControllers.filter((item) => item !== controller);
        }
    }

    async function finalizeUpload(uploadId) {
        const controller = new AbortController();
        activeControllers.push(controller);

        const formData = new FormData();
        formData.append('upload_action', 'finalize');
        formData.append('upload_id', uploadId);

        try {
            return await postJson(formData, controller);
        } finally {
            activeControllers = activeControllers.filter((item) => item !== controller);
        }
    }

    async function uploadFileInChunks(file) {
        const parentId = new FormData(form).get('parent_id');
        const totalChunks = Math.max(1, Math.ceil(file.size / chunkSize));
        const uploadId = getUploadId();
        const totalBytes = file.size;
        let uploadedBytes = 0;
        let nextChunk = 0;

        async function worker() {
            while (true) {
                const chunkIndex = nextChunk;
                nextChunk += 1;

                if (chunkIndex >= totalChunks) {
                    return;
                }

                await uploadChunk(file, uploadId, chunkIndex, totalChunks, parentId, totalBytes);
                const start = chunkIndex * chunkSize;
                const end = Math.min(start + chunkSize, totalBytes);
                uploadedBytes += (end - start);
                setProgress(uploadedBytes, totalBytes, 'Uploading chunk ' + (chunkIndex + 1) + ' of ' + totalChunks + '…');
            }
        }

        const workerCount = Math.min(concurrency, totalChunks);
        const workers = [];
        for (let i = 0; i < workerCount; i += 1) {
            workers.push(worker());
        }

        await Promise.all(workers);

        setProgress(totalBytes, totalBytes, 'Finalizing upload…');
        const payload = await finalizeUpload(uploadId);
        progressStatus.classList.remove('text-gray-500', 'dark:text-gray-400');
        progressStatus.classList.add('text-green-600', 'dark:text-green-400');
        progressStatus.textContent = 'Upload complete! Refreshing…';
        progressBar.style.width = '100%';
        progressPct.textContent = '100%';

        return payload;
    }

    // Reset state whenever the modal is closed
    [cancelBtn, closeBtn].forEach(btn => {
        btn.addEventListener('click', function () {
            cancelUpload();
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const file = fileInput.files[0];
        if (!file) { return; }

        errorWrap.classList.add('hidden');
        progressWrap.classList.remove('hidden');
        progressFile.textContent = file.name;
        progressBar.style.width = '0%';
        progressPct.textContent = '0%';
        progressStatus.textContent = 'Preparing chunked upload…';
        submitBtn.disabled = true;
        submitLabel.textContent = 'Uploading…';
        uploadCancelled = false;

        uploadFileInChunks(file)
            .then((payload) => {
                if (uploadCancelled) {
                    return;
                }

                let redirectUrl = payload.redirect || window.location.href;
                progressBar.style.width = '100%';
                progressPct.textContent = '100%';
                progressStatus.classList.remove('text-gray-500', 'dark:text-gray-400');
                progressStatus.classList.add('text-green-600', 'dark:text-green-400');
                progressStatus.textContent = 'Upload complete! Refreshing…';
                setTimeout(function () { window.location.href = redirectUrl; }, 600);
            })
            .catch((error) => {
                if (uploadCancelled || error.name === 'AbortError') {
                    return;
                }

                showError(error.message || 'A network error occurred. Please check your connection and try again.');
            });
    });
})();
</script>
