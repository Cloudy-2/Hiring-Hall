@if(auth()->check() && in_array(auth()->user()->role, ['moderator', 'admin', 'super_admin']))
<div id="newUpdateContextMenu" class="fixed hidden z-[99999] bg-white rounded-lg shadow-xl border border-gray-200 py-2 min-w-[220px]" style="display: none;">
    <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase border-b mb-1">New Update Manager</div>
    <button id="markAsNewBtn" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700">
        <i class="bi bi-star-fill text-yellow-500"></i>
        Mark as New Update
    </button>
    <button id="markAsBugBtn" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">
        <i class="bi bi-bug-fill text-red-500"></i>
        Mark as Bug
    </button>
    <button id="editBugStatusBtn" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600">
        <i class="bi bi-pencil-square text-blue-500"></i>
        Edit Bug Status <span class="text-xs text-gray-400 ml-auto">Ctrl+E</span>
    </button>
    <button id="removeNewBtn" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">
        <i class="bi bi-x-circle text-red-500"></i>
        Remove Badge
    </button>
    <div class="border-t my-1"></div>
    <button id="viewAllUpdatesBtn" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
        <i class="bi bi-list-ul text-gray-500"></i>
        View All Updates
    </button>
</div>

<div id="quickEditBugModal" class="fixed inset-0 hidden" style="z-index: 999999; isolation: isolate;">
    <div class="absolute inset-0" style="z-index: 999999; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(2px);" onclick="closeQuickEditBugModal()"></div>
    <div class="absolute top-1/2 left-1/2 bg-white rounded-xl shadow-2xl" style="transform: translate(-50%, -50%); width: 350px; max-width: 90vw; z-index: 1000000;">
        <div class="p-4 border-b bg-blue-50">
            <h3 class="font-semibold text-blue-800 flex items-center gap-2">
                <i class="bi bi-pencil-square"></i> Quick Edit Bug Status
            </h3>
            <p class="text-xs text-blue-600 mt-1" id="quickEditBugName">Bug name here</p>
        </div>
        <div class="p-4">
            <input type="hidden" id="quickEditBugId">
            <div class="space-y-2">
                <button onclick="setQuickBugStatus('open')" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg border hover:bg-red-50 hover:border-red-300 transition-colors quick-status-btn" data-status="open">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span class="font-medium">Not Started</span>
                </button>
                <button onclick="setQuickBugStatus('in_progress')" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg border hover:bg-yellow-50 hover:border-yellow-300 transition-colors quick-status-btn" data-status="in_progress">
                    <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                    <span class="font-medium">In Progress</span>
                </button>
                <button onclick="setQuickBugStatus('resolved')" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg border hover:bg-green-50 hover:border-green-300 transition-colors quick-status-btn" data-status="resolved">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    <span class="font-medium">Need Testing / Resolved</span>
                </button>
            </div>
        </div>
        <div class="p-4 border-t flex justify-end">
            <button onclick="closeQuickEditBugModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
        </div>
    </div>
</div>

<div id="markAsNewModal" class="fixed inset-0 hidden" style="z-index: 999999; isolation: isolate;">
    <div class="absolute inset-0" style="z-index: 999999; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(2px);" onclick="closeMarkAsNewModal()"></div>
    <div class="absolute top-1/2 left-1/2 bg-white rounded-xl shadow-2xl" style="transform: translate(-50%, -50%); width: 600px; max-width: 90vw; z-index: 1000000;">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-800">Mark as New Update</h3>
            <p class="text-xs text-gray-500 mt-1">This will add a NEW badge to the selected element</p>
        </div>
        <div class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Feature Name *</label>
                <input type="text" id="newUpdateName" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="e.g. Task Templates">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="newUpdateDesc" class="w-full px-3 py-2 border rounded-lg text-sm" rows="2" placeholder="Brief description for What's New page"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="newUpdateCategory" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="feature">Feature</option>
                        <option value="improvement">Improvement</option>
                        <option value="fix">Fix</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expires After</label>
                    <select id="newUpdateExpiry" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="">Never</option>
                        <option value="7">7 days</option>
                        <option value="14">14 days</option>
                        <option value="30">30 days</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CSS Selector (auto-detected)</label>
                <input type="text" id="newUpdateSelector" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-50" readonly>
            </div>
        </div>
        <div class="p-4 border-t flex justify-end gap-2">
            <button onclick="closeMarkAsNewModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
            <button onclick="saveNewUpdate()" class="px-4 py-2 text-sm text-white bg-yellow-500 hover:bg-yellow-600 rounded-lg">Save</button>
        </div>
    </div>
