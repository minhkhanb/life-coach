<script !src="">
    $().ready(function () {
        $.validator.setDefaults({
            submitHandler: function (form) {
                form.submit();
            }
        });
        $('#courseForm').validate({
            rules: {
                name: {
                    required: true,
                },
                open_at: {
                    required: true,
                },
                expected_end_date: {
                    required: true,
                    greaterThanDate: '#open_at'
                },
            },
            ...errorHighLight
        });
    });

</script>
