{{-- Jitsi Video/Audio Call Modal --}}
<div data-chat-modal="call" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90">
    <div class="relative w-full h-full flex flex-col">
        {{-- Call Header --}}
        <div class="absolute top-0 left-0 right-0 z-10 flex items-center justify-between px-4 py-3 bg-gradient-to-b from-black/80 to-transparent">
            <div class="flex items-center gap-3">
                <div id="call-status" class="flex items-center gap-2 text-white">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span id="call-status-text" class="text-sm font-medium">Connecting...</span>
                </div>
            </div>
            <button type="button" id="call-end-btn" class="flex items-center gap-2 px-4 py-2 rounded-full bg-red-600 hover:bg-red-700 text-white font-medium transition">
                <i class="bi bi-telephone-x"></i>
                <span>End Call</span>
            </button>
        </div>

        {{-- Jitsi Container --}}
        <div id="jitsi-container" class="flex-1 w-full h-full"></div>
    </div>
</div>

{{-- Incoming Call Modal --}}
<div data-chat-modal="incoming-call" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl overflow-hidden">
        {{-- Caller Info --}}
        <div class="p-6 text-center">
            <div class="relative inline-block mb-4">
                <img id="incoming-caller-avatar" src="" alt="Caller" class="w-24 h-24 rounded-full object-cover border-4 border-green-500 animate-pulse">
                <span class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                    <i class="bi bi-telephone-fill text-white text-sm"></i>
                </span>
            </div>
            <h3 id="incoming-caller-name" class="text-xl font-bold text-gray-900 dark:text-white mb-1">Someone</h3>
            <p id="incoming-call-type" class="text-sm text-gray-500 dark:text-white/60">
                <i class="bi bi-camera-video mr-1"></i> Incoming video call...
            </p>
        </div>

        {{-- Call Actions --}}
        <div class="flex border-t border-gray-200 dark:border-white/10">
            <button type="button" id="decline-call-btn" class="flex-1 flex items-center justify-center gap-2 py-4 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                <i class="bi bi-telephone-x text-xl"></i>
                <span class="font-medium">Decline</span>
            </button>
            <button type="button" id="accept-call-btn" class="flex-1 flex items-center justify-center gap-2 py-4 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 border-l border-gray-200 dark:border-white/10 transition">
                <i class="bi bi-telephone-fill text-xl"></i>
                <span class="font-medium">Accept</span>
            </button>
        </div>
    </div>
</div>

