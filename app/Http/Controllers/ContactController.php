<?php

namespace App\Http\Controllers;

use App\Mail\ReplyMail;
use App\Models\LienHe;
use GuzzleHttp\Psr7\FnStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

    public function showListContact(Request $request)
    {
        $status   = $request->input('trang_Thai', 0);      // 0 = chưa xử lý (default)
        $search   = $request->input('search');
        $sortBy   = $request->input('sort_by', 'latest');  // latest | oldest

        $query = LienHe::where('trang_thai', $status);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ho_ten',  'like', "%$search%")
                ->orWhere('email',  'like', "%$search%")
                ->orWhere('tieu_de','like', "%$search%");
            });
        }

        $query->orderBy('ngay_gui', $sortBy === 'oldest' ? 'asc' : 'desc');

        $contacts = $query->paginate(10)->appends($request->query());

        if ($request->filled('page') && $request->page > $contacts->lastPage()) {
            return redirect()->route('admin.contact.list',
                array_merge($request->except('page'), ['page' => $contacts->lastPage()]));
        }

        return view('admins.pages.contact_list', [
            'title'    => 'Danh sách liên hệ',
            'subtitle' => 'Danh sách liên hệ',
            'contacts' => $contacts,
            'status'   => $status,
            'sortBy'   => $sortBy,
        ]);
    }
    public function bulkMarkRead(Request $req) {
        $ids = $req->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['ok' => false, 'message' => 'Dữ liệu không hợp lệ'], 400);
        }

        LienHe::whereIn('id', $ids)->update(['trang_thai' => 1]);
        return response()->json(['ok' => true]);
    }

    public function bulkMarkUnread(Request $req) {
        $ids = $req->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['ok' => false, 'message' => 'Dữ liệu không hợp lệ'], 400);
        }

        LienHe::whereIn('id', $ids)->update(['trang_thai' => 0]);
        return response()->json(['ok' => true]);
    }

    public function bulkDelete(Request $req) {
        $ids = $req->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['ok' => false, 'message' => 'Dữ liệu không hợp lệ'], 400);
        }

        LienHe::whereIn('id', $ids)->delete();
        return response()->json(['ok' => true]);
    }

    public function sendMailContact(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:lien_hes,id',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string',
        ]);

        $contact = LienHe::findOrFail($request->contact_id);

        try {   
            Mail::to($contact->email)->send(new ReplyMail(
                $contact->ho_ten,
                $contact->email,
                $request->message,
                $request->subject
            ));

            $contact->update([
                'trang_thai' => 1
            ]);

            toastr()->success('Gửi phản hồi thành công!');
            return redirect()->back();

        } catch (\Exception $e) {
            toastr()->error('Gửi email thất bại. Lỗi: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
