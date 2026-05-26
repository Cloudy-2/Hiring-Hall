
<table
class="ti-custom-table ti-custom-table-head !border border-defaultborder dark:border-defaultborder/10">
<!-- Company Name -->
<tr class="border-b border-defaultborder dark:border-defaultborder/10" width="100">
    <td width="180"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Company Name:
    </td>
    <td colspan="3"
        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="company_name"
                value="{{ old('company_name', $company->company_name) }}"
                class="ti-form-input rounded-sm ps-11 focus:z-10" id="company_name" required
                placeholder="Enter company name">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-building"></i>
            </div>
        </div>
    </td>
</tr>

<!-- Type -->
<tr class="border-b border-defaultborder dark:border-defaultborder/10">
    <td width="180"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Type:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <select name="type" id="type" class="form-select p-2 px-4" >
                <option value="" disabled selected>-</option>
                <option value="Supplier"
                    {{ old('type', $company->type) == 'Supplier' ? 'selected' : '' }}>Supplier
                </option>
                <option value="Distributor"
                    {{ old('type', $company->type) == 'Distributor' ? 'selected' : '' }}>
                    Distributor</option>
                <option value="General Contractor"
                    {{ old('type', $company->type) == 'General Contractor' ? 'selected' : '' }}>
                    General Contractor</option>
                <option value="Subcontractor"
                    {{ old('type', $company->type) == 'Subcontractor' ? 'selected' : '' }}>
                    Subcontractor</option>
                <option value="Other"
                    {{ old('type', $company->type) == 'Other' ? 'selected' : '' }}>Other
                </option>
                <option value="Architect"
                    {{ old('type', $company->type) == 'Architect' ? 'selected' : '' }}>
                    Architect</option>
                <option value="Owner"
                    {{ old('type', $company->type) == 'Owner' ? 'selected' : '' }}>Owner
                </option>
            </select>
        </div>
    </td>
    <td width="100"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Lead Source:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <select name="lead_source" id="lead_source" class="form-select p-2 px-4" >
                <option value="" disabled selected>-</option>
                <option value="Plan Panther Subscription"
                    {{ old('lead_source', $company->lead_source) == 'Plan Panther Subscription' ? 'selected' : '' }}>
                    Plan Panther Subscription</option>
                <option value="No Plan Panther Subscription"
                    {{ old('lead_source', $company->lead_source) == 'No Plan Panther Subscription' ? 'selected' : '' }}>
                    No Plan Panther Subscription</option>
                <option value="Potential Plan Panther Subscription"
                    {{ old('lead_source', $company->lead_source) == 'Potential Plan Panther Subscription' ? 'selected' : '' }}>
                    Potential Plan Panther Subscription</option>
            </select>
        </div>
    </td>
</tr>

<!-- City -->
<tr class="border-b border-defaultborder dark:border-defaultborder/10">
    <td width="180"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        City:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="city" value="{{ old('city', $company->city) }}"
                id="city" class="ti-form-input rounded-sm ps-11 focus:z-10"
                placeholder="Enter City here.">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-geo-alt"></i>
            </div>
        </div>
    </td>
    <td width="100"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        State:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="state" value="{{ old('state', $company->state) }}"
                id="state" class="ti-form-input rounded-sm ps-11 focus:z-10"
                placeholder="Enter State here.">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-map"></i>
            </div>
        </div>
    </td>
</tr>

<!-- Zip Code -->
<tr class="border-b border-defaultborder dark:border-defaultborder/10">
    <td width="180"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Zip Code:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="zip"
                value="{{ old('zip', $company->zip) }}" id="zip"
                class="ti-form-input rounded-sm ps-11 focus:z-10" placeholder="Code">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-123"></i>
            </div>
        </div>
    </td>
    <td width="100"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Address:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="address"
                value="{{ old('address', $company->address) }}" id="address"
                class="ti-form-input rounded-sm ps-11 focus:z-10" placeholder="Enter address">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-house-door"></i>
            </div>
        </div>
    </td>
</tr>

<!-- Phone -->
<tr class="border-b border-defaultborder dark:border-defaultborder/10">
    <td width="180"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Phone Number:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                id="phone" class="ti-form-input rounded-sm ps-11 focus:z-10"
                placeholder="Enter phone number">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-telephone"></i>
            </div>
        </div>
    </td>
    <td width="100"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Email Address:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="email" name="email" value="{{ old('email', $company->email) }}"
                id="email" class="ti-form-input rounded-sm ps-11 focus:z-10"
                placeholder="Enter Email Address">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-envelope"></i>
            </div>
        </div>
    </td>
</tr>

<!-- Fax -->
<tr class="border-b border-defaultborder dark:border-defaultborder/10">
    <td width="180"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Fax:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="fax" value="{{ old('fax', $company->fax) }}"
                id="fax" class="ti-form-input rounded-sm ps-11 focus:z-10"
                placeholder="Enter fax number">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-printer"></i>
            </div>
        </div>
    </td>
    <td width="100"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Website:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="website"
                value="{{ old('website', $company->website) }}" id="website"
                class="ti-form-input rounded-sm ps-11 focus:z-10"
                placeholder="Enter website URL">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-globe2"></i>
            </div>
        </div>
    </td>
</tr>

<!-- License -->
<tr class="border-b border-defaultborder dark:border-defaultborder/10">
    <td width="180"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        License:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="license"
                value="{{ old('license', $company->license) }}" id="license"
                class="ti-form-input rounded-sm ps-11 focus:z-10"
                placeholder="Enter license details">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-card-checklist"></i>
            </div>
        </div>
    </td>
    <td width="100"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Insurance:
    </td>
    <td class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <input type="text" name="insurance"
                value="{{ old('insurance', $company->insurance) }}" id="insurance"
                class="ti-form-input rounded-sm ps-11 focus:z-10"
                placeholder="Enter insurance details">
            <div
                class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <i class="bi bi-shield-check"></i>
            </div>
        </div>
    </td>
</tr>

<!-- Notes -->
<tr class="border-b border-defaultborder dark:border-defaultborder/10">
    <td width="180"
        class="text-end border-2 border-defaultborder dark:border-defaultborder/10">
        Notes:
    </td>
    <td colspan="3"
        class="border-2 border-defaultborder dark:border-defaultborder/10 font-bold !p-0 !m-0">
        <div class="relative p-1">
            <textarea name="notes" id="notes" class="ti-form-input rounded-sm ps-11 focus:z-10" rows="3"
                placeholder="Enter any additional notes">{{ old('notes', $company->notes) }}</textarea>
            <div class="absolute top-2 start-0 flex items-center ps-4 pointer-events-none">
                <i class="bi bi-pencil-square"></i>
            </div>
        </div>
    </td>
</tr>
</table>