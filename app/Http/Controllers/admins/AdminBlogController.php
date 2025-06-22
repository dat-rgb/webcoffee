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
    public function index(Request $request)
    {
        $query = Blog::with('danhMuc')->where('trang_thai', 1);

        if ($request->filled('search')) {
            $keyword = $request->input('search');
            $query->where(function ($q) use ($keyword) {
                $q->where('tieu_de', 'like', "%$keyword%")
                ->orWhere('ma_blog', 'like', "%$keyword%")
                ->orWhere('tac_gia', 'like', "%$keyword%");
            });
        }
        if ($request->filled('ma_danh_muc')) {
            $query->where('ma_danh_muc_blog', $request->input('ma_danh_muc'));
        }

        $blogs = $query->orderByDesc('ma_blog')
                    ->paginate(10)  
                    ->withQueryString();
        
        $danhMucBlogs = DanhMucBlog::where('trang_thai', 1)->get();

        if ($request->page > $blogs->lastPage()) {
            return redirect()->route('admin.blog.index', ['page' => $blogs->lastPage()]);
        }

        return view('admins.blogs.index', [
            'title' => 'Admin Blogs | CDMT Coffee & Tea',
            'subtitle' => 'Danh sách Blogs',
            'danhMucBlogs' => $danhMucBlogs,
            'blogs' => $blogs,
        ]);
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
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('blogs', $filename, 'public');
            $url = asset('storage/' . $path);

            return response()->json(['location' => $url]);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }
    public function showFormEdit($id)
    {
        $blog = Blog::with('danhMuc')->where('ma_blog', $id)->first();
        if (!$blog) {
            toastr()->error('Blog không tồn tại');
            return redirect()->back();
        }

        $danhMucBlogs = DanhMucBlog::all(); 

        $viewData = [
            'title' => 'Cập nhật Blog',
            'subtitle' => 'Chỉnh sửa bài viết',
            'danhMucBlogs' => $danhMucBlogs,
            'blog' => $blog,
        ];

        return view('admins.blogs.blog_edit', $viewData);
    }
    public function updateBlog(Request $request, $id)
    {
        $blog = Blog::where('ma_blog',$id)->first();
        if (!$blog) {
            toastr()->error('Blog không tồn tại');
            return redirect()->back();
        }

        $validated = $request->validate([
            'tieu_de' => 'required|min:2|max:255',
            'sub_tieu_de' => 'nullable|max:255',
            'noi_dung' => 'required|min:10',
            'tac_gia' => 'required|min:2|max:255',
            'ma_danh_muc' => 'required|exists:danh_muc_blogs,ma_danh_muc_blog',
            'trang_thai' => 'required|in:0,1',
            'hinh_anh' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $blog->tieu_de = $validated['tieu_de'];
        $blog->sub_tieu_de = $validated['sub_tieu_de'];
        $blog->noi_dung = $validated['noi_dung'];
        $blog->tac_gia = $validated['tac_gia'];
        $blog->ma_danh_muc_blog = $validated['ma_danh_muc'];
        $blog->trang_thai = $validated['trang_thai'];
        $blog->hot = $request->input('hot', 0);
        $blog->is_new = $request->input('is_new', 0);

        if ($request->hasFile('hinh_anh')) {
            if ($blog->hinh_anh && Storage::disk('public')->exists($blog->hinh_anh)) {
                Storage::disk('public')->delete($blog->hinh_anh);
            }

            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('blogs', $imageName, 'public');
            $blog->hinh_anh = $path;
        }

        $blog->save();

        toastr()->success('Cập nhật blog thành công!');
        return redirect()->route('admin.blog.index');
    }
}
