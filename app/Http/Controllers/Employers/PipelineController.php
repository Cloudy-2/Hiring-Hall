<?php

namespace App\Http\Controllers\Employers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\PipelineStage;
use Illuminate\Http\Request;

class PipelineController extends Controller
{
    protected function ensureEmployer(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'employer') {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $companies = $user->companies()->verified()->get();

        if ($companies->isEmpty()) {
            return view('employers.pipeline.index', [
                'companies' => $companies,
                'selectedCompany' => null,
                'stages' => collect(),
                'applications' => collect(),
            ]);
        }

        $companyId = $request->get('company_id', $companies->first()->id);
        $selectedCompany = $companies->firstWhere('id', $companyId) ?? $companies->first();

        $this->ensureStagesExist($selectedCompany);

        $stages = PipelineStage::forCompany($selectedCompany->id)
            ->ordered()
            ->withCount('applications')
            ->get();

        $defaultStage = $stages->firstWhere('is_default', true) ?? $stages->first();

        $jobIds = $selectedCompany->jobPostings()->pluck('id');

        $allApplications = JobApplication::whereIn('job_posting_id', $jobIds)
            ->whereNotIn('status', ['not_selected', 'rejected', 'closed'])
            ->with(['applicantProfile.user', 'jobPosting', 'pipelineStage'])
            ->latest('applied_at')
            ->get();

        if ($defaultStage) {
            $applicationsWithNullStage = $allApplications->whereNull('pipeline_stage_id');
            foreach ($applicationsWithNullStage as $app) {
                $app->update(['pipeline_stage_id' => $defaultStage->id]);
                $app->pipeline_stage_id = $defaultStage->id;
            }
        }

        // Sync pipeline stage with application status for any mismatches
        $statusToStageName = [
            'applied' => 'New Applications',
            'under_review' => 'Screening',
            'application_viewed' => 'Screening',
            'viewed' => 'Screening',
            'in_progress' => 'Interview',
            'accepted' => 'Hired',
            'not_selected' => 'Rejected',
        ];
        $stageNameToId = $stages->keyBy('name')->map(fn ($s) => $s->id);
        foreach ($allApplications as $app) {
            $expectedStageName = $statusToStageName[$app->status] ?? null;
            if ($expectedStageName && isset($stageNameToId[$expectedStageName])) {
                $expectedStageId = $stageNameToId[$expectedStageName];
                if ($app->pipeline_stage_id !== $expectedStageId) {
                    $app->update(['pipeline_stage_id' => $expectedStageId]);
                    $app->pipeline_stage_id = $expectedStageId;
                }
            }
        }

        $applications = $allApplications->groupBy('pipeline_stage_id');

        return view('employers.pipeline.index', compact('companies', 'selectedCompany', 'stages', 'applications'));
    }

    public function moveApplication(Request $request, JobApplication $application)
    {
        $user = $this->ensureEmployer($request);

        $application->load('jobPosting.company');

        if ($application->jobPosting->company->user_id !== $user->id) {
            abort(403);
        }

        // Prevent moving if it's already terminal (Accepted or Declined)
        if (in_array($application->status, ['accepted', 'not_selected'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot move an application that has already been Accepted or Declined.',
            ], 422);
        }

        $validated = $request->validate([
            'stage_id' => 'required|exists:pipeline_stages,id',
        ]);

        $stage = PipelineStage::findOrFail($validated['stage_id']);

        if ($stage->company_id !== $application->jobPosting->company_id) {
            abort(403, 'Stage does not belong to this company.');
        }

        $application->update(['pipeline_stage_id' => $stage->id]);

        return response()->json(['success' => true, 'stage' => $stage]);
    }

    public function updateStagesOrder(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'stages' => 'required|array',
            'stages.*.id' => 'required|exists:pipeline_stages,id',
            'stages.*.sort_order' => 'required|integer|min:0',
        ]);

        $company = Company::findOrFail($validated['company_id']);

        if ($company->user_id !== $user->id) {
            abort(403);
        }

        foreach ($validated['stages'] as $stageData) {
            PipelineStage::where('id', $stageData['id'])
                ->where('company_id', $company->id)
                ->update(['sort_order' => $stageData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    public function createStage(Request $request)
    {
        $user = $this->ensureEmployer($request);

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:100',
            'color' => 'required|string|max:20',
        ]);

        $company = Company::findOrFail($validated['company_id']);

        if ($company->user_id !== $user->id) {
            abort(403);
        }

        $maxOrder = PipelineStage::forCompany($company->id)->max('sort_order') ?? 0;

        $stage = PipelineStage::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'color' => $validated['color'],
            'sort_order' => $maxOrder + 1,
            'is_default' => false,
            'is_system' => false,
        ]);

        return response()->json(['success' => true, 'stage' => $stage]);
    }

    public function updateStage(Request $request, PipelineStage $stage)
    {
        $user = $this->ensureEmployer($request);

        if ($stage->company->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'color' => 'required|string|max:20',
        ]);

        $stage->update($validated);

        return response()->json(['success' => true, 'stage' => $stage]);
    }

    public function deleteStage(Request $request, PipelineStage $stage)
    {
        $user = $this->ensureEmployer($request);

        if ($stage->company->user_id !== $user->id) {
            abort(403);
        }

        if ($stage->is_system && $stage->applications()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a system stage with applications. Move applications first.',
            ], 422);
        }

        $defaultStage = PipelineStage::forCompany($stage->company_id)
            ->where('is_default', true)
            ->first();

        if ($defaultStage && $defaultStage->id !== $stage->id) {
            $stage->applications()->update(['pipeline_stage_id' => $defaultStage->id]);
        }

        $stage->delete();

        return response()->json(['success' => true]);
    }

    protected function ensureStagesExist(Company $company): void
    {
        if (! PipelineStage::forCompany($company->id)->exists()) {
            PipelineStage::createDefaultStagesForCompany($company);
        }
    }
}
