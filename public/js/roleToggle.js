const create = document.querySelector('#createToggle');
const read = document.querySelector('#readToggle');
const update = document.querySelector('#updateToggle');
const destroy = document.querySelector('#deleteToggle');
const archive = document.querySelector('#archiveToggle');
const transfer = document.querySelector('#transferToggle');
const request = document.querySelector('#requestToggle');
const specReports = document.querySelector('#spec_reportsToggle');
const finReports = document.querySelector('#fin_reportsToggle');

function createTag() {
    const Select = document.querySelectorAll('#create');

    Select.forEach(function (item) {
        item.checked = item.checked !== true;


    });
}

function readTag() {
    const Select = document.querySelectorAll('#read');

    Select.forEach(function (item) {
        item.checked = item.checked !== true;

    });
}

function updateTag() {
    const Select = document.querySelectorAll('#update');

    Select.forEach(function (item) {
        item.checked = item.checked !== true;

    });
}

function deleteTag() {
    const Select = document.querySelectorAll('#delete');

    Select.forEach(function (item) {
        item.checked = item.checked !== true;

    });
}

function archiveTag() {
    const Select = document.querySelectorAll('#archive');

    Select.forEach(function (item) {
        item.checked = item.checked !== true;

    });
}

function transferTag() {
    const Select = document.querySelectorAll('#transfer');

    Select.forEach(function (item) {
        item.checked = item.checked !== true;

    });
}

function requestTag() {
    const Select = document.querySelectorAll('#request');

    Select.forEach(function (item) {
        item.checked = item.checked !== true;

    });
}

function specTag() {
    const Select = document.querySelectorAll('#spec_reports');

    Select.forEach(function (item) {
        item.checked = item.checked !== true;

    });
}

function finTag() {
    const Select = document.querySelectorAll('#fin_reports');

    Select.forEach(function (item) {
        item.checked = item.checked !== true;

    });
}
