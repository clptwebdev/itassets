$('.transferBtn').click(function () {
    const modelID = document.querySelector('#model_id').value = this.dataset.model - id;
    const modelTAG = document.querySelector('#asset_tag_transfer').value = this.dataset.model - tag;
    const locationID = document.querySelector('#location_id').value = this.dataset.location - id;
    const locationFROM = document.querySelector('#location_from').value = this.dataset.location - from;
    $('#requestTransfer').modal('show');
});
