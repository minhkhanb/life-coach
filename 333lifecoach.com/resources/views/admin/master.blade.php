<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="/public/theme_admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/theme_admin/css/adminlte.min.css">
    <link rel="stylesheet" href="/public/theme_admin/css/toastr.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    @yield('css')
    <style>
        .content-wrapper table td {
            vertical-align: middle;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include('admin.layouts.header')
    @include('admin.layouts.nav')
    @yield('content')
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline">
        </div>
        <strong>Copyright &copy; {{ date('Y') }}</strong>
    </footer>
</div>
<!-- jQuery -->
<script src="/public/theme_admin/vendor/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/public/theme_admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/public/theme_admin/js/toastr.min.js"></script>
<script src="/public/theme_admin/js/adminlte.min.js"></script>
<script src="/public/theme_admin/js/jquery.validate.min.js"></script>
<script !src="">
    $.extend($.validator.messages, {
        required: "Trường này không được trống!",
        remote: "Hãy sửa cho đúng.",
        email: "Địa chỉ email không hợp lệ!",
        url: "Địa chỉ không hợp lệ!",
        date: "Thời gian không hợp lệ!",
        dateISO: "Hãy nhập ngày (ISO).",
        number: "Hãy nhập số.",
        digits: "Hãy nhập chữ số.",
        creditcard: "Hãy nhập số thẻ tín dụng.",
        equalTo: "Giá trị nhập vào không đúng!",
        extension: "Phần mở rộng không đúng.",
        maxlength: $.validator.format("Hãy nhập từ {0} kí tự trở xuống."),
        minlength: $.validator.format("Hãy nhập từ {0} kí tự trở lên."),
        rangelength: $.validator.format("Hãy nhập từ {0} đến {1} kí tự."),
        range: $.validator.format("Hãy nhập từ {0} đến {1}."),
        max: $.validator.format("Hãy nhập từ {0} trở xuống."),
        min: $.validator.format("Hãy nhập từ {0} trở lên.")
    });

    $.validator.setDefaults({
        submitHandler: function (form) {
            form.submit();
        }
    });

    $.validator.addMethod("greaterThanDate",
        function (value, element, params) {
            if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) > new Date($(params).val());
            }
            return isNaN(value) && isNaN($(params).val())
                || (Number(value) > Number($(params).val()));
        }, 'Hãy nhập từ {0} trở lên!');

    $.validator.addMethod("notEqual", function (value, element, param) {
        return this.optional(element) || value != $(param).val();
    }, "Giá trị nhập vào đã bị trùng!");

    var errorHighLight = {
        onfocusout: function (element) {
            $(element).valid()
        },
        onkeyup: function (element) {
            $(element).valid()
        },
        onclick: false,
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group div').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    };

</script>
@include('admin.layouts.notification')
@yield('js')
</body>
</html>