{{-- Jitsi External API --}}
<script src="https://{{ config('services.jitsi.domain', 'meet.hillbcs.com') }}/external_api.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const callModal = document.querySelector('[data-chat-modal="call"]');
    const incomingCallModal = document.querySelector('[data-chat-modal="incoming-call"]');
    const jitsiContainer = document.getElementById('jitsi-container');
    const callStatusText = document.getElementById('call-status-text');
    const callEndBtn = document.getElementById('call-end-btn');
    const acceptCallBtn = document.getElementById('accept-call-btn');
    const declineCallBtn = document.getElementById('decline-call-btn');

    let jitsiApi = null;
    let currentRoomName = null;
    let currentConversationId = null;
    let incomingCallData = null;
    let ringtone = null;

    // Initialize ringtone
    try {
        ringtone = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
        ringtone.loop = true;
    } catch (e) {
        console.log('Could not load ringtone');
    }

    const openModal = (name) => {
        const modal = document.querySelector(`[data-chat-modal="${name}"]`);
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    const closeModal = (name) => {
        const modal = document.querySelector(`[data-chat-modal="${name}"]`);
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    // Start a call
    window.startCall = async function(conversationId, callType = 'video') {
        if (!conversationId) return;

        // Show loading toast
        if (typeof showChatToast === 'function') {
            showChatToast('Starting call...', 'info', 3000);
        }

        try {
            const response = await fetch(`/chats/${conversationId}/call/start`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ type: callType }),
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || 'Failed to start call');
            }

            const data = await response.json();
            currentRoomName = data.room_name;
            currentConversationId = conversationId;

            // Render system message locally for the starter
            const userName = @json(auth()->user()->name);
            if (typeof renderCallSystemMessage === 'function') {
                renderCallSystemMessage(userName, callType, 'started');
            }

            // Open Jitsi in new window
            const jitsiDomain = data.jitsi_domain || 'meet.hillbcs.com';
            const user = @json(auth()->user());
            const displayName = encodeURIComponent(user?.name || 'Guest');
            const jitsiUrl = `https://${jitsiDomain}/${data.room_name}#userInfo.displayName="${displayName}"&config.startWithVideoMuted=${callType === 'audio'}&config.prejoinPageEnabled=false`;
            
            const width = 1000;
            const height = 700;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            const callWindow = window.open(jitsiUrl, 'jitsi_call', `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=no,status=no,toolbar=no,menubar=no,location=no`);
            
            // Track when window closes to end the call
            if (callWindow) {
                const checkWindowClosed = setInterval(() => {
                    if (callWindow.closed) {
                        clearInterval(checkWindowClosed);
                        endCallOnWindowClose(conversationId, data.room_name);
                    }
                }, 1000);
            }
            
            // Show success toast
            if (typeof showChatToast === 'function') {
                showChatToast('Call opened in new window', 'success');
            }
        } catch (error) {
            console.error('Start call error:', error);
            if (typeof showChatToast === 'function') {
                showChatToast(error.message || 'Could not start the call', 'error');
            }
        }
    };

    // Join an existing call (opens in new window)
    window.joinCall = async function(conversationId, roomName, callType = 'video') {
        try {
            const response = await fetch(`/chats/${conversationId}/call/join`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ room_name: roomName }),
            });

            if (!response.ok) throw new Error('Failed to join call');

            const data = await response.json();
            
            // Open Jitsi in new window
            const jitsiDomain = data.jitsi_domain || 'meet.hillbcs.com';
            const user = @json(auth()->user());
            const displayName = encodeURIComponent(user?.name || 'Guest');
            const jitsiUrl = `https://${jitsiDomain}/${roomName}#userInfo.displayName="${displayName}"&config.startWithVideoMuted=${callType === 'audio'}&config.prejoinPageEnabled=false`;
            
            const width = 1000;
            const height = 700;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            const callWindow = window.open(jitsiUrl, 'jitsi_call', `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=no,status=no,toolbar=no,menubar=no,location=no`);
            
            // Track when window closes to end the call
            if (callWindow) {
                const checkWindowClosed = setInterval(() => {
                    if (callWindow.closed) {
                        clearInterval(checkWindowClosed);
                        endCallOnWindowClose(conversationId, roomName);
                    }
                }, 1000);
            }
            
            if (typeof showChatToast === 'function') {
                showChatToast('Joined call in new window', 'success');
            }
        } catch (error) {
            console.error('Join call error:', error);
            if (typeof showChatToast === 'function') {
                showChatToast('Could not join the call', 'error');
            }
        }
    };

    // End call when popup window is closed
    async function endCallOnWindowClose(conversationId, roomName) {
        if (!conversationId || !roomName) return;
        
        try {
            const response = await fetch(`/chats/${conversationId}/call/end`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ room_name: roomName }),
            });
            
            const data = await response.json();
            if (data.ok && !data.call_ongoing && !data.already_ended) {
                if (typeof renderCallSystemMessage === 'function') {
                    renderCallSystemMessage(null, null, 'ended', data.duration_text || '');
                }
            }
        } catch (e) {
            console.error('End call on window close error:', e);
        }
    }

    // Initialize Jitsi Meet
    function initJitsi(domain, roomName, callType) {
        if (jitsiApi) {
            jitsiApi.dispose();
        }

        const user = @json(auth()->user());

        const options = {
            roomName: roomName,
            parentNode: jitsiContainer,
            width: '100%',
            height: '100%',
            configOverwrite: {
                startWithAudioMuted: false,
                startWithVideoMuted: callType === 'audio',
                prejoinPageEnabled: false,
                disableDeepLinking: true,
                enableWelcomePage: false,
                enableClosePage: false,
                disableInviteFunctions: true,
                // Disable Jitsi logging
                logging: {
                    defaultLogLevel: 'error',
                    disableLogCollector: true,
                },
                analytics: {
                    disabled: true,
                },
                toolbarButtons: [
                    'microphone',
                    'camera',
                    'desktop',
                    'fullscreen',
                    'chat',
                    'settings',
                    'videoquality',
                    'tileview',
                ],
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                SHOW_BRAND_WATERMARK: false,
                BRAND_WATERMARK_LINK: '',
                SHOW_POWERED_BY: false,
                TOOLBAR_ALWAYS_VISIBLE: true,
                DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,
                MOBILE_APP_PROMO: false,
            },
            userInfo: {
                displayName: user?.name || 'Guest',
                email: user?.email || '',
            },
        };

        jitsiApi = new JitsiMeetExternalAPI(domain, options);

        jitsiApi.addListener('videoConferenceJoined', () => {
            callStatusText.textContent = 'Connected';
        });

        jitsiApi.addListener('videoConferenceLeft', () => {
            endCall();
        });

        jitsiApi.addListener('participantJoined', (participant) => {
            callStatusText.textContent = `In call with ${participant.displayName || 'participant'}`;
        });

        jitsiApi.addListener('participantLeft', () => {
            callStatusText.textContent = 'Participant left';
        });
    }

    // End call
    async function endCall() {
        if (jitsiApi) {
            jitsiApi.dispose();
            jitsiApi = null;
        }

        if (currentConversationId && currentRoomName) {
            try {
                const response = await fetch(`/chats/${currentConversationId}/call/end`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ room_name: currentRoomName }),
                });
                
                const data = await response.json();
                // Only render system message if this was the last person (call actually ended)
                if (data.ok && !data.call_ongoing && !data.already_ended) {
                    if (typeof renderCallSystemMessage === 'function') {
                        renderCallSystemMessage(null, null, 'ended', data.duration_text || '');
                    }
                }
            } catch (e) {
                console.error('End call error:', e);
            }
        }

        currentRoomName = null;
        currentConversationId = null;
        closeModal('call');
    }

    // Handle incoming call
    window.handleIncomingCall = function(data) {
        incomingCallData = data;

        // Update UI
        document.getElementById('incoming-caller-avatar').src = data.caller_avatar;
        document.getElementById('incoming-caller-name').textContent = data.caller_name;
        document.getElementById('incoming-call-type').innerHTML = data.call_type === 'video' 
            ? '<i class="bi bi-camera-video mr-1"></i> Incoming video call...'
            : '<i class="bi bi-telephone mr-1"></i> Incoming audio call...';

        // Play ringtone
        ringtone?.play().catch(() => {});

        openModal('incoming-call');
    };

    // Dismiss incoming call (when call ends or is cancelled)
    window.dismissIncomingCall = function(roomName) {
        if (!incomingCallData) return;
        // Dismiss if room matches or no room specified (dismiss any)
        if (!roomName || incomingCallData.room_name === roomName) {
            ringtone?.pause();
            if (ringtone) ringtone.currentTime = 0;
            closeModal('incoming-call');
            incomingCallData = null;
        }
    };

    // Accept call
    acceptCallBtn?.addEventListener('click', () => {
        ringtone?.pause();
        ringtone.currentTime = 0;
        closeModal('incoming-call');

        if (incomingCallData) {
            joinCall(incomingCallData.conversation_id, incomingCallData.room_name, incomingCallData.call_type);
        }
        incomingCallData = null;
    });

    // Decline call
    declineCallBtn?.addEventListener('click', () => {
        ringtone?.pause();
        ringtone.currentTime = 0;
        closeModal('incoming-call');
        incomingCallData = null;
    });

    // End call button
    callEndBtn?.addEventListener('click', endCall);

    // Listen for call events via websocket
    if (typeof window.onEchoReady === 'function') {
        window.onEchoReady((Echo) => {
            // The call.started event is already listened in the conversation channel
            // This is handled in index.blade.php
        });
    }
});
</script>
