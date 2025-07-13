<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Settings;

class SettingsComposer
{
    protected $settings;

    public function __construct()
    {
        $this->settings = Settings::first();
    }

    public function compose(View $view)
    {
        $view->with('settings', $this->settings);
    }
}
