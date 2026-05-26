{{-- Duck Race Modal --}}
<div data-chat-modal="duck-race" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-4xl mx-4 rounded-2xl bg-white dark:bg-[#222222] shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-3">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-lg bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                    <svg class="w-6 h-6" viewBox="0 0 64 64" fill="none">
                        <path d="M20 35c0-12 8-20 18-20s14 8 14 18c0 8-4 14-10 16l-4 8h-8l-2-6c-5-2-8-8-8-16z" fill="#fbbf24"/>
                        <circle cx="30" cy="28" r="3" fill="#1f2937"/>
                        <path d="M42 32c4 0 8-2 10-4" stroke="#fbbf24" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Duck Race</h2>
                    <p class="text-xs text-gray-500 dark:text-white/50">Race to the finish!</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div id="duck-race-timer" class="hidden items-center gap-2 px-4 py-2 rounded-xl bg-white dark:bg-white/10 border border-gray-200 dark:border-white/20">
                    <i class="bi bi-stopwatch text-orange-500"></i>
                    <span id="duck-race-time" class="text-2xl font-mono font-bold text-gray-900 dark:text-white">0.00</span>
                    <span class="text-xs text-gray-500 dark:text-white/50">sec</span>
                </div>
                <button type="button" data-chat-modal-close="duck-race" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-5">
            {{-- Winner Banner --}}
            <div id="duck-race-winner" class="hidden mb-4 p-4 rounded-xl bg-gradient-to-r from-yellow-400/20 to-orange-500/20 border border-yellow-500/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🏆</span>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-white/50">Winner!</p>
                            <p id="duck-race-winner-text" class="text-xl font-bold text-gray-900 dark:text-white"></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 dark:text-white/50">Finish Time</p>
                        <p id="duck-race-final-time" class="text-3xl font-mono font-bold text-primary"></p>
                    </div>
                </div>
            </div>

            {{-- Race Track --}}
            <div id="duck-race-track" class="relative rounded-xl overflow-hidden mb-4" style="min-height: 320px;">
                {{-- Animated Water Background --}}
                <div class="absolute inset-0 bg-gradient-to-b from-sky-300 to-blue-500 dark:from-sky-800 dark:to-blue-900">
                    <svg class="water-wave water-wave-1 absolute bottom-0 left-0 w-[200%] h-32" viewBox="0 0 1440 120" preserveAspectRatio="none">
                        <path d="M0,60 C360,120 720,0 1080,60 C1260,90 1350,30 1440,60 L1440,120 L0,120 Z" fill="rgba(255,255,255,0.15)"/>
                    </svg>
                    <svg class="water-wave water-wave-2 absolute bottom-0 left-0 w-[200%] h-24" viewBox="0 0 1440 120" preserveAspectRatio="none">
                        <path d="M0,80 C240,40 480,100 720,60 C960,20 1200,80 1440,40 L1440,120 L0,120 Z" fill="rgba(255,255,255,0.1)"/>
                    </svg>
                    <svg class="water-wave water-wave-3 absolute bottom-0 left-0 w-[200%] h-20" viewBox="0 0 1440 120" preserveAspectRatio="none">
                        <path d="M0,40 Q360,100 720,40 T1440,40 L1440,120 L0,120 Z" fill="rgba(255,255,255,0.05)"/>
                    </svg>
                    <div class="bubble bubble-1"></div>
                    <div class="bubble bubble-2"></div>
                    <div class="bubble bubble-3"></div>
                    <div class="bubble bubble-4"></div>
                    <div class="bubble bubble-5"></div>
                    <div class="bubble bubble-6"></div>
                </div>
                
                {{-- Finish Line (hidden initially, shows in last 5 seconds) --}}
                <div id="duck-finish-line" class="absolute right-8 top-0 bottom-0 w-4 flex flex-col z-10 shadow-lg opacity-0 transition-all duration-500 translate-x-8">
                    @for($i = 0; $i < 16; $i++)
                    <div class="flex-1 {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-900' }}"></div>
                    @endfor
                </div>
                <div id="duck-finish-label" class="absolute right-4 top-3 z-20 opacity-0 transition-all duration-500 translate-x-8">
                    <div class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-lg flex items-center gap-1 animate-pulse">
                        <i class="bi bi-flag-fill"></i> FINISH
                    </div>
                </div>
                
                {{-- Duck Lanes --}}
                <div id="duck-lanes" class="relative p-4 pt-10 z-20"></div>
                
                {{-- Empty State --}}
                <div id="duck-empty-state" class="relative z-20 flex flex-col items-center justify-center py-16 text-white">
                    <svg class="w-24 h-24 mb-4 drop-shadow-lg animate-bounce" viewBox="0 0 64 64" fill="none">
                        <path d="M20 35c0-12 8-20 18-20s14 8 14 18c0 8-4 14-10 16l-4 8h-8l-2-6c-5-2-8-8-8-16z" fill="#fbbf24"/>
                        <circle cx="30" cy="28" r="3" fill="#1f2937"/>
                        <path d="M42 32c4 0 8-2 10-4" stroke="#fbbf24" stroke-width="3" stroke-linecap="round"/>
                        <ellipse cx="22" cy="38" rx="5" ry="4" fill="#f59e0b" opacity="0.7"/>
                    </svg>
                    <p class="text-lg font-bold drop-shadow">Add racers to start!</p>
                </div>
            </div>

            {{-- Controls Row --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                {{-- Racers Input --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-white/70 mb-2">Racers (one per line, max 6)</label>
                    <textarea id="duck-race-racers" rows="3" placeholder="Racer 1&#10;Racer 2&#10;Racer 3" 
                        class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-white/40 focus:border-primary focus:ring-2 focus:ring-primary/20 transition resize-none"></textarea>
                </div>
                
                {{-- Timer Settings --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-white/70 mb-2">Race Duration</label>
                    <select id="duck-race-duration" class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-4 py-3 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
                        <option value="0">No Limit (First to Finish)</option>
                        <option value="5">5 seconds</option>
                        <option value="10">10 seconds</option>
                        <option value="15">15 seconds</option>
                        <option value="30">30 seconds</option>
                        <option value="60">60 seconds</option>
                    </select>
                    <p class="text-xs text-gray-500 dark:text-white/40 mt-1">Set a time limit or race to finish</p>
                </div>
            </div>

            {{-- Quick Options --}}
            <div class="flex flex-wrap gap-2">
                <button type="button" onclick="loadDuckGroupMembers()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">
                    <i class="bi bi-people mr-1"></i> Group Members
                </button>
                <button type="button" onclick="loadDuckNumbers()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">
                    Duck 1-5
                </button>
                <button type="button" onclick="clearDuckRacers()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-500/30 transition">
                    <i class="bi bi-trash mr-1"></i> Clear
                </button>
                <div class="flex-1"></div>
                <button type="button" id="duck-stream-btn" onclick="toggleDuckStream()" class="px-3 py-1.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 hover:bg-purple-200 dark:hover:bg-purple-500/30 transition flex items-center gap-1">
                    <i class="bi bi-broadcast"></i> <span>Stream to Chat</span>
                </button>
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-gray-200 dark:border-white/10 px-5 py-4 flex justify-end gap-3">
            <button type="button" data-chat-modal-close="duck-race" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-white/70 hover:bg-gray-100 dark:hover:bg-white/10 transition">
                Close
            </button>
            <button type="button" id="duck-race-btn" onclick="startDuckRace()" class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-gradient-to-r from-yellow-400 to-orange-500 text-white hover:opacity-90 transition flex items-center gap-2 shadow-lg">
                <svg class="w-5 h-5" viewBox="0 0 64 64" fill="none">
                    <path d="M20 35c0-12 8-20 18-20s14 8 14 18c0 8-4 14-10 16l-4 8h-8l-2-6c-5-2-8-8-8-16z" fill="white"/>
                    <circle cx="30" cy="28" r="3" fill="#1f2937"/>
                </svg>
                Start Race!
            </button>
        </div>
    </div>
</div>

<style>
@keyframes wave-move-1 { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
@keyframes wave-move-2 { 0% { transform: translateX(-50%); } 100% { transform: translateX(0); } }
@keyframes bubble-rise {
    0% { transform: translateY(100%) scale(0); opacity: 0; }
    10% { opacity: 0.7; }
    90% { opacity: 0.7; }
    100% { transform: translateY(-400px) scale(1); opacity: 0; }
}
@keyframes duck-swim {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    25% { transform: translateY(-5px) rotate(-4deg); }
    75% { transform: translateY(-5px) rotate(4deg); }
}
@keyframes duck-splash {
    0% { opacity: 0; transform: scale(0.3) translateX(0); }
    50% { opacity: 1; transform: scale(1) translateX(-10px); }
    100% { opacity: 0; transform: scale(0.3) translateX(-20px); }
}
@keyframes winner-celebrate {
    0%, 100% { transform: scale(1) rotate(0deg); }
    25% { transform: scale(1.15) rotate(-8deg); }
    75% { transform: scale(1.15) rotate(8deg); }
}
@keyframes ripple {
    0% { transform: scale(0.5); opacity: 0.6; }
    100% { transform: scale(2.5); opacity: 0; }
}
@keyframes name-float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-2px); }
}
@keyframes countdown-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.8; }
}

.water-wave-1 { animation: wave-move-1 6s linear infinite; }
.water-wave-2 { animation: wave-move-2 5s linear infinite; }
.water-wave-3 { animation: wave-move-1 8s linear infinite; }

.bubble {
    position: absolute;
    background: rgba(255,255,255,0.4);
    border-radius: 50%;
    animation: bubble-rise 6s ease-in infinite;
}
.bubble-1 { left: 8%; width: 10px; height: 10px; animation-delay: 0s; animation-duration: 5s; }
.bubble-2 { left: 22%; width: 6px; height: 6px; animation-delay: 1.5s; animation-duration: 7s; }
.bubble-3 { left: 38%; width: 8px; height: 8px; animation-delay: 0.5s; animation-duration: 6s; }
.bubble-4 { left: 55%; width: 12px; height: 12px; animation-delay: 2s; animation-duration: 5.5s; }
.bubble-5 { left: 72%; width: 7px; height: 7px; animation-delay: 3s; animation-duration: 8s; }
.bubble-6 { left: 88%; width: 9px; height: 9px; animation-delay: 1s; animation-duration: 6.5s; }

.duck-lane { margin-bottom: 6px; }
.duck-racer { transition: left 0.06s linear; }
.duck-racer.racing { animation: duck-swim 0.3s ease-in-out infinite; }
.duck-racer.winner { animation: winner-celebrate 0.35s ease-in-out infinite; }
.duck-racer .splash {
    position: absolute;
    right: 90%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0;
}
.duck-racer.racing .splash { animation: duck-splash 0.4s ease-out infinite; }
.duck-racer .ripple {
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 40px;
    height: 12px;
    background: rgba(255,255,255,0.4);
    border-radius: 50%;
    transform: translateX(-50%);
    opacity: 0;
}
.duck-racer.racing .ripple { animation: ripple 0.6s ease-out infinite; }
.duck-name {
    position: absolute;
    top: -22px;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    z-index: 30;
}
.duck-racer.racing .duck-name { animation: name-float 0.5s ease-in-out infinite; }
.countdown-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,0.5);
    z-index: 100;
}
.countdown-number {
    font-size: 120px;
    font-weight: 900;
    color: white;
    text-shadow: 0 0 40px rgba(255,255,255,0.5);
    animation: countdown-pulse 0.5s ease-in-out;
}
.stream-active { background: #7c3aed !important; color: white !important; }
</style>

<script>
(function() {
    let racers = [];
    let isRacing = false;
    let raceInterval = null;
    let timerInterval = null;
    let raceStartTime = 0;
    let streamToChat = false;
    let raceDuration = 0;
    
    const duckColors = [
        { body: '#fbbf24', wing: '#f59e0b', beak: '#ea580c', name: 'Yellow', bg: '#fef3c7' },
        { body: '#f472b6', wing: '#ec4899', beak: '#db2777', name: 'Pink', bg: '#fce7f3' },
        { body: '#60a5fa', wing: '#3b82f6', beak: '#2563eb', name: 'Blue', bg: '#dbeafe' },
        { body: '#4ade80', wing: '#22c55e', beak: '#16a34a', name: 'Green', bg: '#dcfce7' },
        { body: '#c084fc', wing: '#a855f7', beak: '#9333ea', name: 'Purple', bg: '#f3e8ff' },
        { body: '#fb923c', wing: '#f97316', beak: '#ea580c', name: 'Orange', bg: '#ffedd5' },
    ];
    
    function getDuckSvg(color, size = 50) {
        return `<svg width="${size}" height="${size}" viewBox="0 0 64 64" fill="none" class="drop-shadow-lg">
            <ellipse cx="32" cy="54" rx="14" ry="5" fill="${color.body}" opacity="0.3"/>
            <path d="M16 32c0-11 8-20 18-20s15 8 15 18c0 8-4 13-9 15l-4 12h-12l-2-10c-4-2-6-8-6-15z" fill="${color.body}"/>
            <ellipse cx="21" cy="40" rx="6" ry="5" fill="${color.wing}" opacity="0.8"/>
            <circle cx="27" cy="24" r="5" fill="white"/>
            <circle cx="28" cy="24" r="3" fill="#1f2937"/>
            <circle cx="29.5" cy="22.5" r="1.5" fill="white"/>
            <path d="M38 28c4 0 9-2 12-5" stroke="${color.body}" stroke-width="5" stroke-linecap="round"/>
            <path d="M38 28c4 0 9-2 12-5" stroke="${color.wing}" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M46 30l10-3c1.5 0 2.5 1.5 2 3l-5 5c-.7.7-2 .7-2.7 0l-5-4c-.7-.7-.3-1.5.7-1z" fill="${color.beak}"/>
            <path d="M18 52l-3 8c-.5 1.5.5 2.5 2 2.5h8c1.5 0 2-1 1.5-2.5l-4-8" fill="${color.beak}"/>
            <path d="M30 52l3 8c.5 1.5-.5 2.5-2 2.5h-8c-1.5 0-2-1-1.5-2.5l4-8" fill="${color.beak}"/>
        </svg>`;
    }
    
    function getSplashSvg() {
        return `<svg class="splash" width="30" height="30" viewBox="0 0 30 30" fill="none">
            <circle cx="8" cy="15" r="4" fill="#7dd3fc"/>
            <circle cx="18" cy="8" r="3" fill="#7dd3fc"/>
            <circle cx="16" cy="22" r="2" fill="#7dd3fc"/>
            <circle cx="26" cy="12" r="2" fill="#7dd3fc"/>
        </svg>`;
    }
    
    const racersTextarea = document.getElementById('duck-race-racers');
    const lanesContainer = document.getElementById('duck-lanes');
    const emptyState = document.getElementById('duck-empty-state');
    const timerDisplay = document.getElementById('duck-race-timer');
    const timeText = document.getElementById('duck-race-time');
    const durationSelect = document.getElementById('duck-race-duration');
    
    function updateTimer() {
        if (!raceStartTime) return;
        const elapsed = (performance.now() - raceStartTime) / 1000;
        if (timeText) {
            if (raceDuration > 0) {
                const remaining = Math.max(0, raceDuration - elapsed);
                timeText.textContent = remaining.toFixed(2);
                
                if (remaining <= 5 && remaining > 0) {
                    showFinishLine();
                }
                
                if (remaining <= 0) endRaceByTimer();
            } else {
                timeText.textContent = elapsed.toFixed(2);
            }
        }
    }
    
    function showFinishLine() {
        const finishLine = document.getElementById('duck-finish-line');
        const finishLabel = document.getElementById('duck-finish-label');
        if (finishLine && !finishLine.classList.contains('finish-visible')) {
            finishLine.classList.add('finish-visible');
            finishLine.style.opacity = '1';
            finishLine.style.transform = 'translateX(0)';
        }
        if (finishLabel && !finishLabel.classList.contains('finish-visible')) {
            finishLabel.classList.add('finish-visible');
            finishLabel.style.opacity = '1';
            finishLabel.style.transform = 'translateX(0)';
        }
    }
    
    function hideFinishLine() {
        const finishLine = document.getElementById('duck-finish-line');
        const finishLabel = document.getElementById('duck-finish-label');
        if (finishLine) {
            finishLine.classList.remove('finish-visible');
            finishLine.style.opacity = '0';
            finishLine.style.transform = 'translateX(8px)';
        }
        if (finishLabel) {
            finishLabel.classList.remove('finish-visible');
            finishLabel.style.opacity = '0';
            finishLabel.style.transform = 'translateX(8px)';
        }
    }
    
    function updateRacers() {
        const text = racersTextarea?.value || '';
        racers = text.split('\n').map(s => s.trim()).filter(s => s.length > 0).slice(0, 6);
        renderLanes();
        document.getElementById('duck-race-winner')?.classList.add('hidden');
        if (timerDisplay) timerDisplay.classList.add('hidden');
        if (timeText) timeText.textContent = '0.00';
        hideFinishLine();
    }
    
    function renderLanes() {
        if (!lanesContainer) return;
        if (racers.length === 0) {
            lanesContainer.innerHTML = '';
            emptyState?.classList.remove('hidden');
            return;
        }
        emptyState?.classList.add('hidden');
        lanesContainer.innerHTML = racers.map((racer, i) => {
            const color = duckColors[i % duckColors.length];
            return `
            <div class="duck-lane relative h-16" data-lane="${i}">
                <div class="duck-racer absolute flex flex-col items-center" data-racer="${i}" style="left: 2%;">
                    <div class="duck-name text-gray-900" style="background: ${color.bg}; border: 2px solid ${color.body};">${racer}</div>
                    <div class="relative">
                        <div class="ripple"></div>
                        ${getSplashSvg()}
                        ${getDuckSvg(color, 52)}
                    </div>
                </div>
            </div>
        `}).join('');
    }
    
    racersTextarea?.addEventListener('input', updateRacers);

    function showCountdown(callback) {
        const track = document.getElementById('duck-race-track');
        let count = 3;
        
        const overlay = document.createElement('div');
        overlay.className = 'countdown-overlay';
        overlay.innerHTML = `<div class="countdown-number">${count}</div>`;
        track.appendChild(overlay);
        
        const countInterval = setInterval(() => {
            count--;
            if (count > 0) {
                overlay.innerHTML = `<div class="countdown-number">${count}</div>`;
            } else if (count === 0) {
                overlay.innerHTML = `<div class="countdown-number" style="color: #22c55e;">GO!</div>`;
            } else {
                clearInterval(countInterval);
                overlay.remove();
                callback();
            }
        }, 800);
    }
    
    function endRaceByTimer() {
        if (!isRacing) return;
        
        clearInterval(raceInterval);
        clearInterval(timerInterval);
        isRacing = false;
        
        const positions = [];
        document.querySelectorAll('.duck-racer').forEach((el, i) => {
            const left = parseFloat(el.style.left) || 0;
            positions.push({ index: i, position: left, name: racers[i], color: duckColors[i % duckColors.length] });
            el.classList.remove('racing');
        });
        
        positions.sort((a, b) => b.position - a.position);
        const winner = positions[0];
        
        document.querySelector(`[data-racer="${winner.index}"]`)?.classList.add('winner');
        
        const btn = document.getElementById('duck-race-btn');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = `<svg class="w-5 h-5" viewBox="0 0 64 64" fill="none"><path d="M20 35c0-12 8-20 18-20s14 8 14 18c0 8-4 14-10 16l-4 8h-8l-2-6c-5-2-8-8-8-16z" fill="white"/><circle cx="30" cy="28" r="3" fill="#1f2937"/></svg> Start Race!`;
        }
        
        showWinner(winner, raceDuration.toFixed(2));
    }
    
    function showWinner(winner, time) {
        const winnerEl = document.getElementById('duck-race-winner');
        const winnerText = document.getElementById('duck-race-winner-text');
        const finalTimeEl = document.getElementById('duck-race-final-time');
        
        if (winnerEl && winnerText) {
            winnerText.innerHTML = `<span style="color: ${winner.color.body}">${winner.color.name} Duck</span> - ${winner.name}`;
            if (finalTimeEl) finalTimeEl.textContent = time + 's';
            winnerEl.classList.remove('hidden');
        }
        
        if (typeof showChatToast === 'function') {
            showChatToast(`🏆 ${winner.name} wins in ${time}s!`, 'success');
        }
        
        if (streamToChat) {
            sendRaceResultToChat(winner, time);
        }
    }
    
    function sendRaceResultToChat(winner, time) {
        const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
        if (!conversationId) return;
        
        const results = racers.map((name, i) => {
            const pos = parseFloat(document.querySelector(`[data-racer="${i}"]`)?.style.left || 0);
            return { name, position: pos, color: duckColors[i % duckColors.length] };
        }).sort((a, b) => b.position - a.position);
        
        let message = `🏁 **Duck Race Results** 🦆\n\n`;
        message += `🏆 Winner: **${winner.name}** (${winner.color.name} Duck)\n`;
        message += `⏱️ Time: **${time}s**\n\n`;
        message += `📊 Final Standings:\n`;
        results.forEach((r, i) => {
            const medal = i === 0 ? '🥇' : i === 1 ? '🥈' : i === 2 ? '🥉' : `${i + 1}.`;
            message += `${medal} ${r.name}\n`;
        });
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const topicId = window.currentTopicId || null;
        
        const payload = { body: message };
        if (topicId) payload.topic_id = topicId;
        
        fetch(`/chats/${conversationId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload)
        }).catch(err => console.error('Failed to send race results:', err));
    }
    
    window.startDuckRace = function() {
        if (isRacing || racers.length < 2) {
            if (racers.length < 2) {
                if (typeof showChatToast === 'function') showChatToast('Add at least 2 racers!', 'warning');
            }
            return;
        }
        
        raceDuration = parseInt(durationSelect?.value || 0);
        
        const btn = document.getElementById('duck-race-btn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split animate-pulse"></i> Get Ready...';
        }
        
        document.getElementById('duck-race-winner')?.classList.add('hidden');
        hideFinishLine();
        
        const duckEls = document.querySelectorAll('.duck-racer');
        duckEls.forEach(el => {
            el.style.left = '2%';
            el.classList.remove('racing', 'winner');
        });
        
        showCountdown(() => {
            actuallyStartRace(btn, duckEls);
        });
    };

    function actuallyStartRace(btn, duckEls) {
        isRacing = true;
        raceStartTime = performance.now();
        
        if (btn) btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Racing...';
        
        if (timerDisplay) {
            timerDisplay.classList.remove('hidden');
            timerDisplay.classList.add('flex');
        }
        if (timeText) timeText.textContent = raceDuration > 0 ? raceDuration.toFixed(2) : '0.00';
        
        timerInterval = setInterval(updateTimer, 50);
        
        duckEls.forEach(el => el.classList.add('racing'));
        
        const positions = racers.map(() => 2);
        const finishLine = 78;
        const startPos = 2;
        const totalDistance = finishLine - startPos;
        let winner = null;
        
        const baseSpeed = raceDuration > 0 ? (totalDistance / (raceDuration * 25)) : 1.5;
        const duckSpeeds = racers.map(() => baseSpeed * (0.7 + Math.random() * 0.6));
        
        raceInterval = setInterval(() => {
            let maxPosition = 0;
            const elapsed = (performance.now() - raceStartTime) / 1000;
            
            racers.forEach((_, i) => {
                if (positions[i] < finishLine) {
                    const randomFactor = 0.5 + Math.random();
                    const speed = duckSpeeds[i] * randomFactor;
                    positions[i] = Math.min(positions[i] + speed, finishLine);
                    
                    const duckEl = document.querySelector(`[data-racer="${i}"]`);
                    if (duckEl) duckEl.style.left = positions[i] + '%';
                    
                    if (positions[i] > maxPosition) maxPosition = positions[i];
                    
                    if (positions[i] >= finishLine && !winner && raceDuration === 0) {
                        winner = { index: i, name: racers[i], color: duckColors[i % duckColors.length] };
                    }
                }
            });
            
            if (raceDuration === 0 && maxPosition >= 60) {
                showFinishLine();
            }
            
            if (winner) {
                clearInterval(raceInterval);
                clearInterval(timerInterval);
                isRacing = false;
                
                const finalTime = ((performance.now() - raceStartTime) / 1000).toFixed(2);
                
                duckEls.forEach((el, i) => {
                    el.classList.remove('racing');
                    if (i === winner.index) el.classList.add('winner');
                });
                
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = `<svg class="w-5 h-5" viewBox="0 0 64 64" fill="none"><path d="M20 35c0-12 8-20 18-20s14 8 14 18c0 8-4 14-10 16l-4 8h-8l-2-6c-5-2-8-8-8-16z" fill="white"/><circle cx="30" cy="28" r="3" fill="#1f2937"/></svg> Start Race!`;
                }
                
                showWinner(winner, finalTime);
            }
        }, 40);
    }
    
    window.toggleDuckStream = function() {
        streamToChat = !streamToChat;
        const btn = document.getElementById('duck-stream-btn');
        if (btn) {
            if (streamToChat) {
                btn.classList.add('stream-active');
                btn.innerHTML = '<i class="bi bi-broadcast-pin"></i> <span>Streaming ON</span>';
                if (typeof showChatToast === 'function') showChatToast('Race results will be sent to chat!', 'info');
            } else {
                btn.classList.remove('stream-active');
                btn.innerHTML = '<i class="bi bi-broadcast"></i> <span>Stream to Chat</span>';
            }
        }
    };
    
    window.loadDuckGroupMembers = function() {
        const members = [];
        document.querySelectorAll('.member-item').forEach(el => {
            const nameEl = el.querySelector('p.font-medium, span.truncate');
            if (nameEl) {
                const name = nameEl.textContent.trim();
                if (name && !members.includes(name)) members.push(name);
            }
        });
        if (members.length === 0) {
            if (typeof showChatToast === 'function') showChatToast('No group members found. Open a group chat first!', 'warning');
            return;
        }
        if (racersTextarea) {
            racersTextarea.value = members.slice(0, 6).join('\n');
            updateRacers();
        }
    };
    
    window.loadDuckNumbers = function() {
        if (racersTextarea) {
            racersTextarea.value = 'Duck 1\nDuck 2\nDuck 3\nDuck 4\nDuck 5';
            updateRacers();
        }
    };
    
    window.clearDuckRacers = function() {
        if (racersTextarea) {
            racersTextarea.value = '';
            updateRacers();
        }
    };
    
    window.openDuckRace = function() {
        const modal = document.querySelector('[data-chat-modal="duck-race"]');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            updateRacers();
        }
    };
    
    const duckModal = document.querySelector('[data-chat-modal="duck-race"]');
    
    function closeModal() {
        if (raceInterval) clearInterval(raceInterval);
        if (timerInterval) clearInterval(timerInterval);
        isRacing = false;
        raceStartTime = 0;
        duckModal?.classList.add('hidden');
        duckModal?.classList.remove('flex');
        document.querySelector('.countdown-overlay')?.remove();
    }
    
    duckModal?.querySelector('[data-chat-modal-close="duck-race"]')?.addEventListener('click', closeModal);
    duckModal?.addEventListener('click', (e) => { if (e.target === duckModal) closeModal(); });
})();
</script>
