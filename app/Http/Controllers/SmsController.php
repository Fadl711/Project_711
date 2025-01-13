<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class SmsController extends Controller
{
    public function sendSms(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'message' => 'required|string',
        ]);

        $to = $request->input('to');
        $message = $request->input('message');

        // إرسال الرسالة عبر Twilio
        $response = $this->sendSmsApi($to, $message);

        if ($response) {
            return redirect()->back()->with('success', 'SMS sent successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to send SMS.');
        }
    }


    // دالة لمحاكاة إرسال SMS
    private function sendSmsApi($to, $message)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_FROM_NUMBER');
    
        $client = new Client($sid, $token);
    
        try {
            $client->messages->create($to, [
                'from' => $from,
                'body' => $message,
            ]);
            return true;
        } catch (\Exception $e) {
            return $e->getMessage(); // إرجاع رسالة الخطأ
        }
    }
}
