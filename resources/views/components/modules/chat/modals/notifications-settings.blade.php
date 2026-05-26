{{-- Notifications Settings Modal --}}
<div data-chat-modal="notifications-settings" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center gap-2">
                <i class="bi bi-bell-fill text-xl text-primary"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Notifications</h2>
            </div>
            <button type="button" data-chat-modal-close="notifications-settings" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-5 space-y-4">
            {{-- Mute conversation --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Mute Conversation</p>
                    <p class="text-xs text-gray-500 dark:text-white/50">Stop receiving notifications</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="mute-conversation" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                </label>
            </div>

            {{-- Sound --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Sound</p>
                    <p class="text-xs text-gray-500 dark:text-white/50">Play sound for new messages</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="notification-sound" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                </label>
            </div>

            {{-- Desktop notifications --}}
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Desktop Notifications</p>
                    <p class="text-xs text-gray-500 dark:text-white/50">Show browser notifications</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="desktop-notifications" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                </label>
            </div>

            {{-- Mute duration --}}
            <div class="pt-2 border-t border-gray-200 dark:border-white/10">
                <p class="text-sm font-medium text-gray-900 dark:text-white mb-3">Mute Duration</p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" class="mute-duration-btn px-3 py-2 rounded-lg text-sm font-medium border border-gray-200 dark:border-white/10 text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/10 transition" data-duration="1h">1 Hour</button>
                    <button type="button" class="mute-duration-btn px-3 py-2 rounded-lg text-sm font-medium border border-gray-200 dark:border-white/10 text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/10 transition" data-duration="8h">8 Hours</button>
                    <button type="button" class="mute-duration-btn px-3 py-2 rounded-lg text-sm font-medium border border-gray-200 dark:border-white/10 text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/10 transition" data-duration="24h">24 Hours</button>
                    <button type="button" class="mute-duration-btn px-3 py-2 rounded-lg text-sm font-medium border border-gray-200 dark:border-white/10 text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/10 transition" data-duration="forever">Forever</button>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-gray-200 dark:border-white/10 px-5 py-4">
            <button type="button" data-chat-modal-close="notifications-settings" class="w-full py-2.5 rounded-xl bg-primary text-white font-medium hover:bg-primary/90 transition">
                Done
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const STORAGE_KEY = 'chat_notification_settings';
    
    // Load settings from localStorage
    const settings = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
    
    const muteCheckbox = document.getElementById('mute-conversation');
    const soundCheckbox = document.getElementById('notification-sound');
    const desktopCheckbox = document.getElementById('desktop-notifications');
    
    // Apply saved settings
    if (muteCheckbox) muteCheckbox.checked = settings.muted || false;
    if (soundCheckbox) soundCheckbox.checked = settings.sound !== false;
    if (desktopCheckbox) desktopCheckbox.checked = settings.desktop || false;
    
    // Save settings on change
    [muteCheckbox, soundCheckbox, desktopCheckbox].forEach(checkbox => {
        checkbox?.addEventListener('change', () => {
            const newSettings = {
                muted: muteCheckbox?.checked || false,
                sound: soundCheckbox?.checked || false,
                desktop: desktopCheckbox?.checked || false,
            };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(newSettings));
        });
    });
    
    // Request desktop notification permission
    desktopCheckbox?.addEventListener('change', async () => {
        if (desktopCheckbox.checked && 'Notification' in window) {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') {
                desktopCheckbox.checked = false;
                window.Swal?.fire({
                    icon: 'warning',
                    title: 'Permission Denied',
                    text: 'Please enable notifications in your browser settings.',
                });
            }
        }
    });
    
    // Mute duration buttons
    document.querySelectorAll('.mute-duration-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const duration = btn.dataset.duration;
            if (muteCheckbox) muteCheckbox.checked = true;
            
            const settings = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
            settings.muted = true;
            settings.muteDuration = duration;
            settings.muteUntil = duration === 'forever' ? null : Date.now() + parseDuration(duration);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(settings));
            
            window.Swal?.fire({
                icon: 'success',
                title: 'Muted',
                text: `Notifications muted for ${duration === 'forever' ? 'forever' : duration}`,
                timer: 1500,
                showConfirmButton: false,
            });
        });
    });
    
    function parseDuration(str) {
        const num = parseInt(str);
        if (str.includes('h')) return num * 60 * 60 * 1000;
        return 0;
    }
});
</script>
