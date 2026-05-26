class Toast {
    constructor() {
        this.createToastElement();
    }

    createToastElement() {
        if (document.getElementById('toast')) {
            return;
        }

        const toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 z-50 hidden';
        
        toast.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg id="toast-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p id="toast-message" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                </div>
            </div>
        `;
        
        document.body.appendChild(toast);
    }

    show(message, type = 'success', duration = 3000) {
        this.createToastElement();
        
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');
        
        if (!toast || !toastMessage || !toastIcon) {
            const fallbackToast = document.createElement('div');
            fallbackToast.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
                color: white;
                padding: 16px 24px;
                border-radius: 8px;
                z-index: 9999;
                font-weight: 500;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            `;
            fallbackToast.textContent = message;
            document.body.appendChild(fallbackToast);
            
            setTimeout(() => {
                if (fallbackToast.parentNode) {
                    fallbackToast.parentNode.removeChild(fallbackToast);
                }
            }, duration);
            return;
        }

        toastMessage.textContent = message;
        
        if (type === 'success') {
            toastIcon.setAttribute('class', 'w-5 h-5 text-green-500');
            toastIcon.innerHTML = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>';
        } else if (type === 'error') {
            toastIcon.setAttribute('class', 'w-5 h-5 text-red-500');
            toastIcon.innerHTML = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>';
        }
        
        toast.classList.remove('hidden');
        
        setTimeout(() => {
            toast.classList.add('hidden');
        }, duration);
    }

    hide() {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.classList.add('hidden');
        }
    }
}

if (typeof module !== 'undefined' && module.exports) {
    module.exports = Toast;
}
