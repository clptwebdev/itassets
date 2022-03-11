const timeoutModal = new bootstrap.Modal(document.getElementById('timeoutModal'));
const timedoutModal = new bootstrap.Modal(document.getElementById('timedoutModal'));
window.onload = function () {
    // let timeout = setInterval(sessionClose, 7100000);
    // let endSession = setInterval(sessionEnd, 7200000);
};

let counter = 0;

//time of 120 minutes which is Laravel's session timer
function sessionClose() {
    if (counter === 0) {
        counter++;
        timeoutModal.show();
    }
}

//end session by creating a modal that cannot close
function sessionEnd() {
    if (counter === 1) {
        counter++;
        timeoutModal.hide();
        timedoutModal.show();
    }

}

//allows session to carry on by resting counter
function resetCounter() {
    timeoutModal.hide();
    counter = 0;
}
