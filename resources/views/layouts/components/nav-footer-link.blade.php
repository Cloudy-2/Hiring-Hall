<!-- Scroll To Top -->
@php
    $defaultMenuVersion = @filemtime(public_path('assets/js/defaultmenu.min.js')) ?: time();
    $customJsVersion = @filemtime(public_path('assets/js/custom.js')) ?: time();
@endphp

<div class="scrollToTop rounded-full" style="display: none;">
    <span class="arrow"><i class="ti ti-arrow-narrow-up text-xl"></i></span>
</div>
<div id="responsive-overlay"></div>
<!-- Scroll To Top -->

<!-- Switch JS -->
<script src="{{ asset('assets/js/switch.js') }}"></script>

<!-- Popper JS -->
<script src="{{ asset('assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

<!-- Preline JS - wrapped to catch errors -->
<script src="{{ asset('assets/libs/preline/preline.js') }}"></script>
<script>
    // Safely re-init preline to prevent errors from breaking other scripts
    try {
        if (window.HSStaticMethods && window.HSStaticMethods.autoInit) {
            // Already initialized by the script itself
        }
    } catch (e) {
        console.warn('Preline init warning:', e.message);
    }
</script>

<!-- Defaultmenu JS -->
<script>
    if (typeof window.horizontalClickFn !== 'function') {
        window.horizontalClickFn = function () {
            const html = document.documentElement;
            html.setAttribute('data-nav-layout', 'horizontal');
            html.setAttribute('data-menu-version', 'v2');
            html.removeAttribute('data-nav-style');
            html.removeAttribute('data-vertical-style');
            html.removeAttribute('data-toggled');

            if (typeof window.syncHeaderActions === 'function') {
                window.syncHeaderActions();
            }
        };
    }
</script>
<script src="{{ asset('assets/js/defaultmenu.min.js') }}?v={{ $defaultMenuVersion }}"></script>

<!-- Node Waves JS -->
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<!-- Sticky JS -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>

<!-- Simplebar JS -->
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>

<!-- Auto Complete JS -->
<script src="{{ asset('assets/libs/@tarekraafat/autocomplete.js/autoComplete.min.js') }}"></script>

<!-- Color Picker JS -->
<script src="{{ asset('assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

<!-- Date & Time Picker JS -->
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

<!-- Custom-Switcher JS -->
{{--
<script src="{{ asset('assets/js/custom-switcher.min.js') }}"></script> --}}

<!-- Custom JS -->
<script src="{{ asset('assets/js/custom.js') }}?v={{ $customJsVersion }}"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /*
    document.addEventListener("DOMContentLoaded", function () {
        const mobileNavToggleBtn = document.querySelector('.sidemenu-toggle');

        if (mobileNavToggleBtn) {
            // Add the event listener for manual toggle only
            mobileNavToggleBtn.addEventListener('click', function () {
                document.body.classList.toggle('mobile-nav-active');

                const sidebar = document.getElementById('sidebar');
                const page = document.querySelector('.page');

                if (sidebar) sidebar.classList.toggle('collapsed');
                if (page) page.classList.toggle('sidebar-collapsed');
            });
        }
    });
    */
</script>
