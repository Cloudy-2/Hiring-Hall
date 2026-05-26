{{-- Mobile Navigation Scripts --}}
<script>
    // Mobile Navigation
    window.openMobileNav = function() {
        const sidemenu = document.querySelector('.chat-v2-sidemenu');
        const sidebar = document.querySelector('.chat-v2-sidebar');
        const overlay = document.getElementById('mobile-overlay');
        
        sidemenu?.classList.add('mobile-open');
        sidebar?.classList.add('mobile-open');
        overlay?.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    window.closeMobileNav = function() {
        const sidemenu = document.querySelector('.chat-v2-sidemenu');
        const sidebar = document.querySelector('.chat-v2-sidebar');
        const overlay = document.getElementById('mobile-overlay');
        
        sidemenu?.classList.remove('mobile-open');
        sidebar?.classList.remove('mobile-open');
        overlay?.classList.remove('active');
        document.body.style.overflow = '';
    };

    const initMobileNav = () => {
        const menuBtn = document.getElementById('mobile-menu-btn');
        const overlay = document.getElementById('mobile-overlay');

        menuBtn?.addEventListener('click', window.openMobileNav);
        overlay?.addEventListener('click', window.closeMobileNav);

        document.addEventListener('click', (e) => {
            if (!e.target || typeof e.target.closest !== 'function') return;
            if (window.innerWidth >= 768) return;
            
            const sidemenuEl = e.target.closest('.chat-v2-sidemenu a, .chat-v2-sidemenu button');
            if (sidemenuEl) setTimeout(window.closeMobileNav, 150);
            
            const sidebarLink = e.target.closest('.chat-v2-sidebar a');
            if (sidebarLink) window.closeMobileNav();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') window.closeMobileNav();
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) window.closeMobileNav();
        });
    };

    const initMobileDropdown = () => {
        const moreBtn = document.getElementById('mobile-more-btn');
        const moreMenu = document.getElementById('mobile-more-menu');

        if (!moreBtn || !moreMenu) return;

        moreBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            moreMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!e.target) return;
            if (!moreMenu.contains(e.target) && e.target !== moreBtn) {
                moreMenu.classList.add('hidden');
            }
        });

        moreMenu.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => moreMenu.classList.add('hidden'));
        });
    };

    document.addEventListener('DOMContentLoaded', initMobileNav);
    document.addEventListener('DOMContentLoaded', initMobileDropdown);
</script>
