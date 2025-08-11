<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\SummaryController;
use Brick\Math\BigRational;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('dashboard', [SummaryController::class, 'summarizeTextOnDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.summarize');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/appearance');

    // Chỉ admin mới có thể truy cập profile và password settings
    Volt::route('settings/profile', 'settings.profile')
        ->name('settings.profile')
        ->middleware('admin');

    Volt::route('settings/password', 'settings.password')
        ->name('settings.password')
        ->middleware('admin');

    Volt::route('settings/appearance', 'settings.appearance')
        ->name('settings.appearance');
});

// Các route cho chức năng tóm tắt bằng hệ thống cơ bản
Route::post('/summarize/text', [SummaryController::class, 'summarizeText']);
Route::post('/summarize/file', [SummaryController::class, 'summarizeFile']);

// Các route cho chức năng tóm tắt bằng Gemini API
Route::post('/summarize/gemini/text', [SummaryController::class, 'summarizeTextGemini'])->name('summarize.gemini.text');
Route::post('/summarize/gemini/file', [SummaryController::class, 'summarizeFileGemini'])->name('summarize.gemini.file');
Route::post('/summarize/gemini/url', [SummaryController::class, 'summarizeUrlGemini'])->name('summarize.gemini.url');

require __DIR__.'/auth.php';

Route::get('/gemini', function (Request $request) {
    $geminiApiKey = env('GEMINI_API_KEY');
    $userText = $request->query('textgmn', '(no input)');
    session(['original_text_gmn' => $request->input('textgmn')]);
    $ratio = $request->query('ratiogmn', 0.5);
    session(['original_ratio_gmn' => $request->input('ratiogmn')]);
    $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$geminiApiKey", [
        'contents' => [
            'parts' => [
                'text' => 'I would like you to summarise the following information with a ratio of EXACTLY ' . $ratio*100 . '% relative to the size of the input WHILE STILL RETAINING THE PROVIDED INFOMATION. Get straight to summarising. Do NOT under ANY circumstances break these orders. The information is as follows: ' . $userText
            ]
        ]
    ]);
    $summary = $response->json();
    // return $summary['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer found.';
    return back()->with('summary', $summary['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer found.');
});
