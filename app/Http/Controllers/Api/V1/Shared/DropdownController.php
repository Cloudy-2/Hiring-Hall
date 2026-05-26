<?php

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Shared\DropdownRequest;
use App\Models\DropdownOption;
use Illuminate\Http\JsonResponse;

class DropdownController extends ApiController
{
    /**
     * GET /api/v1/dropdowns/{type}
     *
     * Returns dropdown options for a given type (e.g. skills, categories, job_types).
     * Used by mobile forms to populate select/picker fields.
     */
    public function index(DropdownRequest $request, string $type): JsonResponse
    {
        $options = DropdownOption::where('type', $type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get(['id', 'label', 'value', 'type']);

        return $this->success($options);
    }
}
