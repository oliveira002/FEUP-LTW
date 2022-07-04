// Show search option on header
document.querySelector("#options li").classList.remove('hidden')
// For responsive css rule that applies only to index page
document.querySelector("header").classList.add('index')

// Listen filters
const orderFilters = document.querySelectorAll('input[name="order"]')
const priceFilters = document.querySelectorAll('input[name="price"]')
const timeFilters = document.querySelectorAll('input[name="time"]')
document.querySelectorAll("input[type=\"radio\"]").forEach(element => {
    element.addEventListener("change", updateRestaurantsList)
});

// Listen search bar input
const search = document.querySelector('#search input')
search.addEventListener('input', updateRestaurantsList)

// List categories
const categories = document.querySelectorAll('#rest_categ li button')
categories.forEach(categ => {
    categ.addEventListener('click', () => {
            active = document.getElementById('active_categ')
            if(active) active.id = "";

            categ.id = "active_categ"
            updateRestaurantsList()
        }
    )
});

// Cache number of restaurantes h1
numRestaurants = document.querySelector('#rest_filters h1')

async function updateRestaurantsList(){
    const searchValue = search.value

    lists.querySelectorAll('.list').forEach(e => {
        e.remove();
    })

    const listRestaurants = await updateSearch(searchValue)

    document.getElementById('rest_not_found').classList.toggle('hidden', listRestaurants.length)
    document.getElementById('lists').classList.toggle('hidden', !listRestaurants.length)

    numRestaurants.textContent = listRestaurants.length + " estabelecimentos";

    const HTML = getHtmlFromListRestaurant(listRestaurants)
    lists.innerHTML = HTML
}

function getOrderFilter(){
    order = "foryou"
    for (const radioButton of orderFilters) {
        if (radioButton.checked) {
            order = radioButton.id;
            break;
        }
    }
    return order
}

function getPriceFilter(){
    price = "none"
    for (const radioButton of priceFilters) {
        if (radioButton.checked) {
            price = radioButton.id;
            break;
        }
    }
    return price
}

function getMaxTimeFilter(){
    maxTime = "max120"
    for (const radioButton of timeFilters) {
        if (radioButton.checked) {
            maxTime = radioButton.id;
            break;
        }
    }
    return maxTime
}

function getActiveCategory(){
    category = 0
    for (i = 0; i < categories.length; i++) {
        if (categories[i].id == 'active_categ') {
            category = i + 1
            break;
        }
    };
    return category
}

async function updateSearch(searchInput){
    const response = await fetch('../api/api_restaurants.php?'
    + 'search=' + searchInput
    + '&order=' + getOrderFilter()
    + '&price=' + getPriceFilter()
    + '&maxTime=' + getMaxTimeFilter().substring(3)
    + '&count=' + 10
    + '&category=' + getActiveCategory())

    return await response.json()
}

function getHtmlFromListRestaurant(ListRestaurants){
    let FinalHtml = `<div class="list">
        <ul>
    `
    for(const r of ListRestaurants){
        const Html = `
            <li>
                <a href="../pages/restaurant.php?id=${r.id}">
                    <img src=../imgs/rest/background_${r.id}.png alt="">
                    <span class="item_description" id="item_title">${r.name}</span>
                    <span class="item_description">${r.minTime}-${r.maxTime} min | Taxa de €0.40</span>
                    <span id="rest_rating">${r.rating}</span>
                </a>
            </li>
    `
    FinalHtml = FinalHtml.concat(Html)
    }
    
    return FinalHtml + `
        </ul>
    </div>
    `
}

updateRestaurantsList()