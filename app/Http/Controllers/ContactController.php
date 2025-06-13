<?php

namespace App\Http\Controllers;

use App\Models\LienHe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function submitContactForm(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'phone'    => 'required|digits:10',
            'subject'  => 'required|string|max:255',
            'message'  => 'required|string',
            'g-recaptcha-response' => 'required',
        ]);

        // Verify Google reCAPTCHA
        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);
        //dd($captchaResponse->json());
        if (!$captchaResponse->json('success')) {
            return back()->withErrors(['captcha' => 'Xác minh reCAPTCHA thất bại'])->withInput();
        }

        $maKH = Auth::check() ? Auth::user()->khachHang->ma_khach_hang : null;
        try {
            LienHe::create([
                'ma_khach_hang' => $maKH,
                'ho_ten'         => $request->name,
                'so_dien_thoai'  => $request->phone,
                'email'          => $request->email,
                'tieu_de'        => $request->subject,
                'noi_dung'       => $request->message,
                'ngay_gui'       => Carbon::now(),
                'trang_thai'     => 0,
            ]);
        } catch (\Exception $e) {
            dd('Insert failed: '.$e->getMessage());
        }
        toastr()->success('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.');
        return redirect()->back();
    }
}
