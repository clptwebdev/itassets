//Search Categories
const categorySearch = document.querySelector("#findCategory");
const categoryResults = document.querySelector("#categoryResults");
const categorySelect = document.querySelector("#categorySelect");

categorySearch.addEventListener("input", function (e) {
    let value = e.target.value;
    if (value.length > 2) {
        const xhttp = new XMLHttpRequest();

        xhttp.onload = function () {
            categoryResults.innerHTML = xhttp.responseText;
            categoryResults.style.visibility = "visible";
            initItems();
        };

        xhttp.open("POST", "/search/category/");
        xhttp.setRequestHeader(
            "Content-type",
            "application/x-www-form-urlencoded"
        );
        xhttp.send(`search=${value}`);
    }
});

document.addEventListener("click", function (e) {
    //If the click is outside of the Search Results or the input then hide the results dropdown
    if (
        !categorySearch.contains(event.target) &&
        !categoryResults.contains(event.target)
    ) {
        categoryResults.style.visibility = "hidden";
    }
});

function initItems() {
    //Gets all of the list items and adds an event listener to them
    //This has to be re-initialised everytime a result set is returned.
    document
        .querySelector("#categoryResults")
        .querySelectorAll("li")
        .forEach(function (item) {
            item.addEventListener("click", function () {
                //Get the information required
                let name = this.getAttribute("data-name");
                let id = this.getAttribute("data-id");
                //Select the Elements
                const cats = document.querySelector("#category_id");
                const elements = document.querySelector("#selectedCategories");
                const array = cats.value.split(",");
                //Check and see if it already exists
                const index = array.indexOf(id);
                if (index == -1) {
                    if (cats.value != "") {
                        cats.value += "," + id;
                    } else {
                        cats.value = id;
                    }
                    let html = `<div id="cat${id}" class="p-2 col-4">
                                        <div class="border border-gray shadow bg-white p-2 rounded d-flex justify-content-between align-items-center">
                                            <span>${name}</span> 
                                            <i class="fas fa-times ml-4 text-danger pointer" data-name="${id}" onclick="javascript:removeCategory(this);"></i>
                                        </div>
                                    </div>`;
                    elements.insertAdjacentHTML("beforeend", html);
                    categoryResults.style.visibility = "hidden";
                    document.querySelector("#findCategory").value = "";
                }
            });
        });
}

function removeCategory(element) {
    const id = element.dataset.name;
    const div = document.querySelector("#cat" + id);
    const cats = document.querySelector("#category_id");
    //Split the String by (,) and put them into an array
    const array = cats.value.split(",");
    //Find the index of the element you would like to remove
    const index = array.indexOf(id);
    console.log(index);
    if (index > -1) {
        //If found remove the index from the array
        array.splice(index, 1);
    }
    //Join the Array (Back to String). join() is empty so by default seperates by a comma
    div.remove();
    cats.value = array.join();
}

//Search for the Supplier
const supplierSearch = document.querySelector("#findSupplier");
const supplierResults = document.querySelector("#supplierResults");

supplierSearch.addEventListener("input", function (e) {
    let value = e.target.value;
    if (value.length > 2) {
        const xhttp = new XMLHttpRequest();

        xhttp.onload = function () {
            supplierResults.innerHTML = xhttp.responseText;
            supplierResults.style.visibility = "visible";
            initSupplierItems();
        };

        xhttp.open("POST", "/search/suppliers/");
        xhttp.setRequestHeader(
            "Content-type",
            "application/x-www-form-urlencoded"
        );
        xhttp.send(`search=${value}`);
    }
});

function initSupplierItems() {
    //Gets all of the list items and adds an event listener to them
    //This has to be re-initialised everytime a result set is returned.
    document
        .querySelector("#supplierResults")
        .querySelectorAll("li")
        .forEach(function (item) {
            item.addEventListener("click", function () {
                //Get the information required
                let name = this.getAttribute("data-name");
                let id = this.getAttribute("data-id");
                //Select the Elements
                const cats = document.querySelector("#supplier_id");
                cats.value = id;
                supplierResults.style.visibility = "hidden";
                document.querySelector("#findSupplier").value = name;
                getSupplierInfo(id);
            });
        });
}

const supplierInfo = document.querySelector("#supplierInfo");

function getSupplierInfo(id) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
        supplierInfo.innerHTML = xhttp.responseText;
    };

    xhttp.open("POST", "/supplier/preview/");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`id=${id}`);
}

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