</div>

<div id="markAsBugModal" class="fixed inset-0 hidden" style="z-index: 999999; isolation: isolate;">
    <div class="absolute inset-0" style="z-index: 999999; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(2px);" onclick="closeMarkAsBugModal()"></div>
    <div class="absolute top-1/2 left-1/2 bg-white rounded-xl shadow-2xl" style="transform: translate(-50%, -50%); width: 600px; max-width: 90vw; z-index: 1000000;">
        <div class="p-4 border-b bg-red-50">
            <h3 class="font-semibold text-red-800 flex items-center gap-2">
                <i class="bi bi-bug-fill"></i> Mark as Bug
            </h3>
            <p class="text-xs text-red-600 mt-1">This will add a BUG badge to the selected element</p>
        </div>
        <div class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bug Title *</label>
                <input type="text" id="bugName" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="e.g. Button not working">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="bugDesc" class="w-full px-3 py-2 border rounded-lg text-sm" rows="3" placeholder="Describe the bug..."></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select id="bugPriority" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="bugStatus" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CSS Selector (auto-detected)</label>
                <input type="text" id="bugSelector" class="w-full px-3 py-2 border rounded-lg text-sm bg-gray-50" readonly>
            </div>
        </div>
        <div class="p-4 border-t flex justify-end gap-2">
            <button onclick="closeMarkAsBugModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
            <button onclick="saveBug()" class="px-4 py-2 text-sm text-white bg-red-500 hover:bg-red-600 rounded-lg">Save Bug</button>
        </div>
    </div>
</div>
@endif

