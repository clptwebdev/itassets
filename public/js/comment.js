const commentModal = new bootstrap.Modal(document.getElementById('commentModalOpen'));
const commentEditModal = new bootstrap.Modal(document.getElementById('commentModalEdit'));
const commentDeleteModal = new bootstrap.Modal(document.getElementById('removeComment'));
const commentForm = document.getElementById('updateForm');
let commentTitle = document.querySelector('#updateTitle');
let commentFormDelete = document.querySelector('.deleteCommentForm');
let commentBody = document.querySelector('#updateComment');
let commentId = document.querySelector('#comment-id');
document.querySelector('#commentModal').addEventListener('click', function () {
    //showModal
    commentModal.show()
});

document.querySelector('.editComment').addEventListener('click', function (event) {
    event.preventDefault();
});

document.querySelectorAll('.editComment').forEach(elem => elem.addEventListener("click", (event) => {

    event.preventDefault();
    commentTitle.value = elem.getAttribute('data-title');
    commentBody.value = elem.getAttribute('data-comment');
    commentForm.action = elem.getAttribute('data-route');
    commentEditModal.show();
}));

document.querySelectorAll('.deleteComment').forEach(elem => elem.addEventListener("click", (event) => {
    event.preventDefault();
    commentId.value = elem.getAttribute('data-id');
    commentFormDelete.action = elem.getAttribute('data-route')
    //showModal
    commentDeleteModal.show();
}));
document.querySelector('#confirmCommentBtn').addEventListener('click', function () {
    //post form
    commentFormDelete.submit();
});

