<?php

namespace App\Services;

use App\Enums\Activity;
use App\Libraries\QueryExceptionLibrary;
use Dipokhalder\Settings\Facades\Settings;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestimonialSettingService
{
    public function list(): array
    {
        try {
            $settings = Settings::group('testimonials')->all();
            return array_merge([
                'testimonials_section_status'   => Activity::DISABLE,
                'testimonials_section_per_page' => 3,
            ], $settings);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function update(Request $request): array
    {
        try {
            $request->validate([
                'testimonials_section_status'   => ['required', 'numeric'],
                'testimonials_section_per_page' => ['required', 'integer', 'min:1', 'max:12'],
            ]);
            Settings::group('testimonials')->set([
                'testimonials_section_status'   => $request->testimonials_section_status,
                'testimonials_section_per_page' => $request->testimonials_section_per_page,
            ]);
            return $this->list();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
