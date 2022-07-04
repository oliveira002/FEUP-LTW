function underlineMenuNav(){
    menus = [...document.querySelectorAll("#menus .list")]

    scrolls = menus.map(e => e.getBoundingClientRect().top - 4)
    scrolls[0] = 0

    maxScroll = scrolls[scrolls.length - 1] + menus[scrolls.length - 1].getBoundingClientRect().height;

    nav = document.querySelectorAll("#rest_menus li")

    window.addEventListener("scroll", () => {
        if(window.scrollY > maxScroll){
            nav.forEach(e => e.querySelector('a').classList.remove("underline"))
            return
        }

        for(i = scrolls.length - 1; i >= 0; i--){
            if(window.scrollY >= scrolls[i]){
                nav.forEach(e => e.querySelector('a').classList.remove("underline"))
                nav[i].querySelector('a').classList.add("underline")
                break
            }
        }
    })
}
var cartCookieName = 'cart_to_checkout'
underlineMenuNav()

const input = document.getElementById("product_quantity")
const total = document.getElementById("product_price")

function stepper(btn){
    price = parseFloat(document.querySelector("#product_popup h2").textContent.replace(",", "."))

    id = btn.getAttribute("id");
    min = input.getAttribute("min")
    max = input.getAttribute("max")
    step = parseInt(input.getAttribute("step"))
    val = parseInt(input.getAttribute("value"))

    newVal = val + (id == "product_increment" ? step : -step)
    if(newVal >= min && newVal <= max){
        input.setAttribute("value", newVal);
        total.textContent = (newVal * price).toFixed(2) + "€"
    }
}

function openProductPopupEvent(){
    productPopup = document.getElementById("product_popup")
    btns = document.querySelectorAll("#menus button")

    btns.forEach(b => {
        b.addEventListener("click", () => {
            input.setAttribute("value", 1)
            updateProductPopup(b)
            productPopup.classList.remove("hidden")
        })
    })
}

function updateProductPopup(btn){
    popup = document.getElementById("product_popup")

    id = popup.querySelector("span")
    id.textContent = btn.querySelector("span").textContent

    section = popup.querySelector("section")

    img = section.querySelector("img")
    img.setAttribute("src", btn.querySelector("img").getAttribute("src"))

    btnDetails = btn.querySelectorAll("span")

    pName = section.querySelector("h1")
    pName.textContent = btnDetails[1].textContent
    price = section.querySelector("h2")
    price.textContent = btnDetails[2].textContent

    total.textContent = price.textContent
}

function closeProductPopupEvent(){
    productPopup = document.getElementById("product_popup")
    ways = [document.getElementById("outside_product_popup"), document.querySelector("#product_popup .close")]

    ways.forEach(w => {
        w.addEventListener("click", () => {
            productPopup.classList.add("hidden")
        })
    })
}

openProductPopupEvent()
closeProductPopupEvent()

document.getElementById("place_product").addEventListener("click", () => {
    document.getElementById("product_popup").classList.add("hidden")
    
    addProductFromPopup(parsePopup())
})

function parsePopup(){
    popup = document.getElementById("product_popup")
    const productID = popup.querySelector("span").textContent
    const productName = popup.querySelector("h1").textContent
    const productPrice = popup.querySelector("h2").textContent
    const productQuantity = popup.querySelector("input").getAttribute("value")

    return {
        id: productID,
        name: productName,
        price: productPrice,
        quantity: productQuantity
    }
}

function deleteCookie(cname) {
    document.cookie = cname + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
}

var allProducts = document.querySelectorAll("#menus .list button")
allProducts.forEach(btn => {
    btn.querySelector("img:nth-last-child(2)").addEventListener("click", e => {
        e.preventDefault()
        location.replace('../actions/action_favorite_product.php?id=' + btn.querySelector("span").textContent)
        e.stopPropagation()
    })
});
deleteCookie('cartCookieName')