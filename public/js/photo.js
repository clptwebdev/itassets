const photoModal = new bootstrap.Modal(document.getElementById('imgModal'));
const photoUploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
const photoUpload = document.querySelector('#imageUpload');
const photo = document.querySelector('#profileImage');
const photoId = document.querySelector("#photo_id");
const urlto = "/photo/upload";

photo.addEventListener("click", function (e) {
    e.preventDefault();
    photoModal.show();
});

function selectPhoto(id, src) {
    photo.src = src;
    photoId.value = id;
    photoModal.hide();
}

photoUpload.onsubmit = async (e) => {

    e.preventDefault();

    let response = await fetch('/photo/upload', {

        method: 'POST',

        body: new FormData(photoUpload)

    });


    let result = await response.json();

    photo.src = result.path;

    photoId.value = result.id;

    photoUploadModal.hide();
}
//ajax get photos pagination
const photoContent = document.querySelector('#photoContent');


function getPhotoPage(page) {
    const xhr = new XMLHttpRequest();

    xhr.onload = function () {
        //place the response text in the modal
        photoContent.innerHTML = xhr.responseText
    }

    xhr.open('get', `/photo/get/${page}`)
    xhr.send()
}

//updatePhotos();
/* function updatePhotos(page = 1){

    const xhr = new XMLHttpRequest();
    xhr.onload = function() {

        //Place the JSON Images into the modal
        let response = JSON.parse(xhr.responseText);
        let output = "";
        Object.entries(response.photos).forEach(([key, value]) => {

             output += `<img src="${value}" width="80px" alt=""

                         class="selectPhoto" data-url="${value}" data-id="${key}">`;
        });
        console.log(output);
        library.innerHTML = output;
        photoUploadModal.hide();
    };

    xhr.open("GET", `/photo/${page}/get`);
    xhr.send();

}
 */
