<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\UploadedFile;

class ApiClient
{
    protected $client;
    protected $baseUri = 'http://localhost:5001'; // URL của API Flask

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'timeout'  => 10.0,
        ]);
    }

    public function summarizeText($text, $ratio = 0.2, $language = 'vietnamese', $userId = 1)
    {
        try {
            $response = $this->client->post('/summarize', [
                'json' => [
                    'text' => $text,
                    'ratio' => $ratio,
                    'language' => $language,
                    'user_id' => $userId
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            // Kiểm tra kết quả trả về
            if (!is_array($result)) {
                return [
                    'error' => 'Kết quả không hợp lệ từ API',
                    'details' => 'Dữ liệu trả về không phải là mảng'
                ];
            }
            
            if (isset($result['error'])) {
                return $result;
            }
            
            // Kiểm tra các key cần thiết
            if (!isset($result['summary']) || !isset($result['status'])) {
                return [
                    'error' => 'Kết quả không đầy đủ từ API',
                    'details' => 'Thiếu các trường cần thiết trong kết quả'
                ];
            }

            return $result;
        } catch (RequestException $e) {
            return [
                'error' => 'Không thể gọi API tóm tắt văn bản',
                'details' => $e->getMessage()
            ];
        }
    }

    public function summarizeFile($source, $ratio = 0.2, $language = 'vietnamese', $userId = 1)
    {
        try {
            $response = $this->client->post('/summarize-file', [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => $source
                    ],
                    [
                        'name' => 'ratio',
                        'contents' => $ratio
                    ],
                    [
                        'name' => 'language',
                        'contents' => $language
                    ],
                    [
                        'name' => 'user_id',
                        'contents' => $userId
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            // Kiểm tra kết quả trả về
            if (!is_array($result)) {
                return [
                    'error' => 'Kết quả không hợp lệ từ API',
                    'details' => 'Dữ liệu trả về không phải là mảng'
                ];
            }
            
            if (isset($result['error'])) {
                return $result;
            }
            
            // Kiểm tra các key cần thiết
            if (!isset($result['summary']) || !isset($result['status'])) {
                return [
                    'error' => 'Kết quả không đầy đủ từ API',
                    'details' => 'Thiếu các trường cần thiết trong kết quả'
                ];
            }

            return $result;
        } catch (RequestException $e) {
            return [
                'error' => 'Không thể gọi API tóm tắt tệp',
                'details' => $e->getMessage()
            ];
        }
    }

    // Phương thức mới để tóm tắt văn bản sử dụng Gemini API
    public function summarizeTextGemini($text, $ratio = 0.2, $language = 'vietnamese', $userId = 1)
    {
        try {
            $response = $this->client->post('/summarize-gemini', [
                'json' => [
                    'text' => $text,
                    'ratio' => $ratio,
                    'language' => $language,
                    'user_id' => $userId
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            // Kiểm tra kết quả trả về
            if (!is_array($result)) {
                return [
                    'error' => 'Kết quả không hợp lệ từ API',
                    'details' => 'Dữ liệu trả về không phải là mảng'
                ];
            }
            
            if (isset($result['error'])) {
                return $result;
            }
            
            // Kiểm tra các key cần thiết
            if (!isset($result['summary']) || !isset($result['status'])) {
                return [
                    'error' => 'Kết quả không đầy đủ từ API',
                    'details' => 'Thiếu các trường cần thiết trong kết quả'
                ];
            }

            return $result;
        } catch (RequestException $e) {
            return [
                'error' => 'Không thể gọi API tóm tắt văn bản với Gemini',
                'details' => $e->getMessage()
            ];
        }
    }

    // Phương thức mới để tóm tắt file sử dụng Gemini API
    public function summarizeFileGemini(UploadedFile $file, $ratio = 0.2, $language = 'vietnamese', $userId = 1)
    {
        try {
            // Chuẩn bị multipart data
            $multipart = [
                [
                    'name' => 'file',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ],
                [
                    'name' => 'ratio',
                    'contents' => $ratio
                ],
                [
                    'name' => 'language',
                    'contents' => $language
                ],
                [
                    'name' => 'user_id',
                    'contents' => $userId
                ]
            ];

            $response = $this->client->post('/summarize-file-gemini', [
                'multipart' => $multipart
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            // Kiểm tra kết quả trả về
            if (!is_array($result)) {
                return [
                    'error' => 'Kết quả không hợp lệ từ API',
                    'details' => 'Dữ liệu trả về không phải là mảng'
                ];
            }
            
            if (isset($result['error'])) {
                return $result;
            }
            
            // Kiểm tra các key cần thiết
            if (!isset($result['summary']) || !isset($result['status'])) {
                return [
                    'error' => 'Kết quả không đầy đủ từ API',
                    'details' => 'Thiếu các trường cần thiết trong kết quả'
                ];
            }

            return $result;
        } catch (RequestException $e) {
            return [
                'error' => 'Không thể gọi API tóm tắt tệp với Gemini',
                'details' => $e->getMessage()
            ];
        }
    }

    // Phương thức mới để tóm tắt URL sử dụng Gemini API
    public function summarizeUrlGemini($url, $ratio = 0.2, $language = 'vietnamese', $userId = 1)
    {
        try {
            $response = $this->client->post('/summarize-url-gemini', [
                'multipart' => [
                    [
                        'name' => 'url',
                        'contents' => $url
                    ],
                    [
                        'name' => 'ratio',
                        'contents' => $ratio
                    ],
                    [
                        'name' => 'language',
                        'contents' => $language
                    ],
                    [
                        'name' => 'user_id',
                        'contents' => $userId
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            // Kiểm tra kết quả trả về
            if (!is_array($result)) {
                return [
                    'error' => 'Kết quả không hợp lệ từ API',
                    'details' => 'Dữ liệu trả về không phải là mảng'
                ];
            }
            
            if (isset($result['error'])) {
                return $result;
            }
            
            // Kiểm tra các key cần thiết
            if (!isset($result['summary']) || !isset($result['status'])) {
                return [
                    'error' => 'Kết quả không đầy đủ từ API',
                    'details' => 'Thiếu các trường cần thiết trong kết quả'
                ];
            }

            return $result;
        } catch (RequestException $e) {
            return [
                'error' => 'Không thể gọi API tóm tắt URL với Gemini',
                'details' => $e->getMessage()
            ];
        }
    }
}