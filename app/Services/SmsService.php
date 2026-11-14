<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class SmsService
{
    protected $settings;

    public function __construct()
    {
        $this->settings = DB::table('sms_settings')->first();
    }

    /**
     * Send SMS to a user's phone number
     */
    public function sendSms($userId, $message, $eventType = 'general')
    {
        if (!$this->settings || $this->settings->sms_enabled != 'yes') {
            return ['success' => false, 'message' => 'SMS service not enabled'];
        }

        // Get user phone
        $user = User::find($userId);
        if (!$user || empty($user->phone)) {
            return ['success' => false, 'message' => 'User phone number not found'];
        }

        // Check if event is enabled
        if (!$this->isEventEnabled($eventType)) {
            return ['success' => false, 'message' => 'SMS not enabled for this event type'];
        }

        // Check rate limiting
        if ($this->settings->sms_rate_limiting_enabled && !$this->checkRateLimit($userId)) {
            return ['success' => false, 'message' => 'Rate limit exceeded'];
        }

        // Try to send SMS
        $result = $this->sendViaPrimaryProvider($user->phone, $message);

        // If primary fails, try fallback 1
        if (!$result['success'] && !empty($this->settings->sms_fallback_provider_1)) {
            $result = $this->sendViaProvider($this->settings->sms_fallback_provider_1, $user->phone, $message);
        }

        // If fallback 1 fails, try fallback 2
        if (!$result['success'] && !empty($this->settings->sms_fallback_provider_2)) {
            $result = $this->sendViaProvider($this->settings->sms_fallback_provider_2, $user->phone, $message);
        }

        // Log SMS attempt
        $this->logSms($userId, $user->phone, $message, $eventType, $result);

        return $result;
    }

    /**
     * Send SMS to a specific phone number (without user lookup)
     */
    public function sendToPhone($phoneNumber, $message, $eventType = 'general')
    {
        if (!$this->settings || $this->settings->sms_enabled != 'yes') {
            return ['success' => false, 'message' => 'SMS service not enabled'];
        }

        $result = $this->sendViaPrimaryProvider($phoneNumber, $message);

        if (!$result['success'] && !empty($this->settings->sms_fallback_provider_1)) {
            $result = $this->sendViaProvider($this->settings->sms_fallback_provider_1, $phoneNumber, $message);
        }

        if (!$result['success'] && !empty($this->settings->sms_fallback_provider_2)) {
            $result = $this->sendViaProvider($this->settings->sms_fallback_provider_2, $phoneNumber, $message);
        }

        DB::table('sms_logs')->insert([
            'phone_number' => $phoneNumber,
            'message' => $message,
            'provider' => $this->settings->sms_primary_provider,
            'event_type' => $eventType,
            'status' => $result['success'] ? 'success' : 'failed',
            'response' => json_encode($result),
            'estimated_cost' => $result['cost'] ?? 0,
            'message_id' => $result['message_id'] ?? null,
            'sent_at' => $result['success'] ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $result;
    }

    protected function sendViaPrimaryProvider($phone, $message)
    {
        return $this->sendViaProvider($this->settings->sms_primary_provider, $phone, $message);
    }

    protected function sendViaProvider($provider, $phone, $message)
    {
        try {
            switch ($provider) {
                case 'twilio':
                    return $this->sendViaTwilio($phone, $message);
                case 'msg91':
                    return $this->sendViaMSG91($phone, $message);
                case 'plivo':
                    return $this->sendViaPlivo($phone, $message);
                case 'vonage':
                    return $this->sendViaVonage($phone, $message);
                case 'africastalking':
                    return $this->sendViaAfricasTalking($phone, $message);
                case 'termii':
                    return $this->sendViaTermii($phone, $message);
                default:
                    return ['success' => false, 'error' => 'Invalid provider'];
            }
        } catch (\Exception $e) {
            Log::error("SMS Error ({$provider}): " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function sendViaTwilio($phone, $message)
    {
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->settings->sms_twilio_sid}/Messages.json";
        
        $response = Http::asForm()
            ->withBasicAuth($this->settings->sms_twilio_sid, $this->settings->sms_twilio_token)
            ->post($url, [
                'From' => $this->settings->sms_twilio_from,
                'To' => $phone,
                'Body' => $message,
            ]);
        
        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'message_id' => $data['sid'] ?? null,
                'cost' => isset($data['price']) ? abs((float)$data['price']) : 0.01,
            ];
        }
        
        return ['success' => false, 'error' => $response->body()];
    }

    protected function sendViaMSG91($phone, $message)
    {
        $url = 'https://api.msg91.com/api/v5/flow/';
        
        $response = Http::withHeaders([
            'authkey' => $this->settings->sms_msg91_authkey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'sender' => $this->settings->sms_msg91_sender_id,
            'route' => $this->settings->sms_msg91_route ?? '4',
            'country' => '91',
            'sms' => [[
                'message' => $message,
                'to' => [$phone]
            ]]
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'message_id' => $data['request_id'] ?? null,
                'cost' => 0.01,
            ];
        }
        
        return ['success' => false, 'error' => $response->body()];
    }

    protected function sendViaPlivo($phone, $message)
    {
        $url = "https://api.plivo.com/v1/Account/{$this->settings->sms_plivo_auth_id}/Message/";
        
        $response = Http::withBasicAuth($this->settings->sms_plivo_auth_id, $this->settings->sms_plivo_auth_token)
            ->asJson()
            ->post($url, [
                'src' => $this->settings->sms_plivo_from,
                'dst' => $phone,
                'text' => $message,
            ]);
        
        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'message_id' => $data['message_uuid'][0] ?? null,
                'cost' => 0.01,
            ];
        }
        
        return ['success' => false, 'error' => $response->body()];
    }

    protected function sendViaVonage($phone, $message)
    {
        $url = 'https://rest.nexmo.com/sms/json';
        
        $response = Http::post($url, [
            'api_key' => $this->settings->sms_vonage_key,
            'api_secret' => $this->settings->sms_vonage_secret,
            'from' => $this->settings->sms_vonage_from,
            'to' => $phone,
            'text' => $message,
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['messages'][0]['status']) && $data['messages'][0]['status'] == '0') {
                return [
                    'success' => true,
                    'message_id' => $data['messages'][0]['message-id'] ?? null,
                    'cost' => 0.01,
                ];
            }
            return ['success' => false, 'error' => $data['messages'][0]['error-text'] ?? 'Unknown error'];
        }
        
        return ['success' => false, 'error' => $response->body()];
    }

    protected function sendViaAfricasTalking($phone, $message)
    {
        $url = 'https://api.africastalking.com/version1/messaging';
        
        $response = Http::withHeaders([
            'apiKey' => $this->settings->sms_africastalking_api_key,
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ])->asForm()->post($url, [
            'username' => $this->settings->sms_africastalking_username,
            'to' => $phone,
            'message' => $message,
            'from' => $this->settings->sms_africastalking_from,
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['SMSMessageData']['Recipients'][0]['status']) && 
                $data['SMSMessageData']['Recipients'][0]['status'] === 'Success') {
                $cost = $data['SMSMessageData']['Recipients'][0]['cost'] ?? '0';
                return [
                    'success' => true,
                    'message_id' => $data['SMSMessageData']['Recipients'][0]['messageId'] ?? null,
                    'cost' => (float)preg_replace('/[^0-9.]/', '', $cost),
                ];
            }
            return ['success' => false, 'error' => $data['SMSMessageData']['Message'] ?? 'Unknown error'];
        }
        
        return ['success' => false, 'error' => $response->body()];
    }

    protected function sendViaTermii($phone, $message)
    {
        $url = 'https://api.ng.termii.com/api/sms/send';
        
        $response = Http::asJson()->post($url, [
            'api_key' => $this->settings->sms_termii_api_key,
            'to' => $phone,
            'from' => $this->settings->sms_termii_sender_id,
            'sms' => $message,
            'type' => 'plain',
            'channel' => $this->settings->sms_termii_channel ?? 'generic',
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['message_id'])) {
                return [
                    'success' => true,
                    'message_id' => $data['message_id'],
                    'cost' => 0.01,
                ];
            }
            return ['success' => false, 'error' => $data['message'] ?? 'Unknown error'];
        }
        
        return ['success' => false, 'error' => $response->body()];
    }

    protected function isEventEnabled($eventType)
    {
        $eventMap = [
            'registration' => 'sms_on_registration',
            'deposit' => 'sms_on_deposit',
            'withdrawal' => 'sms_on_withdrawal',
            'transfer' => 'sms_on_transfer',
            'login' => 'sms_on_login',
            'password_change' => 'sms_on_password_change',
        ];

        if (!isset($eventMap[$eventType])) {
            return true; // Allow general/test messages
        }

        return (bool) $this->settings->{$eventMap[$eventType]};
    }

    protected function checkRateLimit($userId)
    {
        $window = $this->settings->sms_rate_limit_window ?? 60; // minutes
        $limit = $this->settings->sms_rate_limit_per_user ?? 10;

        $count = DB::table('sms_logs')
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subMinutes($window))
            ->count();

        return $count < $limit;
    }

    protected function logSms($userId, $phone, $message, $eventType, $result)
    {
        DB::table('sms_logs')->insert([
            'user_id' => $userId,
            'phone_number' => $phone,
            'message' => $message,
            'provider' => $this->settings->sms_primary_provider,
            'event_type' => $eventType,
            'status' => $result['success'] ? 'success' : 'failed',
            'response' => json_encode($result),
            'estimated_cost' => $result['cost'] ?? 0,
            'message_id' => $result['message_id'] ?? null,
            'sent_at' => $result['success'] ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
