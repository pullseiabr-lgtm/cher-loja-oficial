<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmailTemplateController extends AdminController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:settings', only: ['index', 'show', 'update']),
        ];
    }

    public function index(): \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $templates = EmailTemplate::orderBy('id')->get();
            return response()->json(['data' => $templates]);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function show(EmailTemplate $emailTemplate): \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return response()->json(['data' => $emailTemplate]);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, EmailTemplate $emailTemplate): \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $validated = $request->validate([
                'subject' => ['required', 'string', 'max:255'],
                'body'    => ['required', 'string'],
            ]);

            $emailTemplate->update($validated);
            return response()->json(['data' => $emailTemplate]);
        } catch (Exception $e) {
            return response(['status' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
