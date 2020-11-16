<script !src="">
    $().ready(function () {
        var common = {
            course_id: {
                required: true,
            },
            title: {
                required: true,
            },
            "answer[0]": {
                'required': true,
            },
            "answer[1]": {
                required: true,
            },
            "answer[2]": {
                required: true,
            },
            "answer[3]": {
                required: true,
            },
            answer_correct: {
                required: true
            }
        };

        $('#formQuestion').validate({
            rules: { ...common},
            ...errorHighLight
        });
    });

</script>
