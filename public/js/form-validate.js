// validate login form
$(document).ready(function(){
	$('#login-form').submit(function (e){
		let email = $('input[name="email"]').val().trim();
		let password = $('input[name="password"]').val().trim();

		let errorMessage = "";

		// Kiểm tra email
		let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if(!emailRegex.test(email)){
			errorMessage += "Email không hợp lệ. <br>";
		}

		// Kiểm tra mật khẩu
		if(password.length < 6){
			errorMessage += "Mật khẩu phải ít nhất 6 ký tự. <br>";
		}
		if(password.length > 20){
			errorMessage += "Mật khẩu không vượt quá 20 ký tự. <br>";
		}
		if (!/[A-Z]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 ký tự in hoa. <br>";
		}
		if (!/[0-9]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 số. <br>";
		}
		if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 ký tự đặc biệt. <br>";
		}

		if (errorMessage != "") {
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

//validate register form
$(document).ready(function(){ 
    $('#register-form').submit(function (e) { 
        let name = $('input[name="name"]').val().trim();
        let email = $('input[name="email"]').val().trim();
        let password = $('input[name="password"]').val().trim();
        let password_confirmation = $('input[name="password_confirmation"]').val().trim();
    
        let errorMessage = "";

        if(name.length < 2){
            errorMessage += "Họ và tên phải có ít nhất 2 ký tự. <br>";
        }

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!emailRegex.test(email)){
            errorMessage += "Email không hợp lệ. <br>";
        }

        if(password.length < 6){
            errorMessage += "Mật khẩu phải ít nhất 6 ký tự. <br>";
        }
		if(password.length > 20){
            errorMessage += "Mật khẩu không vượt quá 20 ký tự. <br>";
        }
		if (!/[A-Z]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 ký tự in hoa. <br>";
		}
		if (!/[0-9]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 số. <br>";
		}
		if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 ký tự đặc biệt. <br>";
		}
        if(password != password_confirmation){
            errorMessage += "Mật khẩu nhập lại không khớp. <br>";
        }

		if (errorMessage != "") {
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

//reset-password-form
$(document).ready(function(){ 
    $('#reset-password-form').submit(function (e) { 
        let email = $('input[name="email"]').val().trim();
        let password = $('input[name="password"]').val().trim();
        let password_confirmation = $('input[name="password_confirmation"]').val().trim();
    
        let errorMessage = "";

        // Kiểm tra email
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!emailRegex.test(email)){
            errorMessage += "Email không hợp lệ. <br>";
        }

        // Kiểm tra mật khẩu
        if(password.length < 6){
            errorMessage += "Mật khẩu phải ít nhất 6 ký tự. <br>";
        }
        if(password.length > 20){
            errorMessage += "Mật khẩu không vượt quá 20 ký tự. <br>";
        }

        if (!/[A-Z]/.test(password)) {
            errorMessage += "Mật khẩu phải có ít nhất 1 ký tự in hoa. <br>";
        }

        if (!/[0-9]/.test(password)) {
            errorMessage += "Mật khẩu phải có ít nhất 1 số. <br>";
        }

        if (!/[!@#$%^&*(),.?\":{}|<>]/.test(password)) {
            errorMessage += "Mật khẩu phải có ít nhất 1 ký tự đặc biệt. <br>";
        }

        if(password != password_confirmation){
            errorMessage += "Mật khẩu nhập lại không khớp. <br>";
        }
        
        if (errorMessage != "") {
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

$(document).ready(function () {
    $('#contact-form').submit(function (e) {
        let name = $('#name').val().trim();
        let email = $('#email').val().trim();
        let phone = $('#phone').val().trim();
        let subject = $('#subject').val().trim();
        let message = $('#message').val().trim();
        let recaptcha = grecaptcha.getResponse();

        let errorMessage = "";

        // Validate Họ và tên
        if (name.length < 2) {
            errorMessage += "Họ và tên phải có ít nhất 2 ký tự.<br>";
        }

        // Validate Email
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage += "Email không hợp lệ.<br>";
        }

        // Validate SĐT
        let phoneRegex = /^0\d{9}$/;
        if (!phoneRegex.test(phone)) {
            errorMessage += "Số điện thoại phải bắt đầu bằng 0 và đủ 10 chữ số.<br>";
        }

        // Validate Chủ đề
        if (subject.length < 3) {
            errorMessage += "Chủ đề phải có ít nhất 3 ký tự.<br>";
        }

        // Validate Nội dung
        if (message.length < 10) {
            errorMessage += "Nội dung tin nhắn phải dài hơn 10 ký tự.<br>";
        }

        // Validate reCAPTCHA
        if (recaptcha.length === 0) {
            errorMessage += "Vui lòng xác nhận bạn không phải là robot (reCAPTCHA).<br>";
        }

        if (errorMessage !== "") {
            e.preventDefault();
            Swal.fire({
                title: 'Thông báo!',
                html: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
