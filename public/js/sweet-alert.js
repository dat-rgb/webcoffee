
$(document).on('click', '#logout-btn', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Đăng xuất?',
        text: "Bạn chắc chắn muốn đăng xuất?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Có, đăng xuất!',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#logout-form').submit();
        }
    });
});