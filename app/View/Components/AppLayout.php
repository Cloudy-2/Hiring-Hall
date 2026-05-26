<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public ?string $pageTitle;

    public ?string $active;

    public array $breadcrumbs;

    public function __construct(?string $pageTitle = null, ?string $active = null, array $breadcrumbs = [])
    {
        $this->pageTitle = $pageTitle;
        $this->active = $active;
        $this->breadcrumbs = $breadcrumbs;
    }

    public function render(): View
    {
        return view('layouts.app', [
            'pageTitle' => $this->pageTitle,
            'active' => $this->active,
            'breadcrumbs' => $this->breadcrumbs,
        ]);
    }
}
