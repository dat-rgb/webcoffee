var SweetAlert2Demo = (function () {
    //== Demos
    var initDemos = function () {
      //== Sweetalert Demo 1
      $("#alert_demo_1").click(function (e) {
        swal("Good job!", {
          buttons: {
            confirm: {
              className: "btn btn-success",
            },
          },
        });
      });

      //== Sweetalert Demo 2
      $("#alert_demo_2").click(function (e) {
        swal("Here's the title!", "...and here's the text!", {
          buttons: {
            confirm: {
              className: "btn btn-success",
            },
          },
        });
      });

      //== Sweetalert Demo 3
      $("#alert_demo_3_1").click(function (e) {
        swal("Good job!", "You clicked the button!", {
          icon: "warning",
          buttons: {
            confirm: {
              className: "btn btn-warning",
            },
          },
        });
      });

      $("#alert_demo_3_2").click(function (e) {
        swal("Good job!", "You clicked the button!", {
          icon: "error",
          buttons: {
            confirm: {
              className: "btn btn-danger",
            },
          },
        });
      });

      $("#alert_demo_3_3").click(function (e) {
        swal("Good job!", "You clicked the button!", {
          icon: "success",
          buttons: {
            confirm: {
              className: "btn btn-success",
            },
          },
        });
      });

      $("#alert_demo_3_4").click(function (e) {
        swal("Good job!", "You clicked the button!", {
          icon: "info",
          buttons: {
            confirm: {
              className: "btn btn-info",
            },
          },
        });
      });

      //== Sweetalert Demo 4
      $("#alert_demo_4").click(function (e) {
        swal({
          title: "Good job!",
          text: "You clicked the button!",
          icon: "success",
          buttons: {
            confirm: {
              text: "Confirm Me",
              value: true,
              visible: true,
              className: "btn btn-success",
              closeModal: true,
            },
          },
        });
      });

      $("#alert_demo_5").click(function (e) {
        swal({
          title: "Input Something",
          html: '<br><input class="form-control" placeholder="Input Something" id="input-field">',
          content: {
            element: "input",
            attributes: {
              placeholder: "Input Something",
              type: "text",
              id: "input-field",
              className: "form-control",
            },
          },
          buttons: {
            cancel: {
              visible: true,
              className: "btn btn-danger",
            },
            confirm: {
              className: "btn btn-success",
            },
          },
        }).then(function () {
          swal("", "You entered : " + $("#input-field").val(), "success");
        });
      });

      $("#alert_demo_6").click(function (e) {
        swal("This modal will disappear soon!", {
          buttons: false,
          timer: 3000,
        });
      });

      $("#alert_demo_7").click(function (e) {
        swal({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "warning",
          buttons: {
            confirm: {
              text: "Yes, delete it!",
              className: "btn btn-success",
            },
            cancel: {
              visible: true,
              className: "btn btn-danger",
            },
          },
        }).then((Delete) => {
          if (Delete) {
            swal({
              title: "Deleted!",
              text: "Your file has been deleted.",
              type: "success",
              buttons: {
                confirm: {
                  className: "btn btn-success",
                },
              },
            });
          } else {
            swal.close();
          }
        });
      });

      $("#alert_demo_8").click(function (e) {
        swal({
          title: "Xác nhận xóa?",
          text: "Bạn chắc chắn xóa sản phẩm",
          type: "warning",
          buttons: {
            cancel: {
              visible: true,
              text: "Không, hủy",
              className: "btn btn-danger",
            },
            confirm: {
              text: "Có, tiếp tục xóa",
              className: "btn btn-success",
            },
          },
        }).then((willDelete) => {
          if (willDelete) {
            swal("Đã xóa sản phẩm thành công!", {
              icon: "success",
              buttons: {
                confirm: {
                  className: "btn btn-success",
                },
              },
            });
          } else {
            swal("Your imaginary file is safe!", {
              buttons: {
                confirm: {
                  className: "btn btn-success",
                },
              },
            });
          }
        });
      });
    };

    return {
      //== Init
      init: function () {
        initDemos();
      },
    };
  })();

  //== Class Initialization
  jQuery(document).ready(function () {
    SweetAlert2Demo.init();
  });

