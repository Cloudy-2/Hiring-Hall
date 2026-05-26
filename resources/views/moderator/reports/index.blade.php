<x-app-layout page-title="Reports & Analytics">
    <x-slot name="url_1">{"link": "/admin/dashboard", "text": "Admin"}</x-slot>
    <x-slot name="active">Reports & Analytics</x-slot>

    <x-modern-header chip="Analytics" title="Reports & Analytics"
        desc="Overview of system metrics and key performance indicators.">
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        {{-- Overview Stats --}}
        <div class="col-span-12">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <div
                    class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800">
                    <p class="text-sm font-medium text-textmuted">Total Users</p>
                    <p class="text-2xl font-bold text-primary">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800">
                    <p class="text-sm font-medium text-textmuted">Applicants</p>
                    <p class="text-2xl font-bold text-success">{{ number_format($stats['total_applicants']) }}</p>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800">
                    <p class="text-sm font-medium text-textmuted">Employers</p>
                    <p class="text-2xl font-bold text-info">{{ number_format($stats['total_employers']) }}</p>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800">
                    <p class="text-sm font-medium text-textmuted">Total Jobs</p>
                    <p class="text-2xl font-bold text-warning">{{ number_format($stats['total_jobs']) }}</p>
                    <p class="text-xs text-textmuted">{{ number_format($stats['active_jobs']) }} active</p>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800">
                    <p class="text-sm font-medium text-textmuted">Applications</p>
                    <p class="text-2xl font-bold text-secondary">{{ number_format($stats['total_applications']) }}</p>
                </div>
            </div>
        </div>

        {{-- Period Filter --}}
        <div class="col-span-12">
            <div class="flex items-center justify-between">
                <h5 class="font-semibold">Trends & Analytics</h5>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-textmuted">Period:</span>
                    <select id="period-filter" class="form-control form-control-sm w-auto"
                        onchange="window.location.href='?period=' + this.value">
                        <option value="7" {{ $period == '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="30" {{ $period == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ $period == '90' ? 'selected' : '' }}>Last 90 days</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- User Registration Trends --}}
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">User Registration Trends</div>
                </div>
                <div class="box-body">
                    <canvas id="registration-chart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Company Verification --}}
        <div class="xl:col-span-4 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Company Verification</div>
                </div>
                <div class="box-body">
                    <canvas id="company-chart" height="200"></canvas>
                    <div class="mt-4 text-center">
                        <p class="text-2xl font-bold text-success">{{ $companyData['verification_rate'] }}%</p>
                        <p class="text-sm text-textmuted">Verification Rate</p>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                        <div>
                            <p class="font-semibold text-success">{{ $companyData['by_status']['approved'] ?? 0 }}</p>
                            <p class="text-xs text-textmuted">Approved</p>
                        </div>
                        <div>
                            <p class="font-semibold text-warning">{{ $companyData['by_status']['pending'] ?? 0 }}</p>
                            <p class="text-xs text-textmuted">Pending</p>
                        </div>
                        <div>
                            <p class="font-semibold text-danger">{{ $companyData['by_status']['rejected'] ?? 0 }}</p>
                            <p class="text-xs text-textmuted">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Job Posting Trends --}}
        <div class="xl:col-span-6 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Job Postings Over Time</div>
                </div>
                <div class="box-body">
                    <canvas id="jobs-chart" height="150"></canvas>
                </div>
            </div>
        </div>

        {{-- Application Stats --}}
        <div class="xl:col-span-6 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Application Status Distribution</div>
                </div>
                <div class="box-body">
                    <canvas id="application-chart" height="150"></canvas>
                    <div class="mt-4 text-center">
                        <p class="text-2xl font-bold text-success">{{ $applicationData['conversion_rate'] }}%</p>
                        <p class="text-sm text-textmuted">Hire Conversion Rate</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Job Categories --}}
        <div class="xl:col-span-6 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Top Job Categories</div>
                </div>
                <div class="box-body">
                    @if($jobPostingData['by_category']->isEmpty())
                        <p class="text-textmuted text-center py-4">No job category data available.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($jobPostingData['by_category'] as $category)
                                @php
                                    $percentage = $stats['total_jobs'] > 0 ? ($category->count / $stats['total_jobs']) * 100 : 0;
                                @endphp
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>{{ $category->category }}</span>
                                        <span class="text-textmuted">{{ $category->count }}
                                            ({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Export Options --}}
        <div class="xl:col-span-6 col-span-12">
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Export Data</div>
                </div>
                <div class="box-body">
                    <p class="text-textmuted mb-4">Download reports as CSV files for further analysis.</p>
                    <div class="grid grid-cols-3 gap-4">
                        <a href="{{ route('moderator.reports.export', ['type' => 'users']) }}"
                            class="ti-btn ti-btn-primary-full w-full">
                            <i class="ri-user-line me-1"></i> Users
                        </a>
                        <a href="{{ route('moderator.reports.export', ['type' => 'jobs']) }}"
                            class="ti-btn ti-btn-info-full w-full">
                            <i class="ri-briefcase-line me-1"></i> Jobs
                        </a>
                        <a href="{{ route('moderator.reports.export', ['type' => 'companies']) }}"
                            class="ti-btn ti-btn-success-full w-full">
                            <i class="ri-building-line me-1"></i> Companies
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Registration Chart
                new Chart(document.getElementById('registration-chart'), {
                    type: 'line',
                    data: {
                        labels: @json($registrationData['labels']),
                        datasets: [
                            {
                                label: 'Applicants',
                                data: @json($registrationData['applicants']),
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Employers',
                                data: @json($registrationData['employers']),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // Jobs Chart
                new Chart(document.getElementById('jobs-chart'), {
                    type: 'bar',
                    data: {
                        labels: @json($jobPostingData['trend']['labels']),
                        datasets: [{
                            label: 'Job Postings',
                            data: @json($jobPostingData['trend']['counts']),
                            backgroundColor: 'rgba(245, 158, 11, 0.8)',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // Company Chart
                new Chart(document.getElementById('company-chart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Approved', 'Pending', 'Rejected'],
                        datasets: [{
                            data: [
                                {{ $companyData['by_status']['approved'] ?? 0 }},
                                {{ $companyData['by_status']['pending'] ?? 0 }},
                                {{ $companyData['by_status']['rejected'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });

                // Application Chart
                const applicationData = @json($applicationData['by_status']);
                new Chart(document.getElementById('application-chart'), {
                    type: 'pie',
                    data: {
                        labels: Object.keys(applicationData).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                        datasets: [{
                            data: Object.values(applicationData),
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(107, 114, 128, 0.8)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>