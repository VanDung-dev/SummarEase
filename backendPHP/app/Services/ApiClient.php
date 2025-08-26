<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class ApiClient
{
    protected $client;
    protected $baseUri;

    public function __construct()
    {
        // Use the API_BASE_URI from .env.docker or fallback to localhost for development
        $this->baseUri = env('API_BASE_URI', 'http://localhost:5001');
        
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'timeout'  => 10.0,
        ]);
    }

    public function summarizeText($text, $ratio = 0.2, $language = 'vietnamese', $userId = 3)
    {
        try {
            // Xác định nếu người dùng là khách
            $isGuest = !Auth::check();
            
            $requestData = [
                'text' => $text,
                'ratio' => $ratio,
                'language' => $language,
                'user_id' => $userId
            ];
            
            // Thêm thông tin khách nếu cần
            if ($isGuest) {
                $requestData['is_guest'] = true;
                $requestData['guest_id'] = session()->getId();
            }
            
            $response = $this->client->post('/summarize', [
                'json' => $requestData
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

    public function summarizeFiles($sources, $ratio = 0.2, $language = 'vietnamese', $userId = 3)
    {
        try {
            // Xử lý cả trường hợp một file hoặc nhiều file
            if (!is_array($sources)) {
                $sources = [$sources];
            }
            
            // Xác định nếu người dùng là khách
            $isGuest = !Auth::check();
            
            // Chuẩn bị multipart data
            $multipart = [
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
            
            // Thêm từng file vào multipart data
            foreach ($sources as $index => $source) {
                if ($source instanceof \Illuminate\Http\UploadedFile) {
                    $multipart[] = [
                        'name' => 'files',
                        'contents' => fopen($source->getPathname(), 'r'),
                        'filename' => $source->getClientOriginalName()
                    ];
                } else {
                    $multipart[] = [
                        'name' => 'files',
                        'contents' => $source
                    ];
                }
            }
            
            // Thêm thông tin khách nếu cần
            if ($isGuest) {
                $multipart[] = [
                    'name' => 'is_guest',
                    'contents' => 'true'
                ];
                $multipart[] = [
                    'name' => 'guest_id',
                    'contents' => session()->getId()
                ];
            }
            
            $response = $this->client->post('/summarize-files', [
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
                'error' => 'Không thể gọi API tóm tắt tệp',
                'details' => $e->getMessage()
            ];
        }
    }

    public function summarizeUrl($source, $ratio = 0.2, $language = 'vietnamese', $userId = 3)
    {
        try {
            // Xác định nếu người dùng là khách
            $isGuest = !Auth::check();
            
            if ($source instanceof \Illuminate\Http\UploadedFile) {
                $multipart = [
                    [
                        'name' => 'file',
                        'contents' => fopen($source->getPathname(), 'r'),
                        'filename' => $source->getClientOriginalName()
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
            } else {
                $multipart = [
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
                ];
            }
            
            // Thêm thông tin khách nếu cần
            if ($isGuest) {
                $multipart[] = [
                    'name' => 'is_guest',
                    'contents' => 'true'
                ];
                $multipart[] = [
                    'name' => 'guest_id',
                    'contents' => session()->getId()
                ];
            }
            
            $response = $this->client->post('/summarize-url', [
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
                'error' => 'Không thể gọi API tóm tắt URL',
                'details' => $e->getMessage()
            ];
        }
    }

    // Phương thức mới để tóm tắt văn bản sử dụng Gemini API
    public function summarizeTextGemini($text, $ratio = 0.2, $language = 'vietnamese', $userId = 3)
    {
        try {
            // Xác định nếu người dùng là khách
            $isGuest = !Auth::check();
            
            $requestData = [
                'text' => $text,
                'ratio' => $ratio,
                'language' => $language,
                'user_id' => $userId
            ];
            
            // Thêm thông tin khách nếu cần
            if ($isGuest) {
                $requestData['is_guest'] = true;
                $requestData['guest_id'] = session()->getId();
            }
            
            $response = $this->client->post('/summarize-gemini', [
                'json' => $requestData
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
    public function summarizeFileGemini($files, $ratio = 0.2, $language = 'vietnamese', $userId = 3)
    {
        try {
            // Xử lý cả trường hợp một file hoặc nhiều file
            if (!is_array($files)) {
                $files = [$files];
            }
            
            // Xác định nếu người dùng là khách
            $isGuest = !Auth::check();
            
            // Chuẩn bị multipart data
            $multipart = [
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
            
            // Thêm từng file vào multipart data
            foreach ($files as $index => $file) {
                $multipart[] = [
                    'name' => 'files',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ];
            }
            
            // Thêm thông tin khách nếu cần
            if ($isGuest) {
                $multipart[] = [
                    'name' => 'is_guest',
                    'contents' => 'true'
                ];
                $multipart[] = [
                    'name' => 'guest_id',
                    'contents' => session()->getId()
                ];
            }

            $response = $this->client->post('/summarize-files-gemini', [
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
    public function summarizeUrlGemini($url, $ratio = 0.2, $language = 'vietnamese', $userId = 3)
    {
        try {
            // Xác định nếu người dùng là khách
            $isGuest = !Auth::check();
            
            $multipart = [
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
            ];
            
            // Thêm thông tin khách nếu cần
            if ($isGuest) {
                $multipart[] = [
                    'name' => 'is_guest',
                    'contents' => 'true'
                ];
                $multipart[] = [
                    'name' => 'guest_id',
                    'contents' => session()->getId()
                ];
            }

            $response = $this->client->post('/summarize-url-gemini', [
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
                'error' => 'Không thể gọi API tóm tắt URL với Gemini',
                'details' => $e->getMessage()
            ];
        }
    }
}