{{-- Main Chat Area Component --}}
<div class="flex-1 h-full min-h-0 grid grid-rows-[auto,1fr,auto] border rounded-lg bg-white shadow-sm overflow-hidden">
    {{-- Header --}}
    <div id="chat-header"
        class="px-4 py-3 border-b flex items-center justify-between bg-gradient-to-r from-white to-blue-50/60">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-500"
                id="chat-avatar">
                <i class="bi bi-chat-left-text"></i>
            </div>
            <div>
                <div id="chat-title" class="font-semibold text-sm text-gray-800">
                    Select a conversation
                </div>
                <div id="chat-subtitle" class="text-xs text-gray-500 flex items-center gap-1">
                    <span class="inline-flex h-1.5 w-1.5 rounded-full bg-gray-300"></span>
                    No conversation opened
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2 text-gray-400">
            <button type="button" id="btn-video-call" data-name="{{ auth()->user()->name ?? 'Guest' }}"
                data-email="{{ auth()->user()->email ?? '' }}"
                data-avatar="{{ method_exists(auth()->user(), 'profile_photo_url') ? auth()->user()->profile_photo_url : '' }}"
                class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-white/70 transition"
                title="Start video meeting">
                <i class="bi bi-camera-video"></i>
            </button>

            <button type="button" id="chat-info-btn"
                class="h-8 w-8 flex items-center justify-center rounded-full hover:bg-white/70 transition"
                title="Conversation details">
                <i class="bi bi-info-circle"></i>
            </button>
        </div>
    </div>

    {{-- Messages + background --}}
    <div class="relative bg-slate-50/80 min-h-0">
        {{-- Background --}}
        <div class="absolute inset-0 chat-bg pointer-events-none opacity-70"></div>

        <div id="messages-container"
            class="relative z-10 h-full overflow-y-auto px-4 py-3 space-y-2 text-sm flex flex-col">
            <div id="empty-state" class="m-auto text-center text-xs text-gray-500">
                <div
                    class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 shadow-sm text-blue-500 mb-2">
                    <i class="bi bi-chat-square-dots text-xl"></i>
                </div>
                <p class="font-medium text-gray-700 mb-1">No conversation selected</p>
                <p>Choose a conversation from the left to start chatting.</p>
            </div>
        </div>

        <!-- New messages indicator -->
        <button id="new-messages-indicator"
            class="hidden absolute bottom-3 left-1/2 -translate-x-1/2 px-3 py-1.5 rounded-full bg-blue-600 text-white text-[11px] shadow-lg flex items-center gap-1 z-20">
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-white"></span>
            New messages
            <span class="text-xs">↓</span>
        </button>
    </div>

    {{-- Input + attachments + rich text --}}
    <form id="chat-form"
        class="border-t px-3 py-2 flex flex-col gap-2 bg-white/95 backdrop-blur-sm opacity-60 pointer-events-none">
        @csrf
        <input type="hidden" id="active-conversation-id" name="conversation_id" value="">

        {{-- Attachments preview --}}
        <div id="pending-preview" class="flex flex-wrap gap-2 mb-1 hidden"></div>

        <div class="flex items-end gap-2">
            {{-- Left tools --}}
            <div class="flex items-center gap-1">
                <button type="button" id="btn-attach"
                    class="p-2 rounded-full hover:bg-gray-100 text-gray-600" title="Attach files">
                    <i class="bi bi-paperclip"></i>
                </button>
                <button type="button" id="btn-image"
                    class="p-2 rounded-full hover:bg-gray-100 text-gray-600" title="Attach image">
                    <i class="bi bi-image"></i>
                </button>
                <div class="relative">
                    <button type="button" id="btn-emoji"
                        class="p-2 rounded-full hover:bg-gray-100 text-gray-600" title="Insert emoji">
                        <i class="bi bi-emoji-smile"></i>
                    </button>
                    {{-- Emoji picker using emoji-picker-element --}}
                    <div id="emoji-picker-wrapper"
                        class="hidden absolute bottom-10 left-0 z-30 shadow-xl rounded-lg overflow-hidden">
                        <emoji-picker></emoji-picker>
                    </div>
                </div>

                {{-- Hidden file inputs --}}
                <input id="file-input" type="file" multiple class="hidden">
                <input id="img-input" type="file" accept="image/*" multiple class="hidden">
            </div>

            {{-- Rich text editor --}}
            <div
                class="flex-1 max-h-40 overflow-y-auto border border-gray-200 rounded-2xl px-3 py-2 bg-gray-50 text-sm">
                <div id="chat-editor" contenteditable="true" role="textbox" aria-multiline="true"
                    class="outline-none whitespace-pre-wrap break-words"
                    data-placeholder="Type a message… (Shift+Enter for new line)"></div>
            </div>

            {{-- Send --}}
            <button type="button" id="btn-send" data-chat-send
                class="inline-flex items-center justify-center rounded-full bg-blue-600 text-white text-sm px-3 py-2 hover:bg-blue-700 focus:ring-2 focus:ring-blue-400 shadow-sm">
                <i class="bi bi-send-fill mr-1 text-xs"></i>
                Send
            </button>
        </div>
    </form>
</div>
