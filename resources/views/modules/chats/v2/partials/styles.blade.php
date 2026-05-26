{{-- Chat V2 Styles --}}
{{-- Hot Toast CDN --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/react-hot-toast@2/dist/react-hot-toast.min.css">
<script src="https://cdn.jsdelivr.net/npm/react-hot-toast@2/dist/react-hot-toast.min.js" defer></script>

<style>
    /* Force action bar to stay visible when dropdown is open */
    .dropdown-open-actionbar {
        display: flex !important;
    }

    /* Custom toast styles - White (success/info) and Red (error/warning) only */
    .chat-toast {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        pointer-events: none;
    }
    .chat-toast-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        background: white;
        color: #374151;
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        pointer-events: auto;
        animation: toastSlideIn 0.3s ease-out;
        max-width: 360px;
        border: 1px solid #e5e7eb;
    }
    .chat-toast-item .chat-toast-icon {
        color: #374151;
    }
    .chat-toast-item.error,
    .chat-toast-item.warning {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }
    .chat-toast-item.error .chat-toast-icon,
    .chat-toast-item.warning .chat-toast-icon {
        color: #ef4444;
    }
    .chat-toast-icon {
        font-size: 20px;
        flex-shrink: 0;
    }
    @keyframes toastSlideIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes toastSlideOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-20px); }
    }

    /* Link styling - blue color */
    .chat-message-body a,
    .chat-message-body a.chat-link,
    a.chat-link,
    .chat-link { 
        color: #2563eb !important; 
        text-decoration: underline !important;
    }
    .chat-message-body a:hover,
    .chat-message-body a.chat-link:hover,
    a.chat-link:hover,
    .chat-link:hover { 
        color: #1d4ed8 !important; 
    }
    .dark .chat-message-body a,
    .dark .chat-message-body a.chat-link,
    .dark a.chat-link,
    .dark .chat-link {
        color: #60a5fa !important;
    }
    .dark .chat-message-body a:hover,
    .dark .chat-message-body a.chat-link:hover,
    .dark a.chat-link:hover,
    .dark .chat-link:hover {
        color: #93c5fd !important;
    }

    /* Message animation */
    @keyframes messageEnter {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .message-enter {
        animation: messageEnter 0.2s ease-out;
    }

    /* Own message name - blue color */
    .own-message-name,
    [data-is-own="1"] .font-bold:first-child,
    p.font-bold[style*="color: #2563eb"] {
        color: #2563eb !important;
    }

    /* Rich text content styles */
    .rich-text-content {
        white-space: normal;
    }
    .rich-text-content p {
        margin-bottom: 0.5em;
    }
    .rich-text-content p:last-child {
        margin-bottom: 0;
    }
    .rich-text-content strong, .rich-text-content b {
        font-weight: 700;
    }
    .rich-text-content em, .rich-text-content i {
        font-style: italic;
    }
    .rich-text-content u {
        text-decoration: underline;
    }
    .rich-text-content s, .rich-text-content strike {
        text-decoration: line-through;
    }
    .rich-text-content ul, .rich-text-content ol {
        margin: 0.5em 0;
        padding-left: 1.5em;
    }
    .rich-text-content ul {
        list-style-type: disc;
    }
    .rich-text-content ol {
        list-style-type: decimal;
    }
    .rich-text-content li {
        margin-bottom: 0.25em;
    }
    .rich-text-content blockquote {
        border-left: 3px solid #6366f1;
        padding-left: 1em;
        margin: 0.5em 0;
        color: #6b7280;
    }
    .dark .rich-text-content blockquote {
        color: rgba(255,255,255,0.6);
        border-left-color: #818cf8;
    }
    .rich-text-content code {
        background: #f3f4f6;
        padding: 0.125em 0.375em;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.9em;
    }
    .dark .rich-text-content code {
        background: rgba(255,255,255,0.1);
    }
    .rich-text-content pre {
        background: #1f2937;
        color: #e5e7eb;
        padding: 1em;
        border-radius: 8px;
        overflow-x: auto;
        margin: 0.5em 0;
        font-family: monospace;
        font-size: 0.9em;
    }
    .rich-text-content h1, .rich-text-content h2, .rich-text-content h3,
    .rich-text-content h4, .rich-text-content h5, .rich-text-content h6 {
        font-weight: 600;
        margin: 0.5em 0 0.25em;
    }
    .rich-text-content h1 { font-size: 1.5em; }
    .rich-text-content h2 { font-size: 1.3em; }
    .rich-text-content h3 { font-size: 1.15em; }
    
    /* Inline GIF/Sticker in messages */
    .rich-text-content img,
    .chat-message-body img {
        max-width: 200px;
        max-height: 150px;
        border-radius: 8px;
        display: block;
        margin: 8px 0;
        cursor: pointer;
    }
    .chat-message-body img:hover {
        opacity: 0.9;
    }
</style>