// lưu trữ
$(document).ready(function () {
    $(".archive-btn").click(function (e) {
        e.preventDefault();
        const form = $(this).closest("form");

        swal({
            title: "Xác nhận lưu trữ?",
            text: "Bạn chắc chắn muốn lưu trữ sản phẩm này?",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Không, hủy",
                    visible: true,
                    className: "btn btn-danger",
                },
                confirm: {
                    text: "Có, tiếp tục",
                    className: "btn btn-success",
                },
            },
        }).then((willDelete) => {
            if (willDelete) {
                form.submit(); // Gửi form
            }
        });
    });
});
//Ẩn
$(document).ready(function () {
  $(".hidden-btn").on("click", function (e) {
    e.preventDefault();
    const form = $(this).closest("form");

    swal({
      title: "Bạn có chắc muốn ẩn sản phẩm?",
      text: "Sản phẩm sẽ bị ẩn khỏi danh sách hiển thị!",
      icon: "warning",
      buttons: {
        cancel: {
          text: "Hủy",
          visible: true,
          className: "btn btn-secondary",
        },
        confirm: {
          text: "Đồng ý",
          className: "btn btn-warning",
        },
      },
    }).then((confirmed) => {
      if (confirmed) {
        form.submit();
      }
    });
  });
});
//Hiển thị
$(document).ready(function () {
  $(".acctive-btn").on("click", function (e) {
    e.preventDefault();
    const form = $(this).closest("form");

    swal({
      title: "Bạn có chắc muốn hiển thị sản phẩm?",
      text: "Sản phẩm sẽ hiển thị trong danh sách!",
      icon: "warning",
      buttons: {
        cancel: {
          text: "Hủy",
          visible: true,
          className: "btn btn-secondary",
        },
        confirm: {
          text: "Đồng ý",
          className: "btn btn-warning",
        },
      },
    }).then((confirmed) => {
      if (confirmed) {
        form.submit();
      }
    });
  });
});
//voucher-hidden-btn
$(document).ready(function () {
  $(".voucher-hidden-btn").on("click", function (e) {
    e.preventDefault();
    const form = $(this).closest("form");

    swal({
      title: "Bạn có chắc muốn đóng voucher?",
      text: "Voucher sẽ đóng. Không thể sử dụng!",
      icon: "warning",
      buttons: {
        cancel: {
          text: "Hủy",
          visible: true,
          className: "btn btn-secondary",
        },
        confirm: {
          text: "Đồng ý",
          className: "btn btn-warning",
        },
      },
    }).then((confirmed) => {
      if (confirmed) {
        form.submit();
      }
    });
  });
});
//voucher-acctive-btn
$(document).ready(function () {
  $(".voucher-acctive-btn").on("click", function (e) {
    e.preventDefault();
    const form = $(this).closest("form");

    swal({
      title: "Bạn có chắc muốn mở voucher?",
      text: "Voucher sẽ được ở và sử dụng!",
      icon: "warning",
      buttons: {
        cancel: {
          text: "Hủy",
          visible: true,
          className: "btn btn-secondary",
        },
        confirm: {
          text: "Đồng ý",
          className: "btn btn-warning",
        },
      },
    }).then((confirmed) => {
      if (confirmed) {
        form.submit();
      }
    });
  });
});
//voucher-archive-btn
$(document).ready(function () {
  $(".voucher-archive-btn").click(function (e) {
      e.preventDefault();
      const form = $(this).closest("form");

      swal({
          title: "Xác nhận lưu trữ?",
          text: "Bạn chắc chắn muốn lưu trữ Voucher này?",
          icon: "warning",
          buttons: {
              cancel: {
                  text: "Không, hủy",
                  visible: true,
                  className: "btn btn-danger",
              },
              confirm: {
                  text: "Có, tiếp tục",
                  className: "btn btn-success",
              },
          },
      }).then((willDelete) => {
          if (willDelete) {
              form.submit(); // Gửi form
          }
      });
  });
});

//voucher-delete-btn
$(document).ready(function () {
  $(".voucher-delete-btn").click(function (e) {
      e.preventDefault();
      const form = $(this).closest("form");

      swal({
          title: "Xác nhận xóa?",
          text: "Bạn chắc chắn muốn xóa voucher này?",
          icon: "warning",
          buttons: {
              cancel: {
                  text: "Không, hủy",
                  visible: true,
                  className: "btn btn-danger",
              },
              confirm: {
                  text: "Có, tiếp tục",
                  className: "btn btn-success",
              },
          },
      }).then((willDelete) => {
          if (willDelete) {
              form.submit(); // Gửi form
          }
      });
  });
});
//btn-update
$(document).ready(function () {
  $(".btn-update").on("click", function (e) {
    e.preventDefault();

    Swal.fire({
      title: "Xác nhận cập nhật voucher?",
      text: "Sẽ cập nhật lại các thông tin của voucher!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Đồng ý",
      cancelButtonText: "Hủy",
      buttonsStyling: true,
      customClass: {
        confirmButton: "btn btn-warning",
        cancelButton: "btn btn-secondary",
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        $("#voucher-form")[0].submit();
      }
    });
  });
});

