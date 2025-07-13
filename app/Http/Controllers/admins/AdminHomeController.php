<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Banners;
use App\Models\Settings;
use App\Models\ThongTinWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminHomeController extends Controller
{
    public function thongTinWebsite(){

        $thongTinWebsite = ThongTinWebsite::first(); 

        $viewData = [
            'title' => 'Thông tin website | CDMT Coffee & tea',
            'subtitle' => 'Thông tin website',
            'thongTinWebsite' => $thongTinWebsite,
        ];
        return view('admins.pages.thong_tin_website', $viewData);
    }

    public function updateThongTinWebsite(Request $request)
    {
        $request->validate([
            'ten_website'   => 'required|string|max:100|min:2',
            'so_dien_thoai' => 'required|string|max:20',
            'email'         => 'required|email|max:255',
            'dia_chi'       => 'required|string',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon'       => 'nullable|image|mimes:jpeg,png,jpg,ico|max:1024',
            'facebook_url'  => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'zalo_url'      => 'nullable|url',
            'youtube_url'   => 'nullable|url',
            'tiktok_url'    => 'nullable|url',
            'mo_ta'         => 'nullable|string',
            'tu_khoa'       => 'nullable|string',
            'footer_text'   => 'nullable|string',
        ],
        [
            'ten_website.required' => 'Tên Website không được để trống.',
            'ten_website.max' => 'Tên Website không được quá 100 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'dia_chi.required' => 'Địa chỉ là bắt buộc.'
        ]);

        $thongTin = ThongTinWebsite::first();

        $thongTin->ten_website = $request->ten_website;
        $thongTin->so_dien_thoai = $request->so_dien_thoai;
        $thongTin->email = $request->email;
        $thongTin->dia_chi = $request->dia_chi;
        $thongTin->facebook_url = $request->facebook_url;
        $thongTin->instagram_url = $request->instagram_url;
        $thongTin->zalo_url = $request->zalo_url;
        $thongTin->youtube_url = $request->youtube_url;
        $thongTin->tiktok_url = $request->tiktok_url;
        $thongTin->mo_ta = $request->mo_ta;
        $thongTin->tu_khoa = $request->tu_khoa;
        $thongTin->footer_text = $request->footer_text;
        $thongTin->ban_do = $request->ban_do;

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoPath = public_path('images/website/logo.png');
            $logoFile->move(dirname($logoPath), basename($logoPath));
            $thongTin->logo = 'website/logo.png';
        }

        if ($request->hasFile('favicon')) {
            $faviconFile = $request->file('favicon');
            $faviconPath = public_path('images/website/favicon.png');
            $faviconFile->move(dirname($faviconPath), basename($faviconPath));
            $thongTin->favicon = 'website/favicon.png';
        }


        $thongTin->save();

        toastr()->success('Cập nhật thông tin thành công!');
        return redirect()->back();
    }

    public function banners(){
        $banners = [
            'top_banner' => Banners::where('vi_tri', 'top_banner')->get(),
            'main_slider' => Banners::where('vi_tri', 'main_slider')->get(),
            'about_section_bg' => Banners::where('vi_tri', 'about_section_bg')->get(),
            'store_gallery' => Banners::where('vi_tri', 'store_gallery')->get(),
        ];

        $viewData = [
            'title' => 'Banner website | CDMT Coffee & tea',
            'subtitle' => 'Banner website',
            'banners' => $banners,
        ];
        return view('admins.pages.banner_page', $viewData);
    }
    public function destroy($id)
    {
        $banner = Banners::findOrFail($id);

        $countByPosition = Banners::where('vi_tri', $banner->vi_tri)->count();

        $minLimits = [
            'top_banner' => 1,
            'about_section_bg' => 1,
            'main_slider' => 3,
            'store_gallery' => 5,
        ];

        if (isset($minLimits[$banner->vi_tri]) && $countByPosition <= $minLimits[$banner->vi_tri]) {
            toastr()->error('Không thể xóa banner.');
            return redirect()->back();
        }

        // Xóa ảnh nếu có
        if ($banner->hinh_anh && Storage::disk('public')->exists($banner->hinh_anh)) {
            Storage::disk('public')->delete($banner->hinh_anh);
        }

        // Lưu vị trí trước khi xóa
        $viTri = $banner->vi_tri;

        $banner->delete();

        // Sau khi xóa, cập nhật lại thứ tự trong group đó
        $banners = Banners::where('vi_tri', $viTri)->orderBy('thu_tu')->get();
        foreach ($banners as $index => $item) {
            $item->thu_tu = $index + 1;
            $item->save();
        }

        toastr()->success('Đã xóa banner thành công.');
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'vi_tri' => 'required|string',
            'hinh_anh' => 'required|image',
            'tieu_de' => 'nullable|string',
            'sub_tieu_de' => 'nullable|string',
            'link_dich' => 'nullable|string',
        ]);

        $path = $request->file('hinh_anh')->store('banners', 'public');

        Banners::create([
            'vi_tri' => $request->vi_tri,
            'hinh_anh' => $path,
            'tieu_de' => $request->tieu_de,
            'sub_tieu_de' => $request->sub_tieu_de,
            'link_dich' => $request->link_dich,
            'thu_tu' => Banners::where('vi_tri', $request->vi_tri)->max('thu_tu') + 1,
            'trang_thai' => 1
        ]);
        toastr()->success('Đã thêm banner thành công');
        return redirect()->back();
    }
    public function updateGroup(Request $request, $position)
    {
        $banners = Banners::where('vi_tri', $position)->get();

        foreach ($banners as $banner) {
            $id = $banner->id;

            // Cập nhật các trường
            $banner->tieu_de = $request->input("tieu_de_$id");
            $banner->noi_dung = $request->input("noi_dung_$id");
            $banner->link_dich = $request->input("link_dich_$id");

            // Cập nhật hình ảnh nếu có
            if ($request->hasFile("hinh_anh_$id")) {
                $file = $request->file("hinh_anh_$id");

                if ($banner->hinh_anh && Storage::disk('public')->exists($banner->hinh_anh)) {
                    Storage::disk('public')->delete($banner->hinh_anh);
                }

                $path = $file->store('banners', 'public');
                $banner->hinh_anh = $path;
            }

            $banner->save();
        }
        toastr()->success('Đã cập nhật banner thành công');
        return redirect()->back();
    }

    public function settings(){

        $settings = Settings::first(); 

        $viewData = [
            'title' => 'Cài đặc hệ thống',
            'subtitle' => 'Cài đặc hệ thống',
            'settings' => $settings,
        ];
        return view('admins.pages.settings', $viewData);
    }
    public function updateSettings(Request $request)
    {
        $validatedData = $request->validate([
            'phi_ship' => 'required|numeric|min:0',
            'nguong_mien_phi_ship' => 'required|numeric|min:0',
            'vat_mac_dinh' => 'required|numeric|min:0|max:100',
            'so_luong_toi_thieu' => 'required|integer|min:1',
            'so_luong_toi_da' => 'required|integer|min:1',
            'ty_le_diem_thuong' => 'required|numeric|min:1',
            'ban_kinh_giao_hang' => 'required|numeric|min:0',
            'ban_kinh_hien_thi_cua_hang' => 'required|numeric|min:0',
            'che_do_bao_tri' => 'nullable|boolean',
        ]);

        // Validate logic: số lượng tối thiểu không được lớn hơn tối đa
        if ((int) $validatedData['so_luong_toi_thieu'] > (int) $validatedData['so_luong_toi_da']) {
            toastr()->error('Số lượng tối thiểu không được lớn hơn số lượng tối đa.');
            return back();
        }

        $settings = Settings::first();

        // Nếu chưa có thì tạo mới
        if (!$settings) {
            $settings = new Settings();
        }

        // Gán dữ liệu
        $settings->phi_ship = $validatedData['phi_ship'];
        $settings->nguong_mien_phi_ship = $validatedData['nguong_mien_phi_ship'];
        $settings->vat_mac_dinh = $validatedData['vat_mac_dinh'];
        $settings->so_luong_toi_thieu = $validatedData['so_luong_toi_thieu'];
        $settings->so_luong_toi_da = $validatedData['so_luong_toi_da'];
        $settings->ty_le_diem_thuong = $validatedData['ty_le_diem_thuong'];
        $settings->ban_kinh_giao_hang = $validatedData['ban_kinh_giao_hang'];
        $settings->ban_kinh_hien_thi_cua_hang = $validatedData['ban_kinh_hien_thi_cua_hang'];
        $settings->che_do_bao_tri = $request->has('che_do_bao_tri') ? 1 : 0;

        $settings->save();

        toastr()->success('Cập nhật cài đặc thành công');
        return redirect()->back();
    }
}
