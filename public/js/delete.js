const deleteModal = new bootstrap.Modal(document.getElementById('removeUserModal'));

document.querySelectorAll(".deleteBtn").forEach(elem => elem.addEventListener("click", () => {
    if (elem.getAttribute('data-count')) {
        document.querySelector('#asset_count').innerHTML = elem.getAttribute('data-count');
    }
    document.querySelector('#user-id').value = elem.getAttribute('data-id');
    //showModal
    deleteModal.show();
}));

document.querySelector('#confirmBtn').addEventListener('click', function (event) {
    const user = document.querySelector('#user-id').value;
    const form = document.querySelector('#' + 'form' + user);
    form.submit();
});
