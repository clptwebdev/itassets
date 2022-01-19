const searchButton = document.querySelector('#searchButton');
const searchDiv = document.querySelector('#searchBar');
const sortButton = document.querySelector('#sortButton');
const sortDiv = document.querySelector('#sortBar');

searchButton.addEventListener('click', function(e){
    e.preventDefault();
    searchDiv.classList.toggle('d-none');
    searchDiv.classList.toggle('d-inline-block');
    sortDiv.classList.add('d-none');
    sortDiv.classList.remove('d-inline-block');
})

sortButton.addEventListener('click', function(e){
    e.preventDefault();
    sortDiv.classList.toggle('d-none');
    sortDiv.classList.toggle('d-inline-block');
    searchDiv.classList.add('d-none');
    searchDiv.classList.remove('d-inline-block');
})

const filter = document.querySelector('#filter');
const filterBtn = document.querySelector('#filterBtn');

const toggleFilter = function(){
    if(filter.classList.contains('show')){
        filter.classList.remove('show');
        filter.style.right = "-100%";
    }else{
        filter.classList.add('show');
        filter.style.right = "0%";
    }
}

// No focus = Changes the background color of input to red
document.addEventListener('click', function(event) {
    if (!filter.contains(event.target) && !filterBtn.contains(event.target)) {
        filter.classList.remove('show');
        filter.style.right = "-100%";
    }
});


