{{-- Spin the Wheel Modal --}}
<div data-chat-modal="spin-wheel" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-[#222222] shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-lg bg-gradient-to-br from-red-500 via-yellow-500 to-green-500 flex items-center justify-center">
                    <i class="bi bi-arrow-repeat text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Spin the Wheel</h2>
                    <p class="text-xs text-gray-500 dark:text-white/50">Random picker for decisions</p>
                </div>
            </div>
            <button type="button" data-chat-modal-close="spin-wheel" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-5">
            {{-- Wheel Canvas --}}
            <div class="relative flex justify-center mb-4">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1 z-10">
                    <div class="w-0 h-0 border-l-[14px] border-r-[14px] border-t-[24px] border-l-transparent border-r-transparent border-t-primary drop-shadow-lg"></div>
                </div>
                <canvas id="spin-wheel-canvas" width="380" height="380" class="drop-shadow-xl"></canvas>
            </div>

            {{-- Winner Display --}}
            <div id="spin-wheel-winner" class="hidden mb-4 p-4 rounded-xl bg-gradient-to-r from-primary/20 to-purple-500/20 border border-primary/30 text-center">
                <p class="text-sm text-gray-500 dark:text-white/50 mb-1">🎉 Winner</p>
                <p id="spin-wheel-winner-text" class="text-xl font-bold text-gray-900 dark:text-white"></p>
            </div>

            {{-- Options Input --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-white/70 mb-2">Options (one per line)</label>
                <textarea id="spin-wheel-options" rows="4" placeholder="Option 1&#10;Option 2&#10;Option 3&#10;..." 
                    class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-white/40 focus:border-primary focus:ring-2 focus:ring-primary/20 transition resize-none"></textarea>
            </div>

            {{-- Quick Options --}}
            <div class="flex flex-wrap gap-2 mb-4">
                <button type="button" onclick="loadGroupMembers()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">
                    <i class="bi bi-people mr-1"></i> Use Group Members
                </button>
                <button type="button" onclick="loadYesNo()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">
                    Yes / No
                </button>
                <button type="button" onclick="loadNumbers()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">
                    1-10
                </button>
                <button type="button" onclick="clearWheelOptions()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/30 transition">
                    <i class="bi bi-trash mr-1"></i> Clear
                </button>
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-gray-200 dark:border-white/10 px-5 py-4 flex justify-end gap-3">
            <button type="button" data-chat-modal-close="spin-wheel" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-white/70 hover:bg-gray-100 dark:hover:bg-white/10 transition">
                Close
            </button>
            <button type="button" id="spin-wheel-btn" onclick="spinTheWheel()" class="px-6 py-2 rounded-lg text-sm font-semibold bg-gradient-to-r from-primary to-purple-600 text-white hover:opacity-90 transition flex items-center gap-2">
                <i class="bi bi-arrow-repeat"></i> Spin!
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    let wheelOptions = [];
    let isSpinning = false;
    let currentRotation = 0;
    const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899', '#f43f5e', '#14b8a6'];
    
    const canvas = document.getElementById('spin-wheel-canvas');
    const ctx = canvas?.getContext('2d');
    const optionsTextarea = document.getElementById('spin-wheel-options');
    
    function drawWheel() {
        if (!ctx || !canvas) return;
        
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 10;
        
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        if (wheelOptions.length === 0) {
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
            ctx.fillStyle = '#374151';
            ctx.fill();
            ctx.fillStyle = '#9ca3af';
            ctx.font = '14px sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('Add options below', centerX, centerY);
            return;
        }
        
        const sliceAngle = (2 * Math.PI) / wheelOptions.length;
        
        wheelOptions.forEach((option, i) => {
            const startAngle = currentRotation + (i * sliceAngle);
            const endAngle = startAngle + sliceAngle;
            
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, startAngle, endAngle);
            ctx.closePath();
            ctx.fillStyle = colors[i % colors.length];
            ctx.fill();
            ctx.strokeStyle = '#ffffff';
            ctx.lineWidth = 2;
            ctx.stroke();
            
            // Draw text - flip if on left side of wheel
            ctx.save();
            ctx.translate(centerX, centerY);
            const textAngle = startAngle + sliceAngle / 2;
            ctx.rotate(textAngle);
            
            ctx.fillStyle = '#ffffff';
            ctx.font = 'bold 12px sans-serif';
            ctx.shadowColor = 'rgba(0,0,0,0.5)';
            ctx.shadowBlur = 2;
            const text = option.length > 12 ? option.substring(0, 12) + '...' : option;
            
            // Check if text would be upside down (left side of wheel)
            const normalizedAngle = ((textAngle % (2 * Math.PI)) + (2 * Math.PI)) % (2 * Math.PI);
            if (normalizedAngle > Math.PI / 2 && normalizedAngle < Math.PI * 1.5) {
                // Flip text for left side
                ctx.rotate(Math.PI);
                ctx.textAlign = 'left';
                ctx.fillText(text, -radius + 15, 4);
            } else {
                // Normal text for right side
                ctx.textAlign = 'right';
                ctx.fillText(text, radius - 15, 4);
            }
            ctx.restore();
        });
        
        ctx.beginPath();
        ctx.arc(centerX, centerY, 20, 0, 2 * Math.PI);
        ctx.fillStyle = '#ffffff';
        ctx.fill();
        ctx.strokeStyle = '#e5e7eb';
        ctx.lineWidth = 3;
        ctx.stroke();
    }
    
    function updateOptions() {
        const text = optionsTextarea?.value || '';
        wheelOptions = text.split('\n').map(s => s.trim()).filter(s => s.length > 0);
        drawWheel();
        document.getElementById('spin-wheel-winner')?.classList.add('hidden');
    }
    
    optionsTextarea?.addEventListener('input', updateOptions);
    
    window.spinTheWheel = function() {
        if (isSpinning || wheelOptions.length < 2) {
            if (wheelOptions.length < 2) {
                if (typeof showChatToast === 'function') {
                    showChatToast('Add at least 2 options to spin!', 'warning');
                }
            }
            return;
        }
        
        isSpinning = true;
        const btn = document.getElementById('spin-wheel-btn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Spinning...';
        }
        document.getElementById('spin-wheel-winner')?.classList.add('hidden');
        
        const spinDuration = 4000;
        const totalRotation = (Math.random() * 5 + 8) * Math.PI * 2;
        const startRotation = currentRotation;
        const startTime = performance.now();
        
        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / spinDuration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 4);
            
            currentRotation = startRotation + (totalRotation * easeOut);
            drawWheel();
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                isSpinning = false;
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Spin!';
                }
                
                const normalizedRotation = currentRotation % (2 * Math.PI);
                const sliceAngle = (2 * Math.PI) / wheelOptions.length;
                const pointerAngle = (2 * Math.PI) - normalizedRotation + (Math.PI / 2);
                let winnerIndex = Math.floor(((pointerAngle % (2 * Math.PI)) + (2 * Math.PI)) % (2 * Math.PI) / sliceAngle);
                winnerIndex = winnerIndex % wheelOptions.length;
                
                const winner = wheelOptions[winnerIndex];
                const winnerEl = document.getElementById('spin-wheel-winner');
                const winnerText = document.getElementById('spin-wheel-winner-text');
                if (winnerEl && winnerText) {
                    winnerText.textContent = winner;
                    winnerEl.classList.remove('hidden');
                }
                
                if (typeof showChatToast === 'function') {
                    showChatToast(`🎉 Winner: ${winner}`, 'success');
                }
            }
        }
        
        requestAnimationFrame(animate);
    };
    
    window.loadGroupMembers = function() {
        const members = [];
        document.querySelectorAll('.member-item').forEach(el => {
            const nameEl = el.querySelector('p.font-medium, span.truncate');
            if (nameEl) {
                const name = nameEl.textContent.trim();
                if (name && !members.includes(name)) members.push(name);
            }
        });
        
        if (members.length === 0) {
            if (typeof showChatToast === 'function') {
                showChatToast('No group members found. Open a group chat first!', 'warning');
            }
            return;
        }
        
        if (optionsTextarea) {
            optionsTextarea.value = members.join('\n');
            updateOptions();
        }
    };
    
    window.loadYesNo = function() {
        if (optionsTextarea) {
            optionsTextarea.value = 'Yes\nNo';
            updateOptions();
        }
    };
    
    window.loadNumbers = function() {
        if (optionsTextarea) {
            optionsTextarea.value = '1\n2\n3\n4\n5\n6\n7\n8\n9\n10';
            updateOptions();
        }
    };
    
    window.clearWheelOptions = function() {
        if (optionsTextarea) {
            optionsTextarea.value = '';
            updateOptions();
        }
    };
    
    window.openSpinWheel = function() {
        const modal = document.querySelector('[data-chat-modal="spin-wheel"]');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            updateOptions();
        }
    };
    
    const spinModal = document.querySelector('[data-chat-modal="spin-wheel"]');
    spinModal?.querySelector('[data-chat-modal-close="spin-wheel"]')?.addEventListener('click', () => {
        spinModal.classList.add('hidden');
        spinModal.classList.remove('flex');
    });
    
    spinModal?.addEventListener('click', (e) => {
        if (e.target === spinModal) {
            spinModal.classList.add('hidden');
            spinModal.classList.remove('flex');
        }
    });
    
    document.addEventListener('DOMContentLoaded', () => {
        drawWheel();
    });
})();
</script>
