<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domain;

class SettingsController extends Controller
{
    public function colors($siteId) {
        $domain = Domain::getBySiteId($siteId);

        $settings = [
            'main_button_color' => $domain->main_button_color,
            'main_button_color_hover' => $domain->main_button_color_hover,
            'main_button_color_active' => $domain->main_button_color_active,
            'panel_text_color' => $domain->panel_text_color,
            'add_button_color' => $domain->add_button_color,
            'bg_color' => $domain->bg_color,
            'label_text_color' => $domain->label_text_color,
        ];

        return response()->json($settings);
    }
}
