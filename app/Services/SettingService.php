<?php

namespace App\Services;

use Dipokhalder\Settings\Facades\Settings;

class SettingService
{
    public function list(): array
    {
        $array = [];
        $array = array_merge($array, Settings::group('company')->all());
        $array = array_merge($array, Settings::group('site')->all());
        $array = array_merge($array, Settings::group('shipping_setup')->all());
        $array = array_merge($array, Settings::group('theme')->all());
        $array = array_merge($array, Settings::group('otp')->all());
        $array = array_merge($array, Settings::group('social_media')->all());
        $array = array_merge($array, Settings::group('notification')->all());
        $array = array_merge($array, Settings::group('cookies')->all());
        $testimonials = Settings::group('testimonials')->all();
        $array = array_merge($array, array_merge([
            'testimonials_section_status'   => \App\Enums\Activity::DISABLE,
            'testimonials_section_per_page' => 3,
        ], $testimonials));
        return $array;
    }
}
