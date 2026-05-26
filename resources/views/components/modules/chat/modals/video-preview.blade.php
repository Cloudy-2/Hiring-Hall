{{-- Video Preview Modal --}}
<div id="video-preview-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/90 backdrop-blur-sm">
    <div class="relative w-full max-w-4xl mx-4">
        <button type="button" id="video-preview-close" class="absolute -top-12 right-0 p-2 text-white/70 hover:text-white transition">
            <i class="bi bi-x-lg text-2xl"></i>
        </button>
        
        <div id="video-modal-player-container" class="rounded-xl overflow-hidden bg-black">
            <video id="video-preview-player" class="w-full" controls playsinline>
                <source id="video-preview-source" src="" type="">
            </video>
        </div>
        
        <div id="video-preview-unsupported" class="hidden text-center py-12">
            <i class="bi bi-exclamation-triangle text-5xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-bold text-white mb-2">Format Not Supported</h3>
            <p class="text-white/60 mb-4">Your browser cannot play this video format directly.</p>
            <a id="video-preview-download" href="#" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-purple-600 text-white font-semibold hover:bg-purple-700 transition">
                <i class="bi bi-download"></i> Download Video
            </a>
        </div>
        
        <div class="flex items-center justify-between mt-3 px-1">
            <div class="flex items-center gap-2 text-sm text-white/60">
                <i class="bi bi-camera-video-fill text-purple-400"></i>
                <span id="video-preview-filename" class="truncate max-w-xs"></span>
                <span class="text-white/40">·</span>
                <span id="video-preview-size"></span>
            </div>
            <a id="video-preview-download-btn" href="#" class="text-sm text-purple-400 hover:text-purple-300 transition">
                <i class="bi bi-download mr-1"></i> Download
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('video-preview-modal');
    const playerContainer = document.getElementById('video-modal-player-container');
    const unsupportedEl = document.getElementById('video-preview-unsupported');
    const player = document.getElementById('video-preview-player');
    const source = document.getElementById('video-preview-source');
    const closeBtn = document.getElementById('video-preview-close');
    const filenameEl = document.getElementById('video-preview-filename');
    const sizeEl = document.getElementById('video-preview-size');
    const downloadBtn = document.getElementById('video-preview-download-btn');
    const downloadLink = document.getElementById('video-preview-download');

    const supportedFormats = ['video/mp4', 'video/webm', 'video/ogg'];

    window.openVideoPreview = function(url, downloadUrl, filename, size, mime) {
        const isSupported = supportedFormats.includes(mime) || canPlayType(mime);
        
        filenameEl.textContent = filename || 'Video';
        sizeEl.textContent = size || '';
        downloadBtn.href = downloadUrl || url;
        downloadLink.href = downloadUrl || url;

        if (isSupported) {
            playerContainer.classList.remove('hidden');
            unsupportedEl.classList.add('hidden');
            source.src = url;
            source.type = mime || 'video/mp4';
            player.load();
            player.play().catch(() => {});
        } else {
            playerContainer.classList.add('hidden');
            unsupportedEl.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    };

    function canPlayType(mime) {
        const video = document.createElement('video');
        return video.canPlayType(mime) !== '';
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        player.pause();
        source.src = '';
    }

    closeBtn?.addEventListener('click', closeModal);
    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal?.classList.contains('flex')) closeModal();
    });
});
</script>
