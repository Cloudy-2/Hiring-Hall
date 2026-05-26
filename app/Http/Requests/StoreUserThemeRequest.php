<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserThemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reset' => ['nullable', 'boolean'],
            'theme_mode' => ['required_without:reset', 'string', 'in:light,dark,system'],
            'theme_styles' => ['nullable', 'array'],
            'theme_styles.header_style' => ['nullable', 'string', 'in:light,color,gradient,transparent,dark'],
            'theme_styles.menu_style' => ['nullable', 'string', 'in:light,color,gradient,transparent,dark'],
            'layout_settings' => ['nullable', 'array'],
            'layout_settings.menu_version' => ['nullable', 'string', 'in:v1,v2'],
            'layout_settings.nav_layout' => ['nullable', 'string', 'in:vertical,horizontal'],
            'layout_settings.nav_style' => ['nullable', 'string', 'in:menu-click,menu-hover,icon-click,icon-hover'],
            'layout_settings.vertical_style' => ['nullable', 'string', 'in:default,closed,icontext,overlay,detached,doublemenu'],
            'layout_settings.width' => ['nullable', 'string', 'in:fullwidth,boxed'],
            'layout_settings.page_style' => ['nullable', 'string', 'in:regular,classic,modern'],
            'layout_settings.menu_position' => ['nullable', 'string', 'in:fixed,scrollable'],
            'layout_settings.header_position' => ['nullable', 'string', 'in:fixed,scrollable'],
            'layout_settings.loader' => ['nullable', 'string', 'in:enable,disable'],
            'layout_settings.header_style' => ['nullable', 'string', 'in:light,color,gradient,transparent,dark'],
            'layout_settings.menu_style' => ['nullable', 'string', 'in:light,color,gradient,transparent,dark'],
        ];
    }
}
