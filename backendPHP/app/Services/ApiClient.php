<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

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

    public function summarizeText($text, $ratio = 0.2, $language = 'vietnamese')
    {
        try {
            $response = $this->client->post('/summarize', [
                'json' => [
                    'text' => $text,
                    'ratio' => $ratio,
                    'language' => $language
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'error' => 'Không thể gọi API tóm tắt văn bản',
                'details' => $e->getMessage()
            ];
        }
    }

    public function summarizeFile($source, $ratio = 0.2, $language = 'vietnamese')
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
                    ]
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'error' => 'Không thể gọi API tóm tắt tệp',
                'details' => $e->getMessage()
            ];
        }
    }
}