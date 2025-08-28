<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\SummaryController;
use Brick\Math\BigRational;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('dashboard-file', 'dashboard-file')
    ->middleware(['auth', 'verified'])
    ->name('dashboard-file');

Route::view('dashboard-url', 'dashboard-url')
    ->middleware(['auth', 'verified'])
    ->name('dashboard-url');

Route::post('dashboard', [SummaryController::class, 'summarizeTextOnDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.summarize');

Route::post('dashboard-file', [SummaryController::class, 'summarizeFile'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard-file.summarize');

Route::post('dashboard-url', [SummaryController::class, 'summarizeUrlGemini'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard-url.summarize');

Route::delete('delete-user/{userId}', [SummaryController::class, 'del_user'])
    ->middleware(['admin'])
    ->name('delete-user');

Route::delete('delete-file/{fileId}', [SummaryController::class, 'del_file'])
    ->middleware(['admin'])
    ->name('delete-file');

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

// Route::get('/gemini', function (Request $request) {
//     $geminiApiKey = env('GEMINI_API_KEY');
//     $userText = $request->query('textgmn', '(no input)');
//     session(['original_text_gmn' => $request->input('textgmn')]);
//     $ratio = $request->query('ratiogmn', 0.5);
//     session(['original_ratio_gmn' => $request->input('ratiogmn')]);
//     $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$geminiApiKey", [
//         'contents' => [
//             'parts' => [
//                 'text' => 'I would like you to summarise the following information with a ratio of EXACTLY ' . $ratio*100 . '% relative to the size of the input WHILE STILL RETAINING THE PROVIDED INFOMATION. Get straight to summarising. Do NOT under ANY circumstances break these orders. The information is as follows: ' . $userText
//             ]
//         ]
//     ]);
//     $summary = $response->json();
//     // return $summary['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer found.';
//     return back()->with('summary', $summary['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer found.');
// });

Route::post('dashboard', [SummaryController::class, 'formhandle'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.summarize');

Route::post('dashboard-file', [SummaryController::class, 'formhandle_file'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard-file.summarize');

Route::post('dashboard-url', [SummaryController::class, 'formhandle_url'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard-url.summarize');

Route::get('/history', function () {
    if (auth()->check()) {
        $userId = Auth::id();
        $history = DB::table('summaries')
            ->select('summaries.id as summaryid', 'summary_text', 'summary_ratio', 'title', 'file_name', 'content as doctext', 'summaries.created_at')
            ->orderBy('summaries.created_at', 'desc')
            ->join('documents', 'documents.id', '=', 'document_id')
            ->join('users', 'users.id', '=', 'documents.user_id')
            ->where('users.id', '=', $userId)
            ->get();
        return view('/history-page', compact('history'));
    }
    // else {
    //     $sessionId = Session::getId();
    //     $history = DB::table('guest_summaries')
    //         ->select('guest_summaries.id as summaryid', 'summary_text', 'summary_ratio', 'title', 'file_name', 'content as doctext', 'guest_summaries.created_at')
    //         ->orderBy('guest_summaries.created_at', 'desc')
    //         ->join('guest_documents', 'guest_documents.id', '=', 'document_id')
    //         ->join('sessions', 'sessions.id', '=', 'guest_documents.guest_id')
    //         ->where('sessions.id', '=', $sessionId)
    //         ->paginate();
    //     return view('/history-page', compact('history'));
    // }
})->middleware(['auth', 'verified'])->name('history');

Route::delete('/history/{id}', function ($id) {
    $userId = Auth::id();

    // Find the summary and check if it belongs to the current user
    $summary = DB::table('summaries')
        ->join('documents', 'documents.id', '=', 'summaries.document_id')
        ->where('summaries.id', $id)
        ->where('documents.user_id', $userId)
        ->first();

    if ($summary) {
        // Delete the summary
        DB::table('summaries')->where('id', $id)->delete();

        return redirect()->route('dashboard')->with('success', 'Đã xóa lịch sử thành công');
    }

    return redirect()->route('dashboard')->with('error', 'Không tìm thấy lịch sử hoặc bạn không có quyền xóa');
})->middleware(['auth', 'verified'])->name('history.delete');

Route::delete('/history-all', function () {
    $userId = Auth::id();

    // Delete all summaries for the current user
    DB::table('summaries')
        ->join('documents', 'documents.id', '=', 'summaries.document_id')
        ->where('documents.user_id', $userId)
        ->delete();

    return redirect()->route('dashboard')->with('success', 'Đã xóa tất cả lịch sử thành công');
})->middleware(['auth', 'verified'])->name('history.delete-all');

Route::get('history-content/{summaryid}', function ($summaryid) {
    $usr = auth()->user();
    $isAdmin = $usr && $usr->isAdmin();
    if ($isAdmin) {
        $history = DB::table('summaries')
            ->select('summaries.id', 'summary_text', 'summary_ratio', 'title', 'file_name', 'content as doctext', 'summaries.created_at')
            ->orderBy('summaries.created_at', 'desc')
            ->join('documents', 'documents.id', '=', 'document_id')
            ->join('users', 'users.id', '=', 'documents.user_id')
            ->where('summaries.id', '=', $summaryid)
            ->first();
    }
    elseif (auth()->check()) {
        $userId = Auth::id();
        $history = DB::table('summaries')
            ->select('summaries.id', 'summary_text', 'summary_ratio', 'title', 'file_name', 'content as doctext', 'summaries.created_at')
            ->orderBy('summaries.created_at', 'desc')
            ->join('documents', 'documents.id', '=', 'document_id')
            ->join('users', 'users.id', '=', 'documents.user_id')
            ->where('users.id', '=', $userId)
            ->where('summaries.id', '=', $summaryid)
            ->first();
    }
    // else {
    //     $sessionId = Session::getId();
    //     $history = DB::table('guest_summaries')
    //         ->select('guest_summaries.id', 'summary_text', 'summary_ratio', 'title', 'file_name', 'content as doctext', 'guest_summaries.created_at')
    //         ->orderBy('guest_summaries.created_at', 'desc')
    //         ->join('guest_documents', 'guest_documents.id', '=', 'document_id')
    //         ->join('sessions', 'sessions.id', '=', 'guest_documents.guest_id')
    //         ->where('sessions.id', '=', $sessionId)
    //         ->where('guest_summaries.id', '=', $summaryid)
    //         ->first();
    // }
    return view('history-content', ['history' => $history]);
})->middleware(['auth', 'verified'])->name('history-content');
