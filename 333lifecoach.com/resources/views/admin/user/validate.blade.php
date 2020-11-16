<script !src="">
    $().ready(function () {
        var commonPass = {
            new_password: {
                required: true,
                minlength: 6
            },
            confirm_new_password: {
                required: true,
                minlength: 6,
                equalTo: '#new_password'
            },
        };
        var common = {
            name: {
                required: true,
                minlength: 6
            },
            phone: {
                required: true,
            },
            identity_card: {
                required: true,
                minlength: 8,
                maxlength: 15
            },
            email: {
                required: true,
                email: true
            },
            email_fb: {
                email: true
            },
            nick_fb: {
                url: true
            }
        };
        $('#formChangePassword').validate({
            rules: {...commonPass},
            ...errorHighLight
        });

        $('#formProfileUser').validate({
            rules: {...common},
            ...errorHighLight
        });
    });

</script>
