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
