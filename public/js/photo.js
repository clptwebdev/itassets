function selectPhoto(id, src) {
    const profileImage = document.querySelector("#profileImage");
    const photoId = document.querySelector("#photo_id");

    profileImage.src = src;
    photoId.value = id;
    $("#imgModal").modal("hide");
}

$(document).ready(function () {
    $("form#imageUpload").submit(function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const urlto = "/photo/upload";
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        // AJAX request
        $.ajax({
            url: urlto,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $("#uploadModal").modal("hide");
                profileImage.src = `{{asset(${data.path})}}`;
                photoId.value = data.id;
            },
        });
    });
});
