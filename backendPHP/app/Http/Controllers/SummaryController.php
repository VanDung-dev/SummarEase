<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Illuminate\Support\Facades\Auth;

class SummaryController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function summarizeText(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'ratio' => 'numeric|min:0|max:1',
            'language' => 'in:vietnamese,english'
        ]);

        // Lưu văn bản gốc vào session để hiển thị lại sau khi submit
        session(['original_text' => $request->input('text')]);

        $result = $this->apiClient->summarizeText(
            $request->input('text'),
            $request->input('ratio', 0.2),
            $request->input('language', 'vietnamese'),
            Auth::id() ?? 1 // Sử dụng ID người dùng hiện tại hoặc mặc định là 1
        );

        // Kiểm tra lỗi trong kết quả
        if (isset($result['error'])) {
            return back()->with('error', $result['error'] . ': ' . ($result['details'] ?? ''));
        }

        // Kiểm tra các key cần thiết
        if (!isset($result['summary'])) {
            return back()->with('error', 'Không tìm thấy nội dung tóm tắt trong kết quả');
        }

        return view('dashboard', ['summary' => $result['summary']]);
    }

    public function summarizeTextOnDashboard(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'ratio' => 'numeric|min:0|max:1',
            'language' => 'in:vietnamese,english'
        ]);

        // Lưu văn bản gốc vào session để hiển thị lại sau khi submit
        session(['original_text' => $request->input('text')]);
        session(['original_ratio' => $request->input('ratio')]);

        $result = $this->apiClient->summarizeText(
            $request->input('text'),
            $request->input('ratio', 0.2),
            $request->input('language', 'vietnamese'),
            Auth::id() ?? 1 // Sử dụng ID người dùng hiện tại hoặc mặc định là 1
        );

        // Kiểm tra lỗi trong kết quả
        if (isset($result['error'])) {
            return back()->with('error', $result['error'] . ': ' . ($result['details'] ?? ''));
        }

        // Kiểm tra các key cần thiết
        if (!isset($result['summary'])) {
            return back()->with('error', 'Không tìm thấy nội dung tóm tắt trong kết quả');
        }

        return back()->with('summary', $result['summary']);
    }

    public function summarizeFile(Request $request)
    {
        $request->validate([
            'file' => 'required|string', // Có thể thay đổi thành 'file' nếu upload file
            'ratio' => 'numeric|min:0|max:1',
            'language' => 'in:vietnamese,english'
        ]);

        $result = $this->apiClient->summarizeFile(
            $request->input('file'),
            $request->input('ratio', 0.2),
            $request->input('language', 'vietnamese'),
            Auth::id() ?? 1 // Sử dụng ID người dùng hiện tại hoặc mặc định là 1
        );

        // Kiểm tra lỗi trong kết quả
        if (isset($result['error'])) {
            return back()->with('error', $result['error'] . ': ' . ($result['details'] ?? ''));
        }

        // Kiểm tra các key cần thiết
        if (!isset($result['summary'])) {
            return back()->with('error', 'Không tìm thấy nội dung tóm tắt trong kết quả');
        }

        return view('dashboard', ['summary' => $result['summary']]);
    }
}