//voucher-cancel-btn
$(document).ready(function () {
  $(".voucher-cancel-btn").click(function (e) {
      e.preventDefault(); // Ngừng hành động mặc định của thẻ <a>

      // Hiển thị SweetAlert xác nhận
      Swal.fire({
          title: "Xác nhận hủy?",
          text: "Bạn chắc chắn muốn hủy cập nhật voucher này?",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Có, tiếp tục",
          cancelButtonText: "Không, hủy",
          customClass: {
              cancelButton: "btn btn-danger",
              confirmButton: "btn btn-success"
          }
      }).then((result) => {
          if (result.isConfirmed) {
              // Nếu người dùng xác nhận "Có, tiếp tục", chuyển trang
              window.location.href = $(this).attr('href'); // Điều hướng tới URL
          }
          // Nếu người dùng chọn "Không, hủy", không làm gì cả
      });
  });
});

//Logout out
$(document).ready(function () {
  $("#logout-link").click(function (e) {
      e.preventDefault();

      swal({
          title: "Xác nhận đăng xuất?",
          text: "Bạn chắc chắn muốn đăng xuất khỏi tài khoản này?",
          icon: "warning",
          buttons: {
              cancel: {
                  text: "Không, hủy",
                  visible: true,
                  className: "btn btn-danger",
              },
              confirm: {
                  text: "Có, đăng xuất",
                  className: "btn btn-success",
              },
          },
      }).then((willLogout) => {
          if (willLogout) {
              $("#logout-form").submit(); // Gửi form đăng xuất
          }
      });
  });
});




function confirmStatusChange(event, checkbox) {
  event.preventDefault(); // Ngăn chặn form submit ngay lập tức
  let form = checkbox.form;
  let message = checkbox.checked ? "Bạn chắc chắn muốn mở voucher?" : "Bạn chắc chắn muốn đóng voucher?";

  // Sử dụng SweetAlert để hiển thị thông báo xác nhận
  swal({
      title: "Xác nhận",
      text: message,
      icon: "warning",
      buttons: {
          cancel: {
              visible: true,
              text: "Hủy",
              className: "btn btn-danger",
          },
          confirm: {
              text: "Có, tiếp tục",
              className: "btn btn-success",
          },
      },
  }).then((willChange) => {
      if (willChange) {
          form.submit(); // Nếu xác nhận, submit form để cập nhật trạng thái
      } else {
          checkbox.checked = !checkbox.checked; // Nếu hủy, trả lại trạng thái ban đầu
      }
  });
}


//lưu trữ nhà cung cấp
  $(".supplier-archive-btn").click(function (e) {
      e.preventDefault();
      const form = $(this).closest("form");

      swal({
          title: "Xác nhận lưu trữ?",
          text: "Bạn chắc chắn muốn lưu trữ Nhà Cung Cấp này?",
          icon: "warning",
          buttons: {
              cancel: {
                  text: "Không, hủy",
                  visible: true,
                  className: "btn btn-danger",
              },
              confirm: {
                  text: "Có, tiếp tục",
                  className: "btn btn-success",
              },
          },
      }).then((willDelete) => {
          if (willDelete) {
              form.submit(); // Gửi form
          }
      });
  });
