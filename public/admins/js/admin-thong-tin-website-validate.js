$(document).ready(function () {
    $('#thongTinWebsite-edit-form').submit(function (e) {
        let errorMessage = "";

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const urlRegex = /^(https?:\/\/)?([\w\d\-]+\.)+[\w]{2,}(\/\S*)?$/;

        let tenWebsite = $('#ten_website').val().trim();
        let soDienThoai = $('#so_dien_thoai').val().trim();
        let email = $('#email').val().trim();
        let diaChi = $('#dia_chi').val().trim();

        let facebookUrl = $('input[name="facebook_url"]').val().trim();
        let instagramUrl = $('input[name="instagram_url"]').val().trim();
        let zaloUrl = $('input[name="zalo_url"]').val().trim();
        let youtubeUrl = $('input[name="youtube_url"]').val().trim();
        let tiktokUrl = $('input[name="tiktok_url"]').val().trim();

        // Validate required fields
        if (tenWebsite.length < 2 || tenWebsite.length > 100) {
            errorMessage += "Tên website phải từ 2-100 ký tự.<br>";
        }

        if (soDienThoai.length === 0) {
            errorMessage += "Số điện thoại không được để trống.<br>";
        }

        if (!emailRegex.test(email)) {
            errorMessage += "Email không hợp lệ.<br>";
        }

        if (diaChi.length === 0) {
            errorMessage += "Địa chỉ không được để trống.<br>";
        }

        // Validate optional URLs
        const socialUrls = [
            { label: 'Facebook', value: facebookUrl },
            { label: 'Instagram', value: instagramUrl },
            { label: 'Zalo', value: zaloUrl },
            { label: 'YouTube', value: youtubeUrl },
            { label: 'TikTok', value: tiktokUrl },
        ];

        socialUrls.forEach(social => {
            if (social.value.length > 0 && !urlRegex.test(social.value)) {
                errorMessage += `${social.label} URL không hợp lệ.<br>`;
            }
        });

        if (errorMessage !== "") {
            e.preventDefault();
            Swal.fire({
                title: 'Lỗi!',
                html: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
