<?php

namespace App\Livewire\Profile;

use Carbon\Carbon;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm as JetstreamUpdateProfileInformationForm;

class UpdateProfileInformationForm extends JetstreamUpdateProfileInformationForm
{
    public function mount()
    {
        parent::mount();

        $this->state['address'] = $this->resolveAddress($this->state['address'] ?? null);
        $this->state['date_of_birth'] = $this->normalizeDateForHtmlInput($this->state['date_of_birth'] ?? null);
        $this->state['gender'] = $this->normalizeGender($this->state['gender'] ?? null);
        $this->state['marital_status'] = $this->normalizeMaritalStatus($this->state['marital_status'] ?? null);
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->state['date_of_birth'] = $this->normalizeDateForHtmlInput($this->state['date_of_birth'] ?? null);
        $this->state['gender'] = $this->normalizeGender($this->state['gender'] ?? null);
        $this->state['marital_status'] = $this->normalizeMaritalStatus($this->state['marital_status'] ?? null);

        return parent::updateProfileInformation($updater);
    }

    private function normalizeDateForHtmlInput(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function normalizeGender(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $normalized = strtolower(trim((string) $value));

        return match ($normalized) {
            'm' => 'male',
            'f' => 'female',
            default => $normalized,
        };
    }

    private function normalizeMaritalStatus(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return strtolower(trim((string) $value));
    }

    private function resolveAddress(mixed $value): ?string
    {
        $applicantLocation = $this->user?->applicantProfile?->location;

        if (is_string($applicantLocation) && trim($applicantLocation) !== '') {
            return trim($applicantLocation);
        }

        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }

        return null;
    }
}
