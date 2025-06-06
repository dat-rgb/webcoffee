<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = [
            [
                'ma_blog' => 1,
                'ma_danh_muc_blog' => 1,
                'tieu_de' => 'Giới thiệu',
                'slug' => 'gioi-thieu',
                'sub_tieu_de' => 'CDMT Coffee & Tea Xin chào!',
                'hinh_anh' => 'blogs/about.jpeg',
                'noi_dung' => '
                    <p><strong>CDMT Coffee &amp; Tea</strong> là không gian dành cho những tâm hồn yêu thích sự tinh tế trong từng tách cà phê, vị thanh mát của trà trái cây và cảm giác thư giãn giữa cuộc sống hối hả.</p>
                    <p>Chúng tôi tin rằng mỗi ly nước không chỉ đơn thuần là thức uống mà còn là trải nghiệm, là câu chuyện được kể qua từng hương vị.</p>
                    <p>Nguyên liệu được tuyển chọn kỹ lưỡng từ những nhà cung cấp uy tín, kết hợp với quy trình pha chế chuẩn mực nhằm đảm bảo chất lượng tuyệt hảo nhất cho khách hàng.</p>
                    <p>Không gian quán được thiết kế hiện đại, trẻ trung và thoải mái, là điểm đến lý tưởng để học tập, làm việc hoặc đơn giản là tận hưởng những giây phút thư giãn bên bạn bè.</p>
                    <p>Tại CDMT Coffee & Tea, chúng tôi không chỉ tạo ra đồ uống ngon mà còn mang đến sự kết nối, chia sẻ và tận hưởng.</p>
                ',
                'trang_thai' => 1,
                'luot_xem' => 0,
                'tac_gia' => 'CDMT Coffee & Tea',
                'ngay_dang' => '2025-06-03 13:26:15',
                'hot' => 0,
                'is_new' => 0,
                'do_uu_tien' => 0,
                'created_at' => Carbon::parse('2025-06-03 06:26:15'),
                'updated_at' => Carbon::parse('2025-06-03 06:26:15'),
            ],

            [
                'ma_blog' => 2,
                'ma_danh_muc_blog' => 3,
                'tieu_de' => 'Chuyện Trà – Hương vị của sự chậm rãi',
                'slug' => 'chuyen-tra-huong-vi-cua-su-cham-rai',
                'sub_tieu_de' => 'Mỗi tách trà là một hành trình về phía bình yên.',
                'hinh_anh' => 'blogs/1748932688_683e98505e96c.jpg',
                'noi_dung' => '<p>Giữa nhịp sống vội vã, <strong>trà</strong> không chỉ là một thức uống – mà là một <strong>khoảnh khắc để thở</strong>...</p>',
                'trang_thai' => 1,
                'luot_xem' => 0,
                'tac_gia' => 'Chi Dat',
                'ngay_dang' => '2025-06-03 13:38:08',
                'hot' => 0,
                'is_new' => 0,
                'do_uu_tien' => 0,
                'created_at' => Carbon::parse('2025-06-03 06:38:08'),
                'updated_at' => Carbon::parse('2025-06-03 06:38:08'),
            ],

            [
                'ma_blog' => 3,
                'ma_danh_muc_blog' => 2,
                'tieu_de' => 'Chính sách bảo mật',
                'slug' => 'chinh-sach-bao-mat',
                'sub_tieu_de' => 'Cam kết bảo vệ thông tin khách hàng',
                'hinh_anh' => null,
                'noi_dung' => '
                    <p>Tại <strong>CDMT Coffee & Tea</strong>, việc bảo mật thông tin khách hàng luôn được chúng tôi đặt lên hàng đầu.</p>
                    <p>Chúng tôi cam kết:</p>
                    <ul>
                        <li>Bảo vệ mọi thông tin cá nhân và dữ liệu khách hàng không bị tiết lộ ra bên ngoài.</li>
                        <li>Chỉ sử dụng thông tin khách hàng cho mục đích phục vụ và nâng cao trải nghiệm dịch vụ.</li>
                        <li>Áp dụng các biện pháp kỹ thuật và quản lý an toàn để ngăn chặn truy cập trái phép, mất mát hoặc thay đổi dữ liệu.</li>
                    </ul>
                    <p>Nếu có bất kỳ thắc mắc nào về chính sách bảo mật, khách hàng có thể liên hệ với chúng tôi qua kênh hỗ trợ để được giải đáp nhanh chóng.</p>
                ',
                'trang_thai' => 1,
                'luot_xem' => 0,
                'tac_gia' => 'CDMT Coffee & Tea',
                'ngay_dang' => now(),
                'hot' => 0,
                'is_new' => 1,
                'do_uu_tien' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'ma_blog' => 4,
                'ma_danh_muc_blog' => 2,
                'tieu_de' => 'Chính sách mua hàng',
                'slug' => 'chinh-sach-mua-hang',
                'sub_tieu_de' => 'Cam kết mang đến trải nghiệm mua hàng tốt nhất',
                'hinh_anh' => null,
                'noi_dung' => '
                    <p>Tại <strong>CDMT Coffee & Tea</strong>, chúng tôi luôn đặt quyền lợi khách hàng lên hàng đầu và cam kết mang đến quy trình mua hàng minh bạch, thuận tiện.</p>
                    <h3>1. Đặt hàng và thanh toán</h3>
                    <p>Khách hàng có thể đặt hàng trực tiếp tại quán hoặc qua các kênh online chính thức của CDMT. Các phương thức thanh toán bao gồm tiền mặt, chuyển khoản và ví điện tử.</p>
                    <h3>2. Giao hàng</h3>
                    <p>Chúng tôi hỗ trợ giao hàng tận nơi trong khu vực với thời gian nhanh chóng và đảm bảo chất lượng đồ uống khi đến tay khách.</p>
                    <h3>3. Chính sách đổi trả</h3>
                    <p>Nếu sản phẩm gặp vấn đề về chất lượng hoặc không đúng đơn hàng, khách hàng có thể liên hệ ngay với bộ phận chăm sóc khách hàng để được hỗ trợ đổi trả hoặc hoàn tiền.</p>
                    <h3>4. Bảo mật thông tin</h3>
                    <p>Mọi thông tin cá nhân của khách hàng được CDMT bảo mật tuyệt đối và chỉ sử dụng cho mục đích phục vụ dịch vụ tốt nhất.</p>
                    <h3>5. Hỗ trợ khách hàng</h3>
                    <p>Đội ngũ CSKH của chúng tôi luôn sẵn sàng giải đáp thắc mắc và hỗ trợ khách hàng 24/7 qua các kênh liên hệ chính thức.</p>
                    <p>CDMT Coffee & Tea cam kết mang đến trải nghiệm mua hàng chuyên nghiệp và thân thiện nhất.</p>
                ',
                'trang_thai' => 1,
                'luot_xem' => 0,
                'tac_gia' => 'CDMT Coffee & Tea',
                'ngay_dang' => now(),
                'hot' => 0,
                'is_new' => 1,
                'do_uu_tien' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'ma_blog' => 5,
                'ma_danh_muc_blog' => 4,
                'tieu_de' => 'Bí quyết pha cà phê đậm đà tại nhà',
                'slug' => 'bi-quyet-pha-ca-phe-dam-da',
                'sub_tieu_de' => 'Tự tay pha ly cà phê ngon như barista!',
                'hinh_anh' => 'blogs/b5.jpg',
                'noi_dung' => '
                    <p>Muốn có một ly cà phê đậm đà, chuẩn vị mà không cần ra tiệm? CDMT mách bạn vài tips pha cà phê cực chill tại nhà.</p>
                    <ul>
                        <li>Chọn loại cà phê rang xay nguyên chất, vừa phải.</li>
                        <li>Sử dụng nước nóng ở khoảng 90-95°C để chiết xuất đúng vị.</li>
                        <li>Dụng cụ như phin, pour over hay French press sẽ cho hương vị khác nhau – thử hết cho biết nhé!</li>
                    </ul>
                    <p>Chỉ cần vài bước đơn giản là bạn đã có thể tận hưởng một buổi sáng đầy năng lượng cùng cà phê chuẩn vị nhà CDMT.</p>
                ',
                'trang_thai' => 1,
                'luot_xem' => 0,
                'tac_gia' => 'Chi Dat',
                'ngay_dang' => now(),
                'hot' => 1,
                'is_new' => 1,
                'do_uu_tien' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_blog' => 6,
                'ma_danh_muc_blog' => 3,
                'tieu_de' => 'Matcha có thật sự tốt cho sức khỏe?',
                'slug' => 'matcha-va-suc-khoe',
                'sub_tieu_de' => 'Giải mã sức hút của matcha nguyên chất',
                'hinh_anh' => 'blogs/b6.webp',
                'noi_dung' => '
                    <p>Matcha – loại trà xanh xay mịn – đang trở thành thức uống yêu thích của giới trẻ. Nhưng matcha có gì đặc biệt?</p>
                    <ul>
                        <li>Chứa chất chống oxy hóa cao giúp làm chậm quá trình lão hóa.</li>
                        <li>Giúp thư giãn đầu óc và cải thiện tập trung nhờ L-theanine.</li>
                        <li>Thích hợp cho người ăn kiêng hoặc sống lành mạnh.</li>
                    </ul>
                    <p>Hãy thử một ly matcha đá xay mát lạnh tại CDMT và cảm nhận sự khác biệt ngay!</p>
                ',
                'trang_thai' => 1,
                'luot_xem' => 0,
                'tac_gia' => 'Chi Dat',
                'ngay_dang' => now(),
                'hot' => 1,
                'is_new' => 1,
                'do_uu_tien' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_blog' => 7,
                'ma_danh_muc_blog' => 5, //tin tức
                'tieu_de' => 'CDMT khai trương chi nhánh mới tại Quận 7',
                'slug' => 'khai-truong-quan-7',
                'sub_tieu_de' => 'Check-in nhận quà xịn xò!',
                'hinh_anh' => 'blogs/b7.jpg',
                'noi_dung' => '
                    <p>Chúng tôi vui mừng thông báo: CDMT Coffee & Tea đã có mặt tại Quận 7, TP.HCM!</p>
                    <p>Đến ngay trong tuần khai trương để:</p>
                    <ul>
                        <li>Nhận voucher giảm giá lên đến 50%</li>
                        <li>Chụp hình sống ảo với không gian mới cực chill</li>
                        <li>Tham gia mini game và nhận quà liền tay</li>
                    </ul>
                    <p>Địa chỉ: 72, đường 37, Tân Kiểng, Quận 7 – hẹn bạn tại CDMT Coffee & Tea nhé!</p>
                ',
                'trang_thai' => 1,
                'luot_xem' => 0,
                'tac_gia' => 'Chi Dat',
                'ngay_dang' => now(),
                'hot' => 1,
                'is_new' => 1,
                'do_uu_tien' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ma_blog' => 8,
                'ma_danh_muc_blog' => 2,
                'tieu_de' => 'Chính sách tích điểm & đổi quà',
                'slug' => 'tich-diem-doi-qua',
                'sub_tieu_de' => 'Uống càng nhiều – ưu đãi càng lớn',
                'hinh_anh' => null,
                'noi_dung' => '
                    <p>Tại CDMT, chúng tôi triển khai chương trình **tích điểm đổi quà** dành riêng cho khách hàng thân thiết:</p>
                    <ul>
                        <li>1.000đ = 1 điểm</li>
                        <li>100 điểm = Giảm ngay 10k</li>
                        <li>Đổi lấy thức uống miễn phí hoặc voucher hấp dẫn</li>
                    </ul>
                    <p>Hãy đăng ký tài khoản CDMT Loyalty để không bỏ lỡ ưu đãi mỗi lần ghé quán nhé!</p>
                ',
                'trang_thai' => 1,
                'luot_xem' => 0,
                'tac_gia' => 'Admin',
                'ngay_dang' => now(),
                'hot' => 0,
                'is_new' => 0,
                'do_uu_tien' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::updateOrCreate(['ma_blog' => $blog['ma_blog']], $blog);
        }
    }

}
