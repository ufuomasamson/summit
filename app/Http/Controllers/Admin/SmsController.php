<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    public function index()
    {
        $settings = Settings::where('id', 1)->first();
        
        $sms_settings = DB::table('sms_settings')->first();
        
        if (!$sms_settings) {
            DB::table('sms_settings')->insert([
                'enabled' => false,
                'provider' => 'twilio',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $sms_settings = DB::table('sms_settings')->first();
        }
        
        $total_sent = DB::table('sms_logs')->count();
        $success_count = DB::table('sms_logs')->where('status', 'success')->count();
        $failed_count = DB::table('sms_logs')->where('status', 'failed')->count();
        $pending_count = DB::table('sms_logs')->where('status', 'pending')->count();
        
        $recent_sms = DB::table('sms_logs')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        return view('admin.Settings.sms', [
            'title' => 'SMS Settings',
            'settings' => $settings,
            'sms_settings' => $sms_settings,
            'total_sent' => $total_sent,
            'success_count' => $success_count,
            'failed_count' => $failed_count,
            'pending_count' => $pending_count,
            'recent_sms' => $recent_sms,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'sms_enabled' => 'required|in:yes,no',
            'sms_primary_provider' => 'required|in:twilio,msg91,plivo,vonage,africastalking,termii',
        ];
        
        $this->validate($request, $rules);
        
        DB::table('sms_settings')->where('id', 1)->update([
            'sms_enabled' => $request->sms_enabled,
            'sms_primary_provider' => $request->sms_primary_provider,
            'sms_fallback_provider_1' => $request->sms_fallback_provider_1,
            'sms_fallback_provider_2' => $request->sms_fallback_provider_2,
            
            // Twilio
            'sms_twilio_sid' => $request->sms_twilio_sid,
            'sms_twilio_token' => $request->sms_twilio_token,
            'sms_twilio_from' => $request->sms_twilio_from,
            
            // MSG91
            'sms_msg91_authkey' => $request->sms_msg91_authkey,
            'sms_msg91_sender_id' => $request->sms_msg91_sender_id,
            'sms_msg91_route' => $request->sms_msg91_route ?? '4',
            
            // Plivo
            'sms_plivo_auth_id' => $request->sms_plivo_auth_id,
            'sms_plivo_auth_token' => $request->sms_plivo_auth_token,
            'sms_plivo_from' => $request->sms_plivo_from,
            
            // Vonage (Nexmo)
            'sms_vonage_key' => $request->sms_vonage_key,
            'sms_vonage_secret' => $request->sms_vonage_secret,
            'sms_vonage_from' => $request->sms_vonage_from,
            
            // Africa's Talking
            'sms_africastalking_username' => $request->sms_africastalking_username,
            'sms_africastalking_api_key' => $request->sms_africastalking_api_key,
            'sms_africastalking_from' => $request->sms_africastalking_from,
            
            // Termii
            'sms_termii_api_key' => $request->sms_termii_api_key,
            'sms_termii_sender_id' => $request->sms_termii_sender_id,
            'sms_termii_channel' => $request->sms_termii_channel ?? 'generic',
            
            // Events
            'sms_on_registration' => $request->has('sms_on_registration'),
            'sms_on_deposit' => $request->has('sms_on_deposit'),
            'sms_on_withdrawal' => $request->has('sms_on_withdrawal'),
            'sms_on_transfer' => $request->has('sms_on_transfer'),
            'sms_on_login' => $request->has('sms_on_login'),
            'sms_on_password_change' => $request->has('sms_on_password_change'),
            
            // Additional settings
            'sms_rate_limiting_enabled' => $request->has('sms_rate_limiting_enabled'),
            'sms_rate_limit_per_user' => $request->sms_rate_limit_per_user ?? 10,
            'sms_rate_limit_window' => $request->sms_rate_limit_window ?? 60,
            'sms_email_fallback_enabled' => $request->has('sms_email_fallback_enabled'),
            'sms_cost_tracking_enabled' => $request->has('sms_cost_tracking_enabled'),
            
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'SMS settings updated successfully');
    }

    public function test(Request $request)
    {
        $this->validate($request, [
            'test_phone' => 'required|string',
            'test_message' => 'required|string|max:160',
        ]);
        
        $sms_settings = DB::table('sms_settings')->first();
        
        if (!$sms_settings || $sms_settings->sms_enabled != 'yes') {
            return response()->json([
                'success' => false,
                'message' => 'SMS service is not enabled'
            ], 400);
        }
        
        try {
            $result = $this->sendTestSMS(
                $sms_settings,
                $request->test_phone,
                $request->test_message
            );
            
            DB::table('sms_logs')->insert([
                'phone_number' => $request->test_phone,
                'message' => $request->test_message,
                'provider' => $sms_settings->sms_primary_provider,
                'event_type' => 'test',
                'status' => $result['success'] ? 'success' : 'failed',
                'response' => json_encode($result),
                'estimated_cost' => $result['cost'] ?? 0,
                'message_id' => $result['message_id'] ?? null,
                'sent_at' => $result['success'] ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test SMS sent successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send SMS: ' . $result['error']
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('SMS Test Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sendTestSMS($settings, $phone, $message)
    {
        try {
            switch ($settings->sms_primary_provider) {
                case 'twilio':
                    return $this->sendViaTwilio($settings, $phone, $message);
                case 'msg91':
                    return $this->sendViaMSG91($settings, $phone, $message);
                case 'plivo':
                    return $this->sendViaPlivo($settings, $phone, $message);
                case 'vonage':
                    return $this->sendViaVonage($settings, $phone, $message);
                case 'africastalking':
                    return $this->sendViaAfricasTalking($settings, $phone, $message);
                case 'termii':
                    return $this->sendViaTermii($settings, $phone, $message);
                default:
                    return ['success' => false, 'error' => 'Invalid SMS provider'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function sendViaTwilio($settings, $phone, $message)
    {
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$settings->sms_twilio_sid}/Messages.json";
        
        $response = Http::asForm()
            ->withBasicAuth($settings->sms_twilio_sid, $settings->sms_twilio_token)
            ->post($url, [
                'From' => $settings->sms_twilio_from,
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

    private function sendViaMSG91($settings, $phone, $message)
    {
        $url = 'https://api.msg91.com/api/v5/flow/';
        
        $response = Http::withHeaders([
            'authkey' => $settings->sms_msg91_authkey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'sender' => $settings->sms_msg91_sender_id,
            'route' => $settings->sms_msg91_route ?? '4',
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

    private function sendViaPlivo($settings, $phone, $message)
    {
        $url = "https://api.plivo.com/v1/Account/{$settings->sms_plivo_auth_id}/Message/";
        
        $response = Http::withBasicAuth($settings->sms_plivo_auth_id, $settings->sms_plivo_auth_token)
            ->asJson()
            ->post($url, [
                'src' => $settings->sms_plivo_from,
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

    private function sendViaVonage($settings, $phone, $message)
    {
        $url = 'https://rest.nexmo.com/sms/json';
        
        $response = Http::post($url, [
            'api_key' => $settings->sms_vonage_key,
            'api_secret' => $settings->sms_vonage_secret,
            'from' => $settings->sms_vonage_from,
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

    private function sendViaAfricasTalking($settings, $phone, $message)
    {
        $url = 'https://api.africastalking.com/version1/messaging';
        
        $response = Http::withHeaders([
            'apiKey' => $settings->sms_africastalking_api_key,
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
        ])->asForm()->post($url, [
            'username' => $settings->sms_africastalking_username,
            'to' => $phone,
            'message' => $message,
            'from' => $settings->sms_africastalking_from,
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

    private function sendViaTermii($settings, $phone, $message)
    {
        $url = 'https://api.ng.termii.com/api/sms/send';
        
        $response = Http::asJson()->post($url, [
            'api_key' => $settings->sms_termii_api_key,
            'to' => $phone,
            'from' => $settings->sms_termii_sender_id,
            'sms' => $message,
            'type' => 'plain',
            'channel' => $settings->sms_termii_channel ?? 'generic',
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
}
