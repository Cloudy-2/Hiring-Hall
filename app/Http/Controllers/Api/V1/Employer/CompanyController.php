<?php

namespace App\Http\Controllers\Api\V1\Employer;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Employer\UpdateCompanyRequest;
use App\Http\Requests\Api\Employer\UploadLogoRequest;
use App\Http\Requests\Api\Employer\UploadRegistrationDocumentRequest;
use App\Http\Resources\Api\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyController extends ApiController
{
    /**
     * GET /api/v1/employer/company
     */
    public function show(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return $this->notFound('Company profile not found. Please complete onboarding.');
        }

        return $this->success(new CompanyResource($company));
    }

    /**
     * PUT /api/v1/employer/company
     */
    public function update(UpdateCompanyRequest $request): JsonResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return $this->notFound('Company profile not found.');
        }

        $data = $request->validated();

        // Auto-generate slug if name changed and no slug yet
        if (isset($data['name']) && ! $company->slug) {
            $data['slug'] = Str::slug($data['name']);
        }

        $company->update($data);

        return $this->success(new CompanyResource($company->fresh()), 'Company profile updated.');
    }

    /**
     * POST /api/v1/employer/company/logo
     *
     * Upload and store the company logo.
     */
    public function uploadLogo(UploadLogoRequest $request): JsonResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return $this->notFound('Company profile not found.');
        }

        // Delete old logo if it's a local file
        if ($company->logo_url && ! str_starts_with($company->logo_url, 'http')) {
            Storage::disk('public')->delete($company->logo_url);
        }

        $path = $request->file('logo')->store('company-logos', 'public');
        $company->update(['logo_url' => $path]);

        return $this->success([
            'logo_url' => asset('storage/'.$path),
        ], 'Company logo uploaded.');
    }

    /**
     * POST /api/v1/employer/company/registration-document
     *
     * Upload and store the company's registration document.
     */
    public function uploadRegistrationDocument(UploadRegistrationDocumentRequest $request): JsonResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return $this->notFound('Company profile not found.');
        }

        if ($company->registration_document_url && ! str_starts_with($company->registration_document_url, 'http')) {
            Storage::disk('public')->delete($company->registration_document_url);
        }

        $path = $request->file('document')->store('company-registration-documents', 'public');
        $company->update(['registration_document_url' => $path]);

        return $this->success([
            'registration_document_url' => asset('storage/'.$path),
        ], 'Company registration document uploaded.');
    }
}
