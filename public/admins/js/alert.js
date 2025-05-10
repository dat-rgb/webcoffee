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
