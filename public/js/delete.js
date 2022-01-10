document.querySelectorAll(".deleteBtn").forEach(elem => elem.addEventListener("click", () => {
// document.querySelector('.deleteBtn').addEventListener('click', function () {
    document.querySelector('#user-id').value = elem.getAttribute('data-id');
    //showModal
    $('#removeUserModal').modal('show');
    // document.querySelector('#removeUserModal').style.display= 'block';
    // document.querySelector('#removeUserModal').classList.add('show');
}));

document.querySelector('#confirmBtn').addEventListener('click', function () {
    const user = document.querySelector('#user-id').value;
    const form = document.querySelector('#' + 'form' + user);
    form.submit();
});
