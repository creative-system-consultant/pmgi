<?php

namespace App\Notification;

use Illuminate\Support\Facades\Http;


class Whatsapp
{
    private $url;
    private $token;

    public function __construct()
    {
        $this->url = config('app.wablas_api_url');
        $this->token = config('app.wablas_api_token');

        \Log::info("WhatsApp URL: $this->url");  // Log to ensure URL is correct
        \Log::info("WhatsApp Token: $this->token"); // Log to ensure token is correct
    }

    public function send($contact, $message)
    {
        $curl = curl_init();

        $payload = [
            "data" => [
                [
                    'phone' => $contact,
                    'message' => $message,
                    'secret' => false, // or true
                    'priority' => true, // or true
                ],
            ]
        ];

        \Log::info("Sending WhatsApp message to: $contact with message: $message");
        \Log::info("Payload: ", ['payload' => $payload]); // Log the payload

        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Authorization: $this->token",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_URL, $this->url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);

        \Log::info("cURL response: $result"); // Log the cURL response

        if (curl_errno($curl)) {
            \Log::error('cURL error: ' . curl_error($curl));
        }

        curl_close($curl);

        // return json_decode($result)->status;
        return $result;
    }
}
