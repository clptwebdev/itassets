function selectPhoto(id, src) {
    const profileImage = document.querySelector("#profileImage");
    const photoId = document.querySelector("#photo_id");

    profileImage.src = src;
    photoId.value = id;
    $("#imgModal").modal("hide");
}

const imgUploadForm = document.querySelector("form#imageUpload");

imgUploadForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const urlto = "/photo/upload";

    const xhttp = new XMLHttpRequest();

    xhttp.onload = function () {
        const response = xhttp.responseText;
        if (response !== false) {
            $("#uploadModal").modal("hide");
            profileImage.src = `/${data.path}`;
            photoId.value = data.id;
        }
    };

    xhttp.open("POST", "/model/create/");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`data=${formData}`);
});

/* $(document).ready(function () {
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
                profileImage.src = `/${data.path}`;
                photoId.value = data.id;
            },
        });
    });
}); */
