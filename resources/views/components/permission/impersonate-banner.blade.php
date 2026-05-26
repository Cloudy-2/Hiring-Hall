@if (session('impersonator_email'))
<div class="alert alert-primary fade show custom-alert-icon shadow-sm flex items-center justify-center fixed top-0 left-0 right-0 z-[9999] rounded-none border-0" role="alert" id="impersonate-banner">
    <div class="flex items-center gap-3">
        <span class="badge bg-primary/20 text-primary px-2 py-1 rounded text-xs font-semibold">
            <i class="bi bi-incognito me-1"></i>Impersonate Mode
        </span>
        <span class="text-primary">You are logged in as <strong>{{ Auth::user()->name }}</strong></span>
        <form method="POST" action="{{ route('moderator.stop-impersonating') }}" class="inline ms-3">
            @csrf
            <button type="submit" class="ti-btn ti-btn-primary ti-btn-sm">
                <i class="bi bi-box-arrow-left me-1"></i>
                Return to Your Account
            </button>
        </form>
    </div>
</div>
<div id="impersonate-spacer" class="h-12"></div>
@endif
