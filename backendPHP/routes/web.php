<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\SummaryController;
use Brick\Math\BigRational;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('dashboard', [SummaryController::class, 'summarizeTextOnDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.summarize');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::post('/summarize/text', [SummaryController::class, 'summarizeText']);
Route::post('/summarize/file', [SummaryController::class, 'summarizeFile']);

require __DIR__.'/auth.php';

Route::get('/gemini', function (Request $request) {
    $geminiApiKey = env('GEMINI_API_KEY');
    $userText = $request->query('text', '(No text provided)');
    $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$geminiApiKey", [
        'contents' => [
            'parts' => [
                'text' => $userText
            ]
        ]
    ]);
    $summary = $response->json();
    return $summary['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer found.';
});