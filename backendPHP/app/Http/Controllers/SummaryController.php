<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;

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

        $result = $this->apiClient->summarizeText(
            $request->input('text'),
            $request->input('ratio', 0.2),
            $request->input('language', 'vietnamese')
        );

        return response()->json($result);
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
            $request->input('language', 'vietnamese')
        );

        return response()->json($result);
    }
}