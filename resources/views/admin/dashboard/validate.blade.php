<script !src="">
    $().ready(function () {
        var common = {
            name: {
                required: true,
                minlength: 6
            },
            phone: {
                required: true,
                minlength: 8,
                maxlength: 11
            },
            identity_card: {
                required: true,
                minlength: 8,
                maxlength: 15
            },
            email: {
                required: true,
                email: true
            }
        };

        $('#formRegisterCoach').validate({
            rules: {...common},
            ...errorHighLight
        });
    });

</script>
