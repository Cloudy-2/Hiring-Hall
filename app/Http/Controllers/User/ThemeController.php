<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserThemeRequest;
use App\Models\UserThemePreference;
use Illuminate\Http\JsonResponse;

class ThemeController extends Controller
{
    public function store(StoreUserThemeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (data_get($validated, 'reset') === true) {
            $themePreference = UserThemePreference::query()->updateOrCreate(
                ['user_id' => $request->user()->id],
                [
                    'theme_mode' => 'light',
                    'theme_styles' => [
                        'header_style' => 'light',
                        'menu_style' => 'light',
                    ],
                    'layout_settings' => null,
                ]
            );

            return response()->json([
                'message' => 'Theme preference reset successfully.',
                'data' => [
                    'theme_mode' => $themePreference->theme_mode,
                    'theme_styles' => $themePreference->theme_styles,
                    'layout_settings' => $themePreference->layout_settings,
                ],
            ]);
        }

        $existingPreference = UserThemePreference::query()->where('user_id', $request->user()->id)->first();
        $existingThemeStyles = $existingPreference?->theme_styles ?? [];
        $existingLayoutSettings = $existingPreference?->layout_settings ?? [];

        $incomingThemeStyles = array_filter([
            'header_style' => data_get($validated, 'theme_styles.header_style'),
            'menu_style' => data_get($validated, 'theme_styles.menu_style'),
        ], static fn ($value) => $value !== null);

        $incomingLayoutSettings = array_filter([
            'menu_version' => data_get($validated, 'layout_settings.menu_version'),
            'nav_layout' => data_get($validated, 'layout_settings.nav_layout'),
            'nav_style' => data_get($validated, 'layout_settings.nav_style'),
            'vertical_style' => data_get($validated, 'layout_settings.vertical_style'),
            'width' => data_get($validated, 'layout_settings.width'),
            'page_style' => data_get($validated, 'layout_settings.page_style'),
            'menu_position' => data_get($validated, 'layout_settings.menu_position'),
            'header_position' => data_get($validated, 'layout_settings.header_position'),
            'loader' => data_get($validated, 'layout_settings.loader'),
            'header_style' => data_get($validated, 'layout_settings.header_style'),
            'menu_style' => data_get($validated, 'layout_settings.menu_style'),
        ], static fn ($value) => $value !== null);

        $themePreference = UserThemePreference::query()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'theme_mode' => $validated['theme_mode'],
                'theme_styles' => array_merge($existingThemeStyles, $incomingThemeStyles),
                'layout_settings' => array_merge($existingLayoutSettings, $incomingLayoutSettings),
            ]
        );

        return response()->json([
            'message' => 'Theme preference saved successfully.',
            'data' => [
                'theme_mode' => $themePreference->theme_mode,
                'theme_styles' => $themePreference->theme_styles,
                'layout_settings' => $themePreference->layout_settings,
            ],
        ]);
    }
}
