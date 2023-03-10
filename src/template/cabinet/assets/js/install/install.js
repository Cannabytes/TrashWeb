$(document).ready(function () {
    $("form").submit(function (event) {
        $('#add_db').prop('disabled', true);
        $('#add_db').text('Loading...');
        $.ajax({
            type: "POST",
            url: "/install/db/connect/test",
            data: $(this).serialize(),
            dataType: "json",
            encode: true,
        }).success(function (data) {
            if (data.ok){
                notify_success(data.message)
                document.location.href = '/install/admin'
            }else {
                notify_error(data.message)
            }
        });
        event.preventDefault();
    });
});
 