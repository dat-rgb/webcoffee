<div class="col-lg-4">
    <div class="sidebar-section">
        <div class="recent-posts">
            <h4>Chính sách</h4>
            <ul>
                @foreach ($chinhSachs as $cs)
                <li><a href="{{ route('blog.detail', $cs->slug) }}">{{ $cs->tieu_de }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="archive-posts">
            <h4>Bài viết nổi bật<table></table></h4>
            <ul>
                @foreach ($blogHots as $hot)
                <li><a href="{{ route('blog.detail', $hot->slug) }}">{{ $hot->tieu_de }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="tag-section">
            <h4>Tags</h4>
            <ul>
                @foreach ($danhMucBlog as $dm)
                <li><a href="#">{{ $dm->ten_danh_muc_blog }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</div>