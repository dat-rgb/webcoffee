<?php

namespace App\Http\Controllers\admins;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\DanhMucBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class AdminBlogController extends Controller
{
    public function index(){

        $blogs = Blog::with('danhMuc')->where('trang_thai',1)->get();

        $viewData = [
            'title' => 'Admin Blogs | CDMT Coffee & Tea',
            'subtitle' => 'Danh sách Blogs',
            'blogs' => $blogs,    
        ];

        return view('admins.blogs.index',$viewData);
    }

    public function showFormBlog(){

        $danhMucBlogs = DanhMucBlog::where('trang_thai',1)->get();

        $viewData = [
            'title' => 'Thêm mới Blog | CDMT Coffee & Tea',
            'subtitle' => 'Thêm mới Blog',
            'danhMucBlogs' => $danhMucBlogs,    
        ];

        return view('admins.blogs.blog_form',$viewData);
    }

    public function add(Request $request)
    {
        $request->validate([
            'ma_danh_muc' => 'required|exists:danh_muc_blogs,ma_danh_muc_blog',
            'tieu_de' => 'required|string|max:255|min:2',
            'sub_tieu_de' => 'nullable|string|max:255',
            'hinh_anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'noi_dung' => 'required|string',
            'trang_thai' => 'required|in:0,1',
            'tac_gia' => 'required|string|max:255',
        ], [
            'ma_danh_muc.required' => 'Danh mục blog là bắt buộc.',
            'ma_danh_muc.exists' => 'Danh mục blog không tồn tại.',
            'tieu_de.required' => 'Tiêu đề là bắt buộc.',
            'tieu_de.max' => 'Tiêu đề không quá 255 ký tự.',
            'noi_dung.required' => 'Nội dung là bắt buộc.',
            'hinh_anh.image' => 'Tệp phải là ảnh.',
            'hinh_anh.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'hinh_anh.max' => 'Ảnh không quá 2MB.',
            'tac_gia.required' => 'Tác giả là bắt buộc.',
        ]);

        $slug = Str::slug($request->tieu_de);

        // Kiểm tra trùng slug
        if (Blog::where('slug', $slug)->exists()) {
            toastr()->error('Tiêu đề blog đã trùng, vui lòng chọn tiêu đề khác');
            return redirect()->back()->withInput();
        }

        // Xử lý ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('hinh_anh')) {
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('blogs', $image, $imageName);
            $imagePath = 'blogs/' . $imageName;
        }

        // Tạo blog mới
        Blog::create([
            'ma_danh_muc_blog' => $request->ma_danh_muc,
            'tieu_de'           => $request->tieu_de,
            'slug'              => $slug,
            'sub_tieu_de'       => $request->sub_tieu_de,
            'hinh_anh'          => $imagePath,
            'noi_dung'          => $request->noi_dung,
            'trang_thai'        => $request->trang_thai,
            'tac_gia'           => $request->tac_gia,
            'ngay_dang'         => now(), // hoặc dùng $request->ngay_dang nếu cần
        ]);

        toastr()->success('Thêm blog mới thành công.');
        return redirect()->route('admin.blog.index');
    }

    public function tinymceUpload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads/tinymce', $filename, 'public');
            $url = asset('storage/uploads/tinymce/' . $filename);

            return response()->json([
                'location' => $url
            ]);
        }
        return response()->json(['error' => 'No file uploaded.'], 400);
    }       
}
