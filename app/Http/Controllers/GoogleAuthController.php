<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->scopes(['https://www.googleapis.com/auth/drive.file'])->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $user = Socialite::driver('google')->user();

            // حفظ البيانات في الجلسة بطريقة صريحة
            $request->session()->put('google_access_token', $user->token);
            $request->session()->save(); // إجبار الحفظ الفوري

            // للتأكد من حفظ الجلسة (لأغراض Debugging)
            logger('Session saved:', [
                'token' => $request->session()->get('google_access_token'),
                'session_id' => $request->session()->getId()
            ]);

            return redirect()->route('backup.google');
        } catch (\Exception $e) {
            logger('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors('فشل المصادقة مع جوجل');
        }
    }
}
