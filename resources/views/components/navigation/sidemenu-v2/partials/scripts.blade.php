{{-- Sidemenu V2 - Premium Interactive Scripts --}}
<script>
    (function () {
        const html = document.documentElement;
        const SIDEMENU_STATE_KEY = 'sidemenu-v2-state';

        const getCloseState = () => {
            const currentStyle = html.getAttribute('data-vertical-style');
            const navStyle = html.getAttribute('data-nav-style');
            if (currentStyle === 'overlay') {
                return 'icon-overlay-close';
            }
            if (currentStyle === 'closed') {
                return 'close-menu-close';
            }
            if (currentStyle === 'detached') {
                return 'detached-close';
            }
            if (currentStyle === 'icontext') {
                return 'icon-text-close';
            }
            if (navStyle === 'icon-click') {
                return 'icon-click-closed';
            }
            if (navStyle === 'icon-hover') {
                return 'icon-hover-closed';
            }
            if (navStyle === 'menu-hover') {
                return 'menu-hover-closed';
            }

            return 'close';
        };

        const closeStates = new Set([
            'close',
            'close-menu-close',
            'icon-overlay-close',
            'detached-close',
            'icon-text-close',
            'menu-hover-closed',
            'icon-click-closed',
            'icon-hover-closed',
            'double-menu-close',
        ]);

        const persistSidemenuState = (isClosed) => {
            try {
                localStorage.setItem(SIDEMENU_STATE_KEY, isClosed ? 'closed' : 'open');
            } catch (e) {
                // Ignore storage errors (private mode / storage disabled)
            }
        };

        const restoreSidemenuState = () => {
            let storedState = null;
            try {
                storedState = localStorage.getItem(SIDEMENU_STATE_KEY);
            } catch (e) {
                storedState = null;
            }

            if (storedState === 'closed') {
                html.setAttribute('data-toggled', getCloseState());
            } else if (storedState === 'open' && closeStates.has(html.getAttribute('data-toggled'))) {
                html.removeAttribute('data-toggled');
            }
        };

        const enforceStoredClosedState = () => {
            let storedState = null;
            try {
                storedState = localStorage.getItem(SIDEMENU_STATE_KEY);
            } catch (e) {
                storedState = null;
            }

            if (storedState !== 'closed') {
                return;
            }

            if (window.innerWidth < 992) {
                return;
            }

            const closeState = getCloseState();
            if (html.getAttribute('data-toggled') !== closeState) {
                html.setAttribute('data-toggled', closeState);
            }
        };

        // Ensure correct layout but let theme decide menu styles
        // When Top Header (menu-version=v2) is active, we don't want the sidebar logic
        // to force a vertical layout (it can cause the sidebar to animate in).
        const menuVersion =
            localStorage.getItem('menu-version-style') ||
            html.getAttribute('data-menu-version') ||
            'v1';
        if (menuVersion !== 'v2') {
            html.setAttribute('data-nav-layout', 'vertical');
        }

        restoreSidemenuState();
        // Vendor menu bootstrap may clear data-toggled for default style, so re-apply once after it initializes.
        setTimeout(enforceStoredClosedState, 350);
        window.addEventListener('load', () => setTimeout(enforceStoredClosedState, 120));
        // html.setAttribute('data-menu-styles', 'dark'); // Removed to fix light mode visibility

        // Disable annoying template hover expansion functions globally
        const originalSetAttribute = html.setAttribute.bind(html);
        html.setAttribute = function (name, value) {
            if ((name === 'data-icon-overlay' || name === 'data-icon-text') && value === 'open') {
                return; // Silently block hover expansion
            }
            originalSetAttribute(name, value);
        };

        // Create and manage backdrop for overlay mode
        const ensureBackdrop = () => {
            let backdrop = document.getElementById('sidemenu-v2-backdrop');
            if (!backdrop) {
                backdrop = document.createElement('div');
                backdrop.id = 'sidemenu-v2-backdrop';
                backdrop.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-[102] hidden opacity-0 transition-opacity duration-300';
                backdrop.addEventListener('click', () => {
                    if (typeof window.toggleSidemenuV2 === 'function') {
                        window.toggleSidemenuV2();
                    }
                });
                document.body.appendChild(backdrop);
            }
            return backdrop;
        };

        const toggleBackdrop = (show) => {
            const backdrop = ensureBackdrop();
            if (show) {
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.add('opacity-100'), 10);
            } else {
                backdrop.classList.remove('opacity-100');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            }
        };

        // Dedicated custom toggle for the new inner button
        window.toggleSidemenuV2 = function () {
            const currentToggled = html.getAttribute('data-toggled');
            const vStyle = html.getAttribute('data-vertical-style');
            const closeState = getCloseState();

            // If it is closed, we open it by removing data-toggled
            if (closeStates.has(currentToggled)) {
                html.removeAttribute('data-toggled');
                persistSidemenuState(false);
                if (vStyle === 'overlay') toggleBackdrop(true);
            } else {
                html.setAttribute('data-toggled', closeState);
                persistSidemenuState(true);
                if (vStyle === 'overlay') toggleBackdrop(false);
            }

            // Re-run tooltips to accommodate the new state
            setTimeout(() => window.dispatchEvent(new Event('resize')), 200);

            // Keep persisted state authoritative after any external data-toggled mutations.
            setTimeout(enforceStoredClosedState, 250);
        };

        // Global Sync: Ensure any burger menu anywhere on the page calls our custom toggle
        if (!window.__sidemenuV2ClickBound) {
            document.addEventListener('click', function (e) {
                const toggle = e.target.closest('.sidemenu-toggle');
                if (!toggle) {
                    return;
                }

                e.preventDefault();
                e.stopImmediatePropagation();
                window.toggleSidemenuV2();
            }, true);

            window.__sidemenuV2ClickBound = true;
        }

        function initSidebar() {
            const sidebar = document.querySelector('.app-sidebar');
            if (!sidebar) return;

            // 1. Accordion Logic (delegated so dynamically morphed doublemenu items also work)
            if (!sidebar.dataset.sbAccordionBound) {
                sidebar.addEventListener('click', function (e) {
                    const trigger = e.target.closest('.has-sub > .side-menu__item');
                    if (!trigger || !sidebar.contains(trigger)) {
                        return;
                    }

                    // Disable manual accordion toggle in icon-hover mode to let template handle it
                    const navStyle = html.getAttribute('data-nav-style');
                    if (navStyle === 'icon-hover') {
                        return;
                    }

                    e.preventDefault();
                    e.stopImmediatePropagation();

                    const parent = trigger.parentElement;
                    if (!parent) {
                        return;
                    }

                    // If we're already opening, don't do it again
                    if (e.detail > 1) {
                        return;
                    }

                    // Close other open submenus at the same level
                    const siblings = parent.parentElement?.querySelectorAll(':scope > .has-sub.show') || [];
                    siblings.forEach(sib => {
                        if (sib !== parent) {
                            sib.classList.remove('show');
                        }
                    });

                    // Toggle current
                    parent.classList.toggle('show');
                });

                sidebar.dataset.sbAccordionBound = 'true';
            }

            // 2. Search Logic
            const searchInput = document.getElementById('sidebar-search');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase();
                    const menu = sidebar.querySelector('.main-menu');
                    const elements = Array.from(menu.children);

                    // Check if any item matches the query
                    const hasMatch = elements.some(el => {
                        if (el.classList.contains('slide')) {
                            const label = el.querySelector('.side-menu__label')?.textContent.toLowerCase() || '';
                            const subLabels = Array.from(el.querySelectorAll('.slide-menu .side-menu__label'))
                                .map(sl => sl.textContent.toLowerCase());
                            return label.includes(query) || subLabels.some(sl => sl.includes(query));
                        }
                        return false;
                    });

                    // If no matches found and query is not empty, we treat it as an empty query to show all menus
                    const effectiveQuery = (query !== '' && !hasMatch) ? '' : query;

                    let lastCategory = null;
                    let categoryHasVisibleItems = false;

                    // First pass: Hide/Show items and track categories
                    elements.forEach(el => {
                        if (el.classList.contains('slide__category')) {
                            if (lastCategory && !categoryHasVisibleItems) {
                                lastCategory.style.setProperty('display', 'none', 'important');
                            }
                            lastCategory = el;
                            categoryHasVisibleItems = false;
                            el.style.setProperty('display', effectiveQuery === '' ? 'flex' : 'none', 'important');
                        } else if (el.classList.contains('slide')) {
                            const label = el.querySelector('.side-menu__label')?.textContent.toLowerCase() || '';
                            const subLabels = Array.from(el.querySelectorAll('.slide-menu .side-menu__label'))
                                .map(sl => sl.textContent.toLowerCase());

                            const isMatch = label.includes(effectiveQuery) || subLabels.some(sl => sl.includes(effectiveQuery));

                            el.style.setProperty('display', isMatch ? 'block' : 'none', 'important');

                            if (isMatch) {
                                categoryHasVisibleItems = true;
                                if (lastCategory) lastCategory.style.setProperty('display', 'flex', 'important');

                                // Open if matching sub-item
                                if (effectiveQuery.length > 0 && subLabels.some(sl => sl.includes(effectiveQuery))) {
                                    el.classList.add('show');
                                }
                            }
                        }
                    });

                    // Final check for the last category in the list
                    if (lastCategory && !categoryHasVisibleItems && effectiveQuery !== '') {
                        lastCategory.style.setProperty('display', 'none', 'important');
                    } else if (lastCategory && effectiveQuery === '') {
                        lastCategory.style.setProperty('display', 'flex', 'important');
                    }
                });
            }

            // 3. Tooltips (enabled only for icon-only modes)
            const isIconOnlyMode = () => {
                const verticalStyle = html.getAttribute('data-vertical-style');
                const navStyle = html.getAttribute('data-nav-style');
                const toggled = html.getAttribute('data-toggled');

                return (
                    (verticalStyle === 'icontext' && toggled === 'icon-text-close') ||
                    (navStyle === 'icon-click' && toggled === 'icon-click-closed') ||
                    (navStyle === 'icon-hover' && toggled === 'icon-hover-closed')
                );
            };

            const syncSidemenuTooltips = () => {
                const menuItems = sidebar.querySelectorAll('.side-menu__item');

                menuItems.forEach(el => {
                    if (window.bootstrap && window.bootstrap.Tooltip) {
                        const existing = window.bootstrap.Tooltip.getInstance(el);
                        if (existing) {
                            existing.dispose();
                        }
                    }

                    if (!isIconOnlyMode()) {
                        el.removeAttribute('data-bs-toggle');
                        el.removeAttribute('data-bs-placement');
                        el.removeAttribute('data-bs-original-title');
                        el.removeAttribute('title');
                        return;
                    }

                    const labelText = el.querySelector('.side-menu__label')?.textContent?.trim() || '';
                    if (!labelText) {
                        return;
                    }

                    el.setAttribute('title', labelText);
                    el.setAttribute('data-bs-toggle', 'tooltip');
                    el.setAttribute('data-bs-placement', 'right');

                    if (window.bootstrap && window.bootstrap.Tooltip) {
                        new window.bootstrap.Tooltip(el, {
                            container: 'body',
                            trigger: 'hover focus',
                        });
                    }
                });
            };

            const tooltipModeObserver = new MutationObserver(mutations => {
                const hasTooltipModeChange = mutations.some(mutation =>
                    mutation.type === 'attributes' &&
                    ['data-vertical-style', 'data-nav-style', 'data-toggled'].includes(mutation.attributeName)
                );

                if (hasTooltipModeChange) {
                    syncSidemenuTooltips();
                }
            });

            tooltipModeObserver.observe(html, {
                attributes: true,
                attributeFilter: ['data-vertical-style', 'data-nav-style', 'data-toggled'],
            });

            syncSidemenuTooltips();

            // 3. Active Menu Highlighting
            const currentPath = window.location.pathname.replace(/\/+$/, "") || "/";
            const allLinks = sidebar.querySelectorAll('.side-menu__item');

            // Step 1: Collect all hrefs to find the best (longest/most specific) match
            let bestMatch = null;
            let bestMatchLength = 0;

            allLinks.forEach(link => {
                const href = (link.getAttribute('href') || "").split('?')[0].replace(/\/+$/, "");
                if (!href || href === '#' || href === 'javascript:void(0);') return;

                if (currentPath === href) {
                    // Exact match is highest priority
                    if (href.length > bestMatchLength) {
                        bestMatch = link;
                        bestMatchLength = href.length;
                    }
                } else if (href !== "/" && currentPath.startsWith(href + "/")) {
                    // Partial prefix match — only use if longer than current best
                    if (href.length > bestMatchLength) {
                        bestMatch = link;
                        bestMatchLength = href.length;
                    }
                }
            });

            // Step 2: Apply active class to the SINGLE best match only
            if (bestMatch && !bestMatch.classList.contains('active-menu')) {
                bestMatch.classList.add('active-menu');
                let parent = bestMatch.closest('.has-sub');
                while (parent) {
                    parent.classList.add('show', 'active');
                    parent = parent.parentElement.closest('.has-sub');
                }
            }

            // 5. Draggable Sidebar Logic (for overlay mode)
            const initDraggableSidebar = () => {
                if (html.getAttribute('data-vertical-style') !== 'overlay') return;

                const dragHandle = sidebar.querySelector('.main-sidebar-header');
                const profileHandle = sidebar.querySelector('.sb-profile');
                let isDragging = false;
                let startX, startY, initialX, initialY;

                const onStart = (e) => {
                    // Only drag if not clicking on nested items (like toggle button or logo link)
                    if (e.target.closest('a') || e.target.closest('button')) return;

                    isDragging = true;
                    const clientX = e.clientX || e.touches[0].clientX;
                    const clientY = e.clientY || e.touches[0].clientY;

                    startX = clientX;
                    startY = clientY;

                    const style = window.getComputedStyle(sidebar);
                    const matrix = new WebKitCSSMatrix(style.transform);
                    initialX = matrix.m41 || 0;
                    initialY = matrix.m42 || 0;

                    sidebar.style.transition = 'none';
                    sidebar.classList.add('dragging');
                    document.body.style.cursor = 'grabbing';
                };

                const onMove = (e) => {
                    if (!isDragging) return;
                    e.preventDefault();

                    const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                    const clientY = e.clientY || (e.touches && e.touches[0].clientY);

                    const dx = clientX - startX;
                    const dy = clientY - startY;

                    sidebar.style.transform = `translate(${initialX + dx}px, ${initialY + dy}px)`;
                };

                const onEnd = () => {
                    if (!isDragging) return;
                    isDragging = false;
                    sidebar.style.transition = '';
                    sidebar.classList.remove('dragging');
                    document.body.style.cursor = '';
                };

                [dragHandle, profileHandle].forEach(h => {
                    if (!h) return;
                    h.addEventListener('mousedown', onStart);
                    h.addEventListener('touchstart', onStart, { passive: false });
                });

                document.addEventListener('mousemove', onMove);
                document.addEventListener('touchmove', onMove, { passive: false });
                document.addEventListener('mouseup', onEnd);
                document.addEventListener('touchend', onEnd);
            };

            initDraggableSidebar();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebar);
        } else {
            initSidebar();
        }
    })();
</script>