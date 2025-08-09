<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SummaryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/summarize/text', [SummaryController::class, 'summarizeText']);
Route::post('/summarize/file', [SummaryController::class, 'summarizeFile']);

// Các route mới cho chức năng tóm tắt bằng Gemini API
Route::post('/summarize/gemini/text', [SummaryController::class, 'summarizeTextGemini']);
Route::post('/summarize/gemini/file', [SummaryController::class, 'summarizeFileGemini']);
Route::post('/summarize/gemini/url', [SummaryController::class, 'summarizeUrlGemini']);