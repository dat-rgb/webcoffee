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

		// Kiểm tra ký tự in hoa
		if (!/[A-Z]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 ký tự in hoa. <br>";
		}

		// Kiểm tra số
		if (!/[0-9]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 số. <br>";
		}

		// Kiểm tra ký tự đặc biệt
		if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 ký tự đặc biệt. <br>";
		}

		if (errorMessage != "") {
			toastr.error(errorMessage, "Lỗi");
			e.preventDefault();
		}

	})
})
// validate register form
$(document).ready(function(){ 
    $('#register-form').submit(function (e) { 
        let name = $('input[name="name"]').val().trim();
        let email = $('input[name="email"]').val().trim();
        let password = $('input[name="password"]').val().trim();
        let password_confirmation = $('input[name="password_confirmation"]').val().trim();
    
        let errorMessage = "";

        // Kiểm tra tên
        if(name.length < 2){
            errorMessage += "Họ và tên phải có ít nhất 2 ký tự. <br>";
        }

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

		// Kiểm tra ký tự in hoa
		if (!/[A-Z]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 ký tự in hoa. <br>";
		}

		// Kiểm tra số
		if (!/[0-9]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 số. <br>";
		}

		// Kiểm tra ký tự đặc biệt
		if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
			errorMessage += "Mật khẩu phải có ít nhất 1 ký tự đặc biệt. <br>";
		}
        // Kiểm tra xác nhận mật khẩu
        if(password != password_confirmation){
            errorMessage += "Mật khẩu nhập lại không khớp. <br>";
        }

		if (errorMessage != "") {
			toastr.error(errorMessage, "Lỗi");
			e.preventDefault();
		}
    });
});
