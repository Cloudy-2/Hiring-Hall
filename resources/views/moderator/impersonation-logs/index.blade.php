<x-app-layout page-title="Impersonation Logs" :breadcrumbs="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
    ]"
    active="Impersonation Logs">
    <x-modern-header chip="Audit Trail" title="Impersonation Logs" desc="Audit trail of staff impersonating users.">
        <x-slot:div>
            <a href="{{ route('admin.impersonation-logs.export') }}" class="ti-btn ti-btn-soft-success">
                <i class="bi bi-download"></i> Export CSV
            </a>
        </x-slot:div>
    </x-modern-header>

    <div class="mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="box">
            <div class="box-body">
                <div class="overflow-x-auto">
                    @if ($logs->isEmpty())
                        <div
                            class="rounded-lg border border-defaultborder dark:border-defaultborder/10 bg-gray-50 dark:bg-white/5 p-8 text-center">
                            <i class="bi bi-person-badge text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="text-gray-600 dark:text-gray-300">No impersonation logs yet.</p>
                        </div>
                    @else
                        <table class="table min-w-full">
                            <thead
                                class="border-b border-defaultborder dark:border-defaultborder/10 bg-gray-50 dark:bg-white/5">
                                <tr>
                                    <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Started</th>
                                    <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Ended</th>
                                    <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Impersonator</th>
                                    <th class="px-3 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        Target user</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr
                                        class="border-b border-defaultborder dark:border-defaultborder/10 hover:bg-gray-50 dark:hover:bg-white/5">
                                        <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $log->started_at->format('M j, Y g:i A') }}</td>
                                        <td class="px-3 py-2 text-sm text-textmuted">
                                            {{ $log->ended_at ? $log->ended_at->format('M j, Y g:i A') : '—' }}</td>
                                        <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $log->impersonator->name ?? '—' }} <span
                                                class="text-textmuted">({{ $log->impersonator->email ?? '—' }})</span></td>
                                        <td class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $log->targetUser->name ?? '—' }} <span
                                                class="text-textmuted">({{ $log->targetUser->email ?? '—' }})</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>