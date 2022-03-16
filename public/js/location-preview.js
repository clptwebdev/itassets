//Require the Page to have the #FindLocation and the #LocationResults elements on the page

//Search for the Location
const locationSearch = document.querySelector("#findLocation");
const locationResults = document.querySelector("#locationResults");

locationSearch.addEventListener("input", function (e) {
    let value = e.target.value;
    if (value.length > 2) {
        const xhttp = new XMLHttpRequest();

        xhttp.onload = function () {
            locationResults.innerHTML = xhttp.responseText;
            locationResults.style.visibility = "visible";
            initLocationItems();
        };

        xhttp.open("POST", "/search/locations/");
        xhttp.setRequestHeader(
            "Content-type",
            "application/x-www-form-urlencoded"
        );
        xhttp.send(`search=${value}`);
    }
});

function initLocationItems() {
    //Gets all of the list items and adds an event listener to them
    //This has to be re-initialised everytime a result set is returned.
    document
        .querySelector("#locationResults")
        .querySelectorAll("li")
        .forEach(function (item) {
            item.addEventListener("click", function () {
                //Get the information required
                let name = this.getAttribute("data-name");
                let id = this.getAttribute("data-id");
                //Select the Elements
                const cats = document.querySelector("#location_id");
                cats.value = id;
                locationResults.style.visibility = "hidden";
                locationSearch.value = name;
                getLocationInfo(id);
            });
        });
}

const locationInfo = document.querySelector("#locationInfo");

function getLocationInfo(id) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        locationInfo.innerHTML = xhttp.responseText;
    };

    xhttp.open("POST", "/location/preview/");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`id=${id}`);
}
