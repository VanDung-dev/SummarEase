<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use App\Models\GuestDocument;
use App\Models\GuestSummary;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
        session(['original_ratio' => $request->input('ratio')]);

        $userId = Auth::id() ?? 3; // Sử dụng ID người dùng hiện tại hoặc mặc định là 3

        $result = $this->apiClient->summarizeText(
            $request->input('text'),
            $request->input('ratio', 0.2),
            $request->input('language', 'vietnamese'),
            $userId
        );

        // Kiểm tra lỗi trong kết quả
        if (isset($result['error'])) {
            return back()->with('error', $result['error'] . ': ' . ($result['details'] ?? ''));
        }

        // Kiểm tra các key cần thiết
        if (!isset($result['summary'])) {
            return back()->with('error', 'Không tìm thấy nội dung tóm tắt trong kết quả');
        }

        // Lưu dữ liệu vào bảng guest_documents nếu là khách
        if (!Auth::check()) {
            try {
                $sessionId = Session::getId();
                $guestDocument = new GuestDocument();
                $guestDocument->guest_id = $sessionId;
                $guestDocument->title = 'Văn bản tóm tắt - ' . now()->format('Y-m-d H:i:s');
                $guestDocument->content = $request->input('text');
                $guestDocument->file_type = 'text';
                $guestDocument->save();

                // Lưu tóm tắt vào bảng guest_summaries
                $guestSummary = new GuestSummary();
                $guestSummary->document_id = $guestDocument->id;
                $guestSummary->summary_text = $result['summary'];
                $guestSummary->summary_ratio = $request->input('ratio', 0.2);
                $guestSummary->save();
            } catch (\Exception $e) {
                \Log::error('Lỗi khi lưu dữ liệu khách: ' . $e->getMessage());
            }
        }

        return back()->with('summary', $result['summary']);
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

        $userId = Auth::id() ?? 3; // Sử dụng ID người dùng hiện tại hoặc mặc định là 3

        $result = $this->apiClient->summarizeFile(
            $request->input('file'),
            $request->input('ratio', 0.2),
            $request->input('language', 'vietnamese'),
            $userId
        );

        // Kiểm tra lỗi trong kết quả
        if (isset($result['error'])) {
            return back()->with('error', $result['error'] . ': ' . ($result['details'] ?? ''));
        }

        // Kiểm tra các key cần thiết
        if (!isset($result['summary'])) {
            return back()->with('error', 'Không tìm thấy nội dung tóm tắt trong kết quả');
        }

        // Lưu dữ liệu vào bảng guest_documents nếu là khách
        if (!Auth::check()) {
            try {
                $sessionId = Session::getId();
                $guestDocument = new GuestDocument();
                $guestDocument->guest_id = $sessionId;
                $guestDocument->title = 'Tập tin tóm tắt - ' . now()->format('Y-m-d H:i:s');
                $guestDocument->content = $request->input('file');
                $guestDocument->file_type = 'file';
                $guestDocument->save();

                // Lưu tóm tắt vào bảng guest_summaries
                $guestSummary = new GuestSummary();
                $guestSummary->document_id = $guestDocument->id;
                $guestSummary->summary_text = $result['summary'];
                $guestSummary->summary_ratio = $request->input('ratio', 0.2);
                $guestSummary->save();
            } catch (\Exception $e) {
                \Log::error('Lỗi khi lưu dữ liệu khách: ' . $e->getMessage());
            }
        }

        return back()->with('summary', $result['summary']);
    }

    // Phương thức mới để tóm tắt văn bản sử dụng Gemini API
    public function summarizeTextGemini(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'ratio' => 'numeric|min:0|max:1',
            'language' => 'in:vietnamese,english,auto'
        ]);

        // Lưu văn bản gốc vào session để hiển thị lại sau khi submit
        session(['original_text' => $request->input('text')]);
        session(['original_ratio' => $request->input('ratio')]);

        $userId = Auth::id() ?? 3; // Sử dụng ID người dùng hiện tại hoặc mặc định là 3

        $result = $this->apiClient->summarizeTextGemini(
            $request->input('text'),
            $request->input('ratio', 0.2),
            $request->input('language', 'vietnamese'),
            $userId
        );

        // Kiểm tra lỗi trong kết quả
        if (isset($result['error'])) {
            return back()->with('error', $result['error'] . ': ' . ($result['details'] ?? ''));
        }

        // Kiểm tra các key cần thiết
        if (!isset($result['summary'])) {
            return back()->with('error', 'Không tìm thấy nội dung tóm tắt trong kết quả');
        }

        // Lưu dữ liệu vào bảng guest_documents nếu là khách
        if (!Auth::check()) {
            try {
                $sessionId = Session::getId();
                $guestDocument = new GuestDocument();
                $guestDocument->guest_id = $sessionId;
                $guestDocument->title = 'Văn bản tóm tắt (Gemini) - ' . now()->format('Y-m-d H:i:s');
                $guestDocument->content = $request->input('text');
                $guestDocument->file_type = 'text';
                $guestDocument->save();

                // Lưu tóm tắt vào bảng guest_summaries
                $guestSummary = new GuestSummary();
                $guestSummary->document_id = $guestDocument->id;
                $guestSummary->summary_text = $result['summary'];
                $guestSummary->summary_ratio = $request->input('ratio', 0.2);
                $guestSummary->save();
            } catch (\Exception $e) {
                \Log::error('Lỗi khi lưu dữ liệu khách: ' . $e->getMessage());
            }
        }

        // Trả về thêm tiêu đề, từ khóa và ngôn ngữ nếu có
        return back()->with([
            'summary' => $result['summary'],
            'title' => $result['title'] ?? null,
            'keywords' => $result['keywords'] ?? null,
            'language' => $result['language'] ?? null
        ]);
    }

    // Phương thức mới để tóm tắt file sử dụng Gemini API
    public function summarizeFileGemini(Request $request)
    {
        $request->validate([
            'file' => 'required|file', // Yêu cầu là một file upload
            'ratio' => 'numeric|min:0|max:1',
            'language' => 'in:vietnamese,english,auto'
        ]);

        $userId = Auth::id() ?? 3; // Sử dụng ID người dùng hiện tại hoặc mặc định là 3

        $result = $this->apiClient->summarizeFileGemini(
            $request->file('file'),
            $request->input('ratio', 0.2),
            $request->input('language', 'vietnamese'),
            $userId
        );

        // Kiểm tra lỗi trong kết quả
        if (isset($result['error'])) {
            return back()->with('error', $result['error'] . ': ' . ($result['details'] ?? ''));
        }

        // Kiểm tra các key cần thiết
        if (!isset($result['summary'])) {
            return back()->with('error', 'Không tìm thấy nội dung tóm tắt trong kết quả');
        }

        // Lưu dữ liệu vào bảng guest_documents nếu là khách
        if (!Auth::check()) {
            try {
                $sessionId = Session::getId();
                $guestDocument = new GuestDocument();
                $guestDocument->guest_id = $sessionId;
                $guestDocument->title = 'Tập tin tóm tắt (Gemini) - ' . now()->format('Y-m-d H:i:s');
                // Trong thực tế, bạn sẽ lưu nội dung file ở đây
                $guestDocument->file_type = 'file';
                $guestDocument->save();

                // Lưu tóm tắt vào bảng guest_summaries
                $guestSummary = new GuestSummary();
                $guestSummary->document_id = $guestDocument->id;
                $guestSummary->summary_text = $result['summary'];
                $guestSummary->summary_ratio = $request->input('ratio', 0.2);
                $guestSummary->save();
            } catch (\Exception $e) {
                \Log::error('Lỗi khi lưu dữ liệu khách: ' . $e->getMessage());
            }
        }

        // Trả về thêm tiêu đề, từ khóa và ngôn ngữ nếu có
        return back()->with([
            'summary' => $result['summary'],
            'title' => $result['title'] ?? null,
            'keywords' => $result['keywords'] ?? null,
            'language' => $result['language'] ?? null
        ]);
    }

    // Phương thức mới để tóm tắt URL sử dụng Gemini API
    public function summarizeUrlGemini(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'ratio' => 'numeric|min:0|max:1',
            'language' => 'in:vietnamese,english,auto'
        ]);

        session(['original_url' => $request->input('url')]);
        session(['original_ratio' => $request->input('ratio')]);

        $userId = Auth::id() ?? 3; // Sử dụng ID người dùng hiện tại hoặc mặc định là 3

        $result = $this->apiClient->summarizeUrlGemini(
            $request->input('url'),
            $request->input('ratio', 0.2),
            $request->input('language', 'vietnamese'),
            $userId
        );

        // Kiểm tra lỗi trong kết quả
        if (isset($result['error'])) {
            return back()->with('error', $result['error'] . ': ' . ($result['details'] ?? ''));
        }

        // Kiểm tra các key cần thiết
        if (!isset($result['summary'])) {
            return back()->with('error', 'Không tìm thấy nội dung tóm tắt trong kết quả');
        }

        // Lưu dữ liệu vào bảng guest_documents nếu là khách
        if (!Auth::check()) {
            try {
                $sessionId = Session::getId();
                $guestDocument = new GuestDocument();
                $guestDocument->guest_id = $sessionId;
                $guestDocument->title = 'URL tóm tắt (Gemini) - ' . now()->format('Y-m-d H:i:s');
                $guestDocument->content = $request->input('url');
                $guestDocument->file_type = 'url';
                $guestDocument->save();

                // Lưu tóm tắt vào bảng guest_summaries
                $guestSummary = new GuestSummary();
                $guestSummary->document_id = $guestDocument->id;
                $guestSummary->summary_text = $result['summary'];
                $guestSummary->summary_ratio = $request->input('ratio', 0.2);
                $guestSummary->save();
            } catch (\Exception $e) {
                \Log::error('Lỗi khi lưu dữ liệu khách: ' . $e->getMessage());
            }
        }

        // Trả về thêm tiêu đề, từ khóa và ngôn ngữ nếu có
        return back()->with([
            'summary' => $result['summary'],
            'title' => $result['title'] ?? null,
            'keywords' => $result['keywords'] ?? null,
            'language' => $result['language'] ?? null
        ]);
    }

    public function formhandle(Request $request)
    {
        $action = $request->input('sum');
            $request->validate([
            'text' => 'required|string',
            'ratio' => 'numeric|min:0|max:1',
            'language' => 'in:vietnamese,english'
        ]);
        session(['original_text' => $request->input('text')]);
        session(['original_ratio' => $request->input('ratio')]);
        if ($action === 'summarease') {
            $this->summarizeText($request);
            return back();
        } elseif ($action === 'gemini') {
            $this->summarizeTextGemini($request);
            return back();
        }
    }
}