<script>
(function() {
    let targetElement = null;
    let newUpdatesCache = [];
    let showBugBadges = localStorage.getItem('showBugBadges') === 'true';
    const canManage = {{ auth()->check() && in_array(auth()->user()->role, ['moderator', 'admin', 'super_admin']) ? 'true' : 'false' }};
    const canViewBadges = {{ auth()->check() && auth()->user()->role !== 'applicant' ? 'true' : 'false' }};

    function updateBugBadgeVisibility() {
        const bugBadges = document.querySelectorAll('.bug-badge');
        bugBadges.forEach(badge => {
            badge.style.display = showBugBadges ? 'inline-block' : 'none';
        });
    }

    document.addEventListener('keydown', function(e) {
        if (!canViewBadges) return; // Skip for candidate
        if (e.ctrlKey && e.key === 'b') {
            e.preventDefault();
            showBugBadges = !showBugBadges;
            localStorage.setItem('showBugBadges', showBugBadges);
            updateBugBadgeVisibility();
            if (typeof Toast !== 'undefined') {
                new Toast().show(showBugBadges ? 'Bug badges visible' : 'Bug badges hidden', 'success', 1500);
            }
        }
    });

    async function loadAndInjectBadges() {
        try {
            const currentUrl = window.location.pathname;
            const res = await fetch(`/api/new-updates/selectors?url=${encodeURIComponent(currentUrl)}`);
            const data = await res.json();
            newUpdatesCache = data.updates || [];
            injectAllBadges();
            updateBugBadgeVisibility();
        } catch (e) {
            console.error('Failed to load badges:', e);
        }
    }

    function injectAllBadges() {
        newUpdatesCache.forEach(update => {
            if (update.selector) {
                tryInjectBadge(update);
            }
        });
    }

    function tryInjectBadge(update) {
        let escapedSelector = update.selector;
        escapedSelector = escapedSelector.replace(/\.([a-zA-Z0-9_-]+):([a-zA-Z])/g, '.$1\\:$2');

        let el = null;
        try {
            el = document.querySelector(escapedSelector);
        } catch (e) {
            try { el = document.querySelector(update.selector); } catch (e2) {}
        }

        if (!el && update.selector) {
            const idMatch = update.selector.match(/#([a-zA-Z0-9_-]+)/);
            if (idMatch) el = document.getElementById(idMatch[1]);
        }

        if (el && !el.querySelector('.new-update-badge') && !el.querySelector('.bug-badge')) {
            const parent = el.parentElement;
            if (parent && (parent.querySelector('.new-update-badge[data-update-id="' + update.id + '"]') ||
                          parent.querySelector('.bug-badge[data-update-id="' + update.id + '"]'))) {
                return false;
            }
            injectBadge(el, update);
            return true;
        }
        return false;
    }

    function injectBadge(element, update) {
        const voidElements = ['IMG', 'SVG', 'INPUT', 'BR', 'HR', 'AREA', 'BASE', 'COL', 'EMBED', 'LINK', 'META', 'PARAM', 'SOURCE', 'TRACK', 'WBR'];
        let targetElement = element;

        if (voidElements.includes(element.tagName)) {
            targetElement = element.parentElement;
            if (!targetElement) return;
        }

        if (targetElement.querySelector('.new-update-badge[data-update-id="' + update.id + '"]') ||
            targetElement.querySelector('.bug-badge[data-update-id="' + update.id + '"]')) {
            return;
        }

        const computed = window.getComputedStyle(targetElement);
        if (computed.position === 'static') {
            targetElement.style.position = 'relative';
        }

        targetElement.style.overflow = 'visible !important';

        if (targetElement.closest('#header, .app-header, header')) {
            let parent = targetElement.parentElement;
            while (parent && parent !== document.body) {
                parent.style.overflow = 'visible';
                if (parent.id === 'header' || parent.classList?.contains('app-header')) break;
                parent = parent.parentElement;
            }
        }

        const boxParent = targetElement.closest('.box, .box-body');
        if (boxParent) {
            boxParent.style.overflow = 'visible';
            if (boxParent.parentElement?.classList?.contains('box')) {
                boxParent.parentElement.style.overflow = 'visible';
            }
        }

        const isBug = update.category === 'bug';
        const badge = document.createElement('span');
        badge.className = isBug ? 'bug-badge' : 'new-update-badge';
        badge.setAttribute('data-update-id', update.id);
        badge.innerHTML = isBug ? 'BUG' : 'NEW';

        let bgColor = '#facc15';
        let textColor = '#713f12';

        if (isBug) {
            textColor = '#ffffff';
            const statusColors = { open: '#ef4444', in_progress: '#f59e0b', resolved: '#22c55e' };
            bgColor = statusColors[update.status] || '#ef4444';
            const statusLabels = { open: 'Not Started', in_progress: 'In Progress', resolved: 'Need Testing' };
            badge.title = statusLabels[update.status] || update.status || 'Bug';
        }

        badge.style.cssText = `position: absolute !important; top: -8px !important; right: -8px !important; padding: 2px 6px !important; font-size: 9px !important; font-weight: bold !important; color: ${textColor} !important; background: ${bgColor} !important; border-radius: 4px !important; box-shadow: 0 1px 3px rgba(0,0,0,0.2) !important; text-transform: uppercase !important; letter-spacing: 0.5px !important; transform: rotate(-12deg) !important; z-index: 9999 !important; cursor: help !important; pointer-events: auto !important; display: ${(isBug && !showBugBadges) ? 'none' : 'inline-block'} !important; line-height: 1 !important; overflow: visible !important;`;
        targetElement.appendChild(badge);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Only load and inject badges if user can view them (not candidate)
        if (canViewBadges) {
            loadAndInjectBadges();
            setTimeout(loadAndInjectBadges, 500);
            setTimeout(loadAndInjectBadges, 1500);
            setTimeout(loadAndInjectBadges, 3000);

            const observer = new MutationObserver(function(mutations) {
                let shouldReinject = false;
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && (mutation.attributeName === 'class' || mutation.attributeName === 'style')) {
                        shouldReinject = true;
                    }
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        shouldReinject = true;
                    }
                });
                if (shouldReinject) setTimeout(injectAllBadges, 50);
            });

            observer.observe(document.body, { attributes: true, childList: true, subtree: true, attributeFilter: ['class', 'style', 'hidden'] });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.hs-dropdown, .ti-dropdown, [data-bs-toggle="dropdown"], .header-element')) {
                    setTimeout(injectAllBadges, 100);
                    setTimeout(injectAllBadges, 300);
                }
            });
        }
    });

    if (canManage) {
        const contextMenu = document.getElementById('newUpdateContextMenu');
        const modal = document.getElementById('markAsNewModal');
        const bugModal = document.getElementById('markAsBugModal');

        function getSelector(el) {
            if (el.id) return '#' + el.id;
            let path = [];
            let current = el;

            while (current && current.nodeType === Node.ELEMENT_NODE) {
                let selector = current.nodeName.toLowerCase();
                if (current.id) { selector = '#' + current.id; path.unshift(selector); break; }
                if (current.dataset && current.dataset.id) { selector += `[data-id="${current.dataset.id}"]`; path.unshift(selector); break; }
                if (current.getAttribute('aria-label')) { selector += `[aria-label="${current.getAttribute('aria-label')}"]`; path.unshift(selector); break; }

                if (current.className && typeof current.className === 'string') {
                    const classes = current.className.trim().split(/\s+/).filter(c => !c.startsWith('hover:') && !c.startsWith('focus:') && !c.includes(':') && c !== 'active' && c !== 'open' && c !== 'show' && c !== 'hidden');
                    if (classes.length) {
                        const escapedClasses = classes.slice(0, 2).map(c => c.replace(/([!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~])/g, '\\$1'));
                        selector += '.' + escapedClasses.join('.');
                    }
                }

                const siblings = current.parentNode ? Array.from(current.parentNode.children).filter(e => e.nodeName === current.nodeName) : [];
                if (siblings.length > 1) { const index = siblings.indexOf(current) + 1; selector += `:nth-of-type(${index})`; }

                path.unshift(selector);
                current = current.parentNode;
                if (path.length > 5) break;
            }
            return path.join(' > ');
        }

        document.addEventListener('contextmenu', function(e) {
            if (e.ctrlKey) {
                e.preventDefault();
                targetElement = e.target;
                const menuWidth = 220, menuHeight = 280;
                let left = e.pageX, top = e.pageY;
                if (left + menuWidth > window.innerWidth) left = window.innerWidth - menuWidth - 10;
                if (top + menuHeight > window.innerHeight + window.scrollY) top = e.pageY - menuHeight;
                contextMenu.style.display = 'block';
                contextMenu.style.left = left + 'px';
                contextMenu.style.top = top + 'px';
                contextMenu.classList.remove('hidden');
                targetElement.style.outline = '2px dashed #facc15';
            }
        });

        document.addEventListener('click', function(e) {
            if (!contextMenu.contains(e.target)) {
                contextMenu.style.display = 'none';
                if (targetElement) targetElement.style.outline = '';
            }
        });

        document.getElementById('markAsNewBtn')?.addEventListener('click', function() {
            contextMenu.style.display = 'none';
            if (!targetElement) return;
            document.getElementById('newUpdateSelector').value = getSelector(targetElement);
            document.getElementById('newUpdateName').value = targetElement.textContent?.trim().substring(0, 50) || '';
            modal.classList.remove('hidden');
        });

        document.getElementById('markAsBugBtn')?.addEventListener('click', function() {
            contextMenu.style.display = 'none';
            if (!targetElement) return;
            document.getElementById('bugSelector').value = getSelector(targetElement);
            document.getElementById('bugName').value = targetElement.textContent?.trim().substring(0, 50) || '';
            bugModal.classList.remove('hidden');
            targetElement.style.outline = '2px dashed #ef4444';
        });

        document.getElementById('removeNewBtn')?.addEventListener('click', async function() {
            contextMenu.style.display = 'none';
            if (!targetElement) return;

            const showToast = (message, type) => { if (typeof Toast !== 'undefined') { new Toast().show(message, type, 2500); } };

            let badge = targetElement.querySelector('.new-update-badge') || targetElement.querySelector('.bug-badge');
            if (!badge) { const parent = targetElement.parentElement; if (parent) badge = parent.querySelector('.new-update-badge') || parent.querySelector('.bug-badge'); }

            if (badge) {
                const updateId = badge.getAttribute('data-update-id');
                if (updateId) {
                    try {
                        const res = await fetch(`/api/new-updates/${updateId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' } });
                        if (res.ok) { badge.remove(); newUpdatesCache = newUpdatesCache.filter(u => u.id != updateId); showToast('Badge removed successfully', 'success'); }
                        else showToast('Failed to remove badge', 'error');
                    } catch (e) { showToast('Error removing badge', 'error'); }
                } else { badge.remove(); showToast('Badge removed', 'success'); }
            } else {
                const selector = getSelector(targetElement);
                const matchingUpdate = newUpdatesCache.find(u => u.selector === selector);
                if (matchingUpdate) {
                    try {
                        const res = await fetch(`/api/new-updates/${matchingUpdate.id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' } });
                        if (res.ok) { newUpdatesCache = newUpdatesCache.filter(u => u.id != matchingUpdate.id); showToast('Badge removed successfully', 'success'); }
                        else showToast('Failed to remove badge', 'error');
                    } catch (e) { showToast('Error removing badge', 'error'); }
                } else showToast('No badge found on this element', 'error');
            }
            targetElement.style.outline = '';
        });

        document.getElementById('viewAllUpdatesBtn')?.addEventListener('click', function() { contextMenu.style.display = 'none'; window.location.href = '/new-updates'; });

        window.closeMarkAsNewModal = function() { modal.classList.add('hidden'); if (targetElement) targetElement.style.outline = ''; };
        window.closeMarkAsBugModal = function() { bugModal.classList.add('hidden'); if (targetElement) targetElement.style.outline = ''; };

        window.saveNewUpdate = async function() {
            const name = document.getElementById('newUpdateName').value.trim();
            const selector = document.getElementById('newUpdateSelector').value;
            if (!name) { if (typeof Toast !== 'undefined') new Toast().show('Please enter a feature name', 'error', 2500); return; }

            const existingBadge = newUpdatesCache.find(u => u.selector === selector);
            if (existingBadge) { if (typeof Toast !== 'undefined') new Toast().show('This element already has a badge', 'error', 2500); return; }

            const saveBtn = document.querySelector('#markAsNewModal button[onclick="saveNewUpdate()"]');
            const originalText = saveBtn?.innerHTML;
            if (saveBtn) { saveBtn.disabled = true; saveBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Saving...'; }

            const expiryDays = document.getElementById('newUpdateExpiry').value;
            let expiresAt = null;
            if (expiryDays) { const d = new Date(); d.setDate(d.getDate() + parseInt(expiryDays)); expiresAt = d.toISOString().split('T')[0]; }

            const isGlobalElement = selector.includes('#header') || selector.includes('.app-header') || selector.includes('.app-sidebar') || selector.includes('#sidebar') || (targetElement && (targetElement.closest('#header') || targetElement.closest('.app-header') || targetElement.closest('.app-sidebar')));

            try {
                const res = await fetch('/api/new-updates', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                    body: JSON.stringify({ name: name, selector: selector, url: isGlobalElement ? null : window.location.pathname, description: document.getElementById('newUpdateDesc').value, category: document.getElementById('newUpdateCategory').value, expires_at: expiresAt })
                });
                const data = await res.json();
                if (data.success) {
                    closeMarkAsNewModal();
                    if (targetElement) { injectBadge(targetElement, data.update); targetElement.style.outline = ''; }
                    if (typeof Toast !== 'undefined') new Toast().show('New update badge added', 'success', 2500);
                } else { if (typeof Toast !== 'undefined') new Toast().show(data.error || 'Failed to save', 'error', 2500); }
            } catch (e) { if (typeof Toast !== 'undefined') new Toast().show('Failed to save', 'error', 2500); }
            finally { if (saveBtn) { saveBtn.disabled = false; saveBtn.innerHTML = originalText; } }
        };

        window.saveBug = async function() {
            const name = document.getElementById('bugName').value.trim();
            const selector = document.getElementById('bugSelector').value;
            if (!name) { if (typeof Toast !== 'undefined') new Toast().show('Please enter a bug title', 'error', 2500); return; }

            const existingBadge = newUpdatesCache.find(u => u.selector === selector);
            if (existingBadge) { if (typeof Toast !== 'undefined') new Toast().show('This element already has a badge', 'error', 2500); return; }

            const saveBtn = document.querySelector('#markAsBugModal button[onclick="saveBug()"]');
            const originalText = saveBtn?.innerHTML;
            if (saveBtn) { saveBtn.disabled = true; saveBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Saving...'; }

            const isGlobalElement = selector.includes('#header') || selector.includes('.app-header') || selector.includes('.app-sidebar') || selector.includes('#sidebar') || (targetElement && (targetElement.closest('#header') || targetElement.closest('.app-header') || targetElement.closest('.app-sidebar')));

            try {
                const res = await fetch('/api/new-updates', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                    body: JSON.stringify({ name: name, selector: selector, url: isGlobalElement ? null : window.location.pathname, description: document.getElementById('bugDesc').value, category: 'bug', priority: document.getElementById('bugPriority').value, status: document.getElementById('bugStatus').value, expires_at: null })
                });
                const data = await res.json();
                if (data.success) {
                    closeMarkAsBugModal();
                    if (targetElement) { injectBadge(targetElement, { ...data.update, category: 'bug' }); targetElement.style.outline = ''; }
                    if (typeof Toast !== 'undefined') new Toast().show('Bug badge added', 'success', 2500);
                } else { if (typeof Toast !== 'undefined') new Toast().show(data.error || 'Failed to save bug', 'error', 2500); }
            } catch (e) { if (typeof Toast !== 'undefined') new Toast().show('Failed to save bug', 'error', 2500); }
            finally { if (saveBtn) { saveBtn.disabled = false; saveBtn.innerHTML = originalText; } }
        };

        const quickEditModal = document.getElementById('quickEditBugModal');
        let currentEditingBugId = null;

        window.closeQuickEditBugModal = function() { quickEditModal.classList.add('hidden'); if (targetElement) targetElement.style.outline = ''; };

        window.openQuickEditBugModal = function(bugId, bugName) {
            currentEditingBugId = bugId;
            document.getElementById('quickEditBugId').value = bugId;
            document.getElementById('quickEditBugName').textContent = bugName || 'Bug';
            const bug = newUpdatesCache.find(b => b.id == bugId);
            document.querySelectorAll('.quick-status-btn').forEach(btn => {
                btn.classList.remove('bg-blue-50', 'border-blue-300');
                if (bug && btn.dataset.status === bug.status) btn.classList.add('bg-blue-50', 'border-blue-300');
            });
            quickEditModal.classList.remove('hidden');
        };

        window.setQuickBugStatus = async function(status) {
            const bugId = document.getElementById('quickEditBugId').value;
            if (!bugId) return;

            const clickedBtn = document.querySelector(`.quick-status-btn[data-status="${status}"]`);
            const allBtns = document.querySelectorAll('.quick-status-btn');
            allBtns.forEach(btn => btn.disabled = true);
            const originalText = clickedBtn?.innerHTML;
            if (clickedBtn) clickedBtn.innerHTML = '<span class="w-3 h-3 rounded-full bg-gray-400 animate-pulse"></span><span class="font-medium">Saving...</span>';

            try {
                const res = await fetch(`/api/new-updates/${bugId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                    body: JSON.stringify({ status: status })
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    closeQuickEditBugModal();
                    const bug = newUpdatesCache.find(b => b.id == bugId);
                    if (bug) bug.status = status;
                    const badge = document.querySelector(`[data-update-id="${bugId}"]`);
                    if (badge) {
                        const statusLabels = { open: 'Not Started', in_progress: 'In Progress', resolved: 'Need Testing' };
                        const statusColors = { open: '#ef4444', in_progress: '#f59e0b', resolved: '#22c55e' };
                        badge.title = statusLabels[status] || status;
                        badge.style.background = statusColors[status] || '#ef4444';
                    }
                    if (typeof Toast !== 'undefined') { const statusLabels = { open: 'Not Started', in_progress: 'In Progress', resolved: 'Need Testing' }; new Toast().show(`Status updated to: ${statusLabels[status]}`, 'success', 2500); }
                } else { if (typeof Toast !== 'undefined') new Toast().show(data.error || 'Failed to update status', 'error', 2500); }
            } catch (e) { if (typeof Toast !== 'undefined') new Toast().show('Failed to update status', 'error', 2500); }
            finally { allBtns.forEach(btn => btn.disabled = false); if (clickedBtn && originalText) clickedBtn.innerHTML = originalText; }
        };

        document.getElementById('editBugStatusBtn')?.addEventListener('click', function() {
            contextMenu.style.display = 'none';
            if (!targetElement) return;
            let badge = targetElement.querySelector('.bug-badge');
            if (!badge) { const parent = targetElement.parentElement; if (parent) badge = parent.querySelector('.bug-badge'); }
            if (badge) { const bugId = badge.getAttribute('data-update-id'); const bug = newUpdatesCache.find(b => b.id == bugId); openQuickEditBugModal(bugId, bug?.name); }
            else { if (typeof Toast !== 'undefined') new Toast().show('No bug badge found on this element', 'error', 2500); }
        });

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'e') {
                e.preventDefault();
                const hoveredBadge = document.querySelector('.bug-badge:hover');
                if (hoveredBadge) { const bugId = hoveredBadge.getAttribute('data-update-id'); const bug = newUpdatesCache.find(b => b.id == bugId); openQuickEditBugModal(bugId, bug?.name); return; }
                const pageBugs = newUpdatesCache.filter(u => u.category === 'bug');
                if (pageBugs.length === 1) openQuickEditBugModal(pageBugs[0].id, pageBugs[0].name);
                else if (pageBugs.length > 1) { const bugNames = pageBugs.map((b, i) => `${i + 1}. ${b.name}`).join('\n'); const choice = prompt(`Multiple bugs on this page. Enter number:\n${bugNames}`); if (choice) { const idx = parseInt(choice) - 1; if (pageBugs[idx]) openQuickEditBugModal(pageBugs[idx].id, pageBugs[idx].name); } }
            }
        });
    }
})();
</script>

<style>
#newUpdateContextMenu { animation: fadeIn 0.15s ease; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
.box:has(.bug-badge), .box:has(.new-update-badge), .box-body:has(.bug-badge), .box-body:has(.new-update-badge) { overflow: visible !important; }
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-spin { animation: spin 1s linear infinite; display: inline-block; }
</style>