//xóa
$(document).ready(function () {
  $(".voucher-delete-btn").click(function (e) {
      e.preventDefault();
      const form = $(this).closest("form");

      swal({
          title: "Xác nhận xóa?",
          text: "Bạn chắc chắn muốn xóa voucher này?",
          icon: "warning",
          buttons: {
              cancel: {
                  text: "Không, hủy",
                  visible: true,
                  className: "btn btn-danger",
              },
              confirm: {
                  text: "Có, tiếp tục",
                  className: "btn btn-success",
              },
          },
      }).then((willDelete) => {
          if (willDelete) {
              form.submit(); // Gửi form
          }
      });
  });
});
//// Xác nhận thêm nhân viên
$(".add-employee-btn").click(function (e) {
    e.preventDefault();
    const form = $(this).closest("form");

    swal({
        title: "Xác nhận thêm nhân viên?",
        text: "Bạn chắc chắn muốn thêm nhân viên này?",
        icon: "info",
        buttons: {
            cancel: {
                text: "Không, hủy",
                visible: true,
                className: "btn btn-danger",
            },
            confirm: {
                text: "Có, thêm ngay",
                className: "btn btn-success",
            },
        },
    }).then((willAdd) => {
        if (willAdd) {
            form.submit(); // Gửi form
        }
    });
});
//cập nhật nhân viên
$(document).ready(function () {
  $(".nhanvien-btn-update").on("click", function (e) {
    e.preventDefault();

    let form = $(this).closest("form"); // Tìm form gần nhất chứa nút

    Swal.fire({
      title: "Xác nhận cập nhật nhân viên?",
      text: "Thông tin nhân viên sẽ được cập nhật!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Cập nhật",
      cancelButtonText: "Hủy",
      buttonsStyling: false,
      customClass: {
        confirmButton: "btn btn-warning",
        cancelButton: "btn btn-secondary",
      }
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // Gửi form
      }
    });
  });
});
//Lưu lịch làm việc
$(document).ready(function () {
  $(".lich-btn-update").on("click", function (e) {
    e.preventDefault();

    let form = $(this).closest("form"); // Tìm form gần nhất chứa nút

    Swal.fire({
      title: "Xác nhận cập nhật Lịch?",
      text: "Lịch phân ca sẽ được cập nhật!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Cập nhật",
      cancelButtonText: "Hủy",
      buttonsStyling: false,
      customClass: {
        confirmButton: "btn btn-warning",
        cancelButton: "btn btn-secondary",
      }
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // Gửi form
      }
    });
  });
});
//khôi phục nhân viên
$(document).ready(function () {
  $(".nhanvien-btn-update").on("click", function (e) {
    e.preventDefault();

    let form = $(this).closest("form"); // Tìm form gần nhất chứa nút

    Swal.fire({
      title: "Xác nhận khôi phục nhân viên ?",
      text: "Danh sách nhân viên sẽ được cập nhật!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Xác nhận",
      cancelButtonText: "Hủy",
      buttonsStyling: false,
      customClass: {
        confirmButton: "btn btn-warning",
        cancelButton: "btn btn-secondary",
      }
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // Gửi form
      }
    });
  });
});
//Chỉnh sửa nhân viên
$(document).ready(function () {
  $(".nhanvien-btn-edit").on("click", function (e) {
    e.preventDefault();

    let form = $(this).closest("form"); // Tìm form gần nhất chứa nút

    Swal.fire({
      title: "Xác nhận chỉnh sửa nhân viên này ?",
      text: "Nhân viên sẽ được cập nhật!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Xác nhận",
      cancelButtonText: "Hủy",
      buttonsStyling: false,
      customClass: {
        confirmButton: "btn btn-success me-2",
        cancelButton: "btn btn-secondary",
      }
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // Gửi form
      }
    });
  });
});





//ẩn, khôi phục, xóa danh mục
$(document).ready(function () {
    $(".btn-bulk-restore, .btn-bulk-delete, .btn-bulk-archive").on("click", function (e) {
        e.preventDefault();
        let button = $(this);
        let form = button.closest("form");
        let actionUrl = button.attr("formaction");

        let checkedCount = form.find("input[name='selected_ids[]']:checked").length;
        if (checkedCount === 0) {
            Swal.fire({
                title: "Không có danh mục nào được chọn!",
                text: "Vui lòng chọn ít nhất một danh mục trước khi thực hiện thao tác.",
                icon: "info",
                confirmButtonText: "OK",
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            return; // Dừng ở đây, không tiếp tục
        }
        let config = {
            ".btn-bulk-restore": {
                title: "Xác nhận khôi phục danh mục?",
                text: "Các danh mục đã chọn sẽ được khôi phục và hiển thị trở lại!",
                icon: "warning",
                confirmButton: "btn-warning"
            },
            ".btn-bulk-delete": {
                title: "Xác nhận xóa vĩnh viễn danh mục?",
                text: "Sau khi xóa, bạn sẽ không thể khôi phục lại các danh mục này!",
                icon: "error",
                confirmButton: "btn-danger"
            },
            ".btn-bulk-archive": {
                title: "Xác nhận tạm xóa danh mục?",
                text: "Các danh mục đã chọn sẽ bị tạm xóa khỏi danh sách hiển thị!",
                icon: "warning",
                confirmButton: "btn-warning"
            }
        };

        let btnClass = "";
        if (button.hasClass("btn-bulk-restore")) btnClass = ".btn-bulk-restore";
        if (button.hasClass("btn-bulk-delete")) btnClass = ".btn-bulk-delete";
        if (button.hasClass("btn-bulk-archive")) btnClass = ".btn-bulk-archive";

        let alertConfig = config[btnClass];

        Swal.fire({
            title: alertConfig.title,
            text: alertConfig.text,
            icon: alertConfig.icon,
            showCancelButton: true,
            confirmButtonText: "Xác nhận",
            cancelButtonText: "Hủy",
            buttonsStyling: false,
            customClass: {
                confirmButton: `btn ${alertConfig.confirmButton} me-2`, // thêm margin-end
                cancelButton: "btn btn-secondary ms-2", // thêm margin-start
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.attr("action", actionUrl);
                form.submit();
            }
        });
    });
});





