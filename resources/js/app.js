import './bootstrap';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

// document.addEventListener('DOMContentLoaded', function () {
//     const maCuaHang = document.querySelector('meta[name="ma-cua-hang"]')?.content;
//     console.log("DOM ready");
    
//     if (window.Echo && maCuaHang) {
//         console.log("Subscribing to: orders." + maCuaHang);
//         window.Echo.channel('orders.' + maCuaHang)
//             .listen('.order.created', (e) => {
//                 console.log("Đã nhận được order mới:", e);
//                 toastr.success(`🛒 Đơn hàng mới: ${e.order.ma_hoa_don} từ ${e.order.ten_khach_hang}`);
//             });
//     } else {
//         console.warn("Echo chưa sẵn sàng hoặc không có ma_cua_hang");
//     }
// });
