$('.transferBtn').click(function () {
    const modelID = document.querySelector('#model_id').value = this.getAttribute('data-model-id');
    const modelTAG = document.querySelector('#asset_tag_transfer').value = this.getAttribute('data-model-tag');
    const locationID = document.querySelector('#location_id').value = this.getAttribute('data-location-id');
    const locationFROM = document.querySelector('#location_from').value = this.getAttribute('data-location-from');
    $('#requestTransfer').modal('show');
    //const requestModal = document.querySelector('#requestTransfer').style.display = 'block';
});
