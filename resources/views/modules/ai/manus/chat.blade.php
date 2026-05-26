<x-app-layout>

    <x-slot name="pageTitle">Chat with AI</x-slot>
    <x-slot name="return">{"link": "/users/manage", "text": "back"}</x-slot>
    <x-slot name="url_1">{"link": "/developer/routes", "text": "AI-Powered"}</x-slot>
    <x-slot name="active">Chat</x-slot>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .box-body {
            padding: 0px !important;
        }

        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --bubble: #fff;
            --mine: #dcf8c6
        }

        /*
        * {
            box-sizing: border-box
        }

        body {
            font-family: 'Poppins', system-ui, Segoe UI, Roboto, sans-serif
        } */


        .chat-header {
            padding: 14px 18px;
            border-bottom: 1px solid #eef2f7;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fafafa
        }

        .ai-avatar,
        .me-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            color: #fff
        }

        .ai-avatar {
            background: #111827
        }

        .me-avatar {
            background: #2563eb
        }

        .title {
            font-weight: 600;
            color: var(--ink)
        }

        .sub {
            font-size: 12px;
            color: var(--muted)
        }

        .chat-body {
            position: relative;
            height: 64vh;
            overflow: auto;
            background: #e7f0ff
        }

        .chat-body::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: .2;
            background-image: radial-gradient(#94a3b8 1px, transparent 1px);
            background-size: 18px 18px
        }

        .messages {
            position: relative;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 10px
        }

        .msg-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        /* Side alignment */
        .msg-row.ai {
            justify-content: flex-start;
        }

        .msg-row.me {
            justify-content: flex-end;
        }

        /* Bubble tweaks by side */
        .msg-row.ai .bubble {
            border-bottom-left-radius: 6px;
        }

        .msg-row.me .bubble {
            background: var(--mine);
            border-bottom-right-radius: 6px;
        }

        .bubble {
            max-width: 70%;
            padding: 10px 12px;
            border-radius: 14px;
            background: var(--bubble);
            color: var(--ink);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05)
        }

        .chat-footer {
            border-top: 1px solid #eef2f7;
            background: #fff;
            padding: 10px;
            display: flex;
            align-items: flex-end;
            gap: 10px;
            width: 100%;
        }

        .input-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .chat-footer textarea {
            width: 100%;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px;
            resize: none;
            height: 48px;
            font: inherit;
            font-size: 14px;
            line-height: 1.4;
            transition: border 0.2s;
        }

        .chat-footer textarea:focus {
            border-color: #2563eb;
            outline: none;
        }

        .char-count {
            font-size: 12px;
            color: #64748b;
            text-align: right;
            margin-top: 4px;
            padding-right: 4px;
        }

        .char-count.over {
            color: #dc2626;
            /* red if over limit */
        }

        .chat-footer .btn {
            background: #111827;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0 16px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            transition: background 0.2s ease;
            height: 48px;
        }

        .chat-footer .btn:hover {
            background: #1f2937;
        }



        .field {
            flex: 1;
            display: flex;
            gap: 8px;
            align-items: center;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 8px 10px
        }

        .field textarea {
            flex: 1;
            border: 0;
            outline: 0;
            resize: none;
            height: 46px;
            padding: 6px;
            font: inherit;
            font-size: 14px
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #111827;
            color: #fff;
            border: 1px solid #111827;
            border-radius: 12px;
            padding: 10px 14px;
            cursor: pointer
        }

        .btn:disabled {
            opacity: .6;
            cursor: not-allowed
        }

        .typing {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #475569;
            font-size: 13px
        }

        .dots>span {
            display: inline-block;
            width: 6px;
            height: 6px;
            background: #475569;
            border-radius: 50%;
            animation: jump 1.2s infinite
        }

        .dots>span:nth-child(2) {
            animation-delay: .15s
        }

        .dots>span:nth-child(3) {
            animation-delay: .3s
        }

        @keyframes jump {

            0%,
            60%,
            100% {
                transform: translateY(0)
            }

            30% {
                transform: translateY(-3px)
            }
        }
    </style>

    <div class="chat-wrapx">
        <div class="chat-header">
            <div class="ai-avatar"><i class="bi bi-robot"></i></div>
            <div class="title">Manus AI Chat</div>
        </div>

        <div class="chat-body">
            <div id="messages" class="messages"></div>
        </div>

        <div class="chat-footer">
            @csrf
            <div class="input-wrap">
                <div class="char-count mb-2 justify-left" id="charCount">0 / 8000</div>
                <textarea id="input" placeholder="Type your message..." maxlength="8000"></textarea>
            </div>
            <button id="send" class="btn"><i class="bi bi-send-fill"></i></button>
        </div>
    </div>


    <script>
        const elInput = document.getElementById('input');
        const elCount = document.getElementById('charCount');
        const elSend = document.getElementById('send');

        const MAX_CHARS = 8000;

        elInput.addEventListener('input', () => {
            const len = elInput.value.length;
            elCount.textContent = `${len} / ${MAX_CHARS}`;
            if (len > MAX_CHARS) {
                elCount.classList.add('over');
                elSend.disabled = true;
            } else {
                elCount.classList.remove('over');
                elSend.disabled = len === 0; // disable if empty
            }
        });

        (function() {
            localStorage.clear();
            const elMsgs = document.getElementById('messages');
            const elInput = document.getElementById('input');
            const elSend = document.getElementById('send');
            const LS_KEY = 'manus_chat_history_v1';
            let history = loadHistory();

            if (history.length === 0) pushAI("Hi! I’m Manus AI. How can I help?");
            else history.forEach(m => m.role === 'user' ? pushMe(m.content, false) : pushAI(m.content, false));

            function loadHistory() {
                try {
                    return JSON.parse(localStorage.getItem(LS_KEY) || '[]')
                } catch {
                    return []
                }
            }

            function saveHistory() {
                localStorage.setItem(LS_KEY, JSON.stringify(history.slice(-60)))
            }

            function bubble(html, side = 'ai') {
                const row = document.createElement('div');
                row.className = 'msg-row ' + (side === 'me' ? 'me' : 'ai'); // ← updated
                const avatar = document.createElement('div');
                avatar.className = side === 'me' ? 'me-avatar' : 'ai-avatar';
                avatar.innerHTML = side === 'me' ? '<i class="bi bi-person"></i>' : '<i class="bi bi-robot"></i>';
                const bub = document.createElement('div');
                bub.className = 'bubble';
                bub.innerHTML = html;

                if (side === 'me') {
                    row.appendChild(bub);
                    row.appendChild(avatar);
                } else {
                    row.appendChild(avatar);
                    row.appendChild(bub);
                }
                elMsgs.appendChild(row);
                elMsgs.parentElement.scrollTop = elMsgs.parentElement.scrollHeight;
                return bub;
            }


            function escapeHTML(s) {
                return s.replace(/[&<>"]/g, c => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;'
                } [c]))
            }

            function mdLite(t) {
                t = escapeHTML(t);
                t = t.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
                t = t.replace(/\*(.+?)\*/g, '<em>$1</em>');
                t = t.replace(/^- (.+)$/gm, '• $1');
                t = t.replace(/\n/g, '<br>');
                return t;
            }

            function pushMe(text, store = true) {
                bubble(mdLite(text), 'me');
                if (store) {
                    history.push({
                        role: 'user',
                        content: text
                    });
                    saveHistory();
                }
            }
            async function pushAI(text, store = true) {
                const wrap = bubble(
                    '<div class="typing"><span>Thinking…</span><span class="dots"><span></span><span></span><span></span></span></div>',
                    'ai');
                await new Promise(r => setTimeout(r, 2000));
                const target = document.createElement('div');
                wrap.innerHTML = '';
                wrap.appendChild(target);
                const out = mdLite(text);
                await typewriter(target, out, 10);
                if (store) {
                    history.push({
                        role: 'assistant',
                        content: text
                    });
                    saveHistory();
                }
            }

            function typewriter(el, html, speed) {
                return new Promise(res => {
                    let i = 0;
                    const tmp = document.createElement('div');
                    tmp.innerHTML = html;
                    const text = tmp.textContent || tmp.innerText || '';
                    const iv = setInterval(() => {
                        el.textContent = text.slice(0, i += 3);
                        if (i >= text.length) {
                            clearInterval(iv);
                            el.innerHTML = html;
                            res();
                        }
                    }, speed);
                });
            }

            elSend.addEventListener('click', send);
            elInput.addEventListener('keydown', e => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    send();
                }
            });

            async function send() {
                const text = elInput.value.trim();
                if (!text) return;
                elSend.disabled = true;
                pushMe(text);
                elInput.value = '';
                const fd = new FormData();
                fd.append('_token', "{{ csrf_token() }}");
                fd.append('message', text);
                const recent = history.slice(-20);
                recent.forEach((m, i) => {
                    fd.append(`history[${i}][role]`, m.role);
                    fd.append(`history[${i}][content]`, m.content);
                });
                try {
                    const res = await fetch("{{ route('manus.chat.send') }}", {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: fd
                    });
                    let j;
                    try {
                        j = await res.json();
                    } catch (_) {
                        const txt = await res.text();
                        throw new Error(`HTTP ${res.status}: ${txt}`);
                    }
                    if (!j.ok) throw new Error(j.detail || 'Request failed');
                    await pushAI(j.answer || '(no content)');
                } catch (err) {
                    await pushAI('Error: ' + err.message);
                } finally {
                    elSend.disabled = false;
                }
            }
        })();
    </script>

</x-app-layout>
