@php
    $message = session('success') ?? session('status');
@endphp

@if ($message)
    <div id="flash-success-fallback" class="alert alert-success alert-dismissible fade show mx-3 mb-4 hidden" role="alert">
        <i class="ri-checkbox-circle-line me-1"></i>
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.Swal) {
                var isDark = document.documentElement.classList.contains('dark') ||
                    document.body.classList.contains('dark-theme') ||
                    document.documentElement.getAttribute('data-theme-mode') === 'dark';

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: @json($message),
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: isDark ? '#0f172a' : '#ffffff',
                    color: isDark ? '#e2e8f0' : '#334155',
                    customClass: {
                        popup: 'rounded-xl border-0 shadow-lg',
                        title: 'text-sm font-semibold'
                    },
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            } else {
                const fallback = document.getElementById('flash-success-fallback');
                if (fallback) {
                    fallback.classList.remove('hidden');
                }
            }
        });
    </script>
@endif