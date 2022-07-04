itemsInCart = JSON.parse(localStorage.getItem("Cart"))
if(itemsInCart == null) itemsInCart = []

cartBar = document.getElementById("cartBar")
totalText = document.getElementById("cb_total")
if(cartBar != null){
    initializeCart()
}

var cartCookieName = 'cart_to_checkout'

if(getCookie(cartCookieName) == ''){
    clearCart()
}

function initializeCart(){
    cartBar.querySelector("#cartBar_rest").textContent = localStorage.getItem("CartRestaurant")
    itemsInCart.forEach(i => addProductToHTML(i, true));
    clearCartCookie()
    onCartUpdate()
}

function isCartEmpty(){
    return itemsInCart.length == 0
}

function onCartUpdate(){
    localStorage.setItem("Cart", JSON.stringify(itemsInCart))
    toggleCart(isCartEmpty())
    updateTotal()
    if(itemsInCart.length == 0){
        return
    }
    let cookieValue = itemsInCart[0].id.toString()+":"+itemsInCart[0].quantity.toString()
    for(let i = 1; i<itemsInCart.length;i++){
        cookieValue = cookieValue + "," + itemsInCart[i].id.toString()+":"+itemsInCart[i].quantity.toString()
    }
    setCookie(cartCookieName,cookieValue,1)
}

function removeProduct(btn, product){
    btn.target.parentElement.remove()

    const index = itemsInCart.indexOf(product)
    if(index > -1) itemsInCart.splice(index, 1)

    onCartUpdate()
}

function updateTotal(){
    let total = 0
    itemsInCart.forEach(i => {
        total += parseFloat(i.price) * parseInt(i.quantity)
    });
    totalText.textContent = total.toFixed(2) + "€"
}

function clearCart(){
    itemsInCart = []
    entries = [...cartBar.querySelectorAll("article")]
    entries.forEach(e => e.remove())
    onCartUpdate()
}

function addProductFromPopup(product){
    checkIfProductIsFromTheSameRestaurant()

    alreadyExisted = false
    for(i = 0; i < itemsInCart.length; i++){
        if(itemsInCart[i].id == product.id){
            alert("This item already existed on cart, we added the quantities!")
            itemsInCart[i].quantity = (parseInt(product.quantity) + parseInt(itemsInCart[i].quantity))
            updateProductQuantity(i, itemsInCart[i].quantity)
            alreadyExisted = true
            break;
        }
    }
    
    if(!alreadyExisted)
        addProductToHTML(product)

    onCartUpdate()
}

function updateProductQuantity(index, newValue){
    cartBar.querySelectorAll(`.cb_entry`)[index].querySelector(".cbe_prod span").textContent = newValue + "x"
}

function checkIfProductIsFromTheSameRestaurant(){
    restHeader = document.getElementById("rest_header")

    currentCartRestaurant = cartBar.querySelector("#cartBar_rest")
    newProductRestaurant = restHeader.querySelector("h1")

    if(currentCartRestaurant.textContent != newProductRestaurant.textContent){
        clearCart()
        currentCartRestaurant.textContent = newProductRestaurant.textContent
    }

    localStorage.setItem("CartRestaurant", newProductRestaurant.textContent)
}

function addProductToHTML(product, cached=false){
    entryContent = `
        <span class="hidden">${product.id}</span>
        <div class="cbe_prod">
            <img src="../imgs/prod/${product.id}.webp" alt="">
            <span>${product.quantity}x</span>
        </div>
        <div class="cbe_details">
            <span>${product.name}</span>
            <span>${product.price}</span>
        </div>
        <img src="../imgs/trash.png" alt="" class="trash">
    `
    
    entry = document.createElement("article")
    entry.classList.add("cb_entry")
    entry.innerHTML = entryContent

    document.getElementById("cartBar_notEmpty").appendChild(entry)
    entry.querySelector(".trash").addEventListener("click", btn => removeProduct(btn, product))

    if(!cached) itemsInCart.push(product)
}

function toggleCart(isEmpty){
    document.getElementById("cartBar_empty").classList.toggle("hidden", !isEmpty)
    document.getElementById("cartBar_notEmpty").classList.toggle("hidden", isEmpty)
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date()
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000))
    const expires = "expires="+d.toUTCString()
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/"
}
  
function getCookie(cname) {
    const name = cname + "="
    const ca = document.cookie.split(';')
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
        c = c.substring(1)
        }
        if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length)
        }
    }
    return "";
}

function deleteCookie(cname) {
    document.cookie = cname + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
}

function openCheckout(url) {
    if(itemsInCart.length == 0){
        return
    }
    let cookieValue = itemsInCart[0].id.toString()+":"+itemsInCart[0].quantity.toString()
    for(let i = 1; i<itemsInCart.length;i++){
        cookieValue = cookieValue + "," + itemsInCart[i].id.toString()+":"+itemsInCart[i].quantity.toString()
    }
    setCookie(cartCookieName,cookieValue,1)
    window.open(url, '_self').focus()
}

function clearCartCookie(){
    deleteCookie(cartCookieName)
    setCookie(cartCookieName,"",1)
}