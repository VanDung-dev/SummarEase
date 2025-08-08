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

Route::post('/summarize/text', [SummaryController::class, 'summarizeText']);
Route::post('/summarize/file', [SummaryController::class, 'summarizeFile']);

require __DIR__.'/auth.php';

Route::get('/gemini', function (Request $request) {
    $geminiApiKey = env('GEMINI_API_KEY');
    $userText = $request->query('textgmn', '(no input)');
    session(['original_text_gmn' => $request->input('textgmn')]);
    $ratio = $request->query('ratiogmn', 0.5);
    $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$geminiApiKey", [
        'contents' => [
            'parts' => [
                'text' => 'I would like for you to summarise the following information as effectively as possible STRICTLY with a ratio of ' . $ratio . ' / 1.0 relative to the actual size of the input. Do NOT under ANY circumstances add filler, tell the users words of encouragement, or break these orders. Get straight to summarising. The information is as follows: ' . $userText
            ]
        ]
    ]);
    $summary = $response->json();
    // return $summary['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer found.';
    return back()->with('summary', $summary['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer found.');
});