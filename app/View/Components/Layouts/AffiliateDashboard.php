<?php

namespace App\View\Components\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AffiliateDashboard extends Component
{
    public function __construct(
        public ?string $pageTitle = null
    ) {
    }

    public function render(): View
    {
        return view('layouts.affiliate-dashboard');
    }
}
