const importModal = new bootstrap.Modal(document.getElementById('importManufacturerModal'));
const input = document.querySelector('#importEmpty');
document.querySelectorAll('#import').forEach(elem => elem.addEventListener("click", (event) => {
    importModal.show();
}));
document.querySelector('#confirmBtnImport').addEventListener('click', function (event) {
    if (!input.value) {
        event.preventDefault();
    }
});

