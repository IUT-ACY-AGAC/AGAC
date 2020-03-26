$(document).ready(function() {
    function refresh(file, arg = null) {
        $(file).next('.custom-file-label').children("span").text(arg === null ? (file.files.length > 0 ? file.files[0].name : "") : "");

        checkFormFiles($(file).closest('form'));
    }

    function checkFormFiles(form) {
        $(form).find("[type=submit]").prop("disabled", !form.find("input[type=file]").is(function () {
            return this.files.length > 0;
        }));
    }

    $("input[type=file]").change(function (e) {
        refresh(this);
    });

    $("[type=reset]").closest('form').on('reset', function (event) {
        $(this).find("input[type=file]").each(function () {
            refresh(this, "");
        })
    });
});