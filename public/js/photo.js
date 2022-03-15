const photoModal = new bootstrap.Modal(document.getElementById('imgModal'));
const photoUploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
const photoUpload = document.querySelector('#imageUpload');
const photo = document.querySelector('#profileImage');
const xhttp = new XMLHttpRequest();
const urlto = "photo/upload";

photo.addEventListener("click", function (e) {
    e.preventDefault();
    photoModal.toggle();
});

function selectPhoto(id, src) {
    const profileImage = document.querySelector("#profileImage");
    const photoId = document.querySelector("#photo_id");

    profileImage.src = src;
    photoId.value = id;
    photoModal.toggle();
}


/* const imgUploadForm = document.querySelector("form#imageUpload");

imgUploadForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    const urlto = "/photo/upload";

    const xhttp = new XMLHttpRequest();

    xhttp.onload = function () {
        const response = xhttp.responseText;
        if (response !== false) {
            console.log(response);
        }
    };

    xhttp.open("POST", urlto);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`data=${formData}`);
}); */


photoUpload.addEventListener("submit", function (event) {
    event.preventDefault();
    const formData = new FormData();
    const file = document.querySelector("[name='file']").files[0];
    console.log(file);
    const name = document.querySelector("[name='name']");
    console.log(name);
    formData.append('file', file);
    formData.append('name', name);
    console.log(formData);
    xhttp.onload = function () {
        photoUploadModal.hide();
        console.log(xhttp.response);
        // JSON.parse(xhr.responseText);
        // document.getElementById("profileImage").src =
        // document.getElementById("photo_id").value = xhttp.response.id;
    };
    xhttp.open("POST", urlto);
    xhttp.setRequestHeader('Content-type', 'multipart/form-data');

    xhttp.send("name=" + name.value);
});


// $(document).ready(function () {
//     $("form#imageUpload").submit(function (e) {
//         e.preventDefault();
//         const formData = new FormData(this);
//         const urlto = "/photo/upload";
//         $.ajaxSetup({
//             headers: {
//                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//             },
//         });
//         // AJAX request
//         $.ajax({
//             url: urlto,
//             method: "POST",
//             data: formData,
//             processData: false,
//             contentType: false,
//             success: function (data) {
//                 $("#uploadModal").modal("hide");
//                 profileImage.src = `/${data.path}`;
//                 photoId.value = data.id;
//             },
//         });
//     });
// });


