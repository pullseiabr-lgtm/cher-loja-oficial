<?php

namespace Database\Seeders;

use App\Enums\Activity;
use Illuminate\Database\Seeder;
use Dipokhalder\Settings\Facades\Settings;

class TestimonialSettingSeeder extends Seeder
{
    public function run(): void
    {
        Settings::group('testimonials')->set([
            'testimonials_section_status'   => Activity::DISABLE,
            'testimonials_section_per_page' => 3,
        ]);
    }
}
