function hamburguerEvents(){
    const bar = document.querySelector("#sideBar")
    const openButton = document.querySelector("#hamburguer")
    const closeButton = document.querySelector("#outsideSideBar")

    openButton.addEventListener("click", () => {
        bar.classList.remove("hidden")
    })

    closeButton.addEventListener("click", () => {
        bar.classList.add("hidden")
    })
}

hamburguerEvents()

function searchBarEvent(){
    const button = document.querySelector('#search')
    const label = button.querySelector('#search_label')
    const input = button.querySelector('input');
    const clear = document.querySelector('#search_clear');

    input.addEventListener("click", e => e.stopPropagation())

    clear.addEventListener("click", e => {
        input.value = ""

        var event = new CustomEvent("input", { "value": "" })
        input.dispatchEvent(event)
        e.stopPropagation()
    })

    button.addEventListener("click", e => {
        if(!e.pointerType == ''){
            button.classList.toggle('active')
            label.classList.toggle('hidden')
            input.classList.toggle('hidden')
            clear.classList.toggle('hidden')
        }
        if(button.classList.contains('active'))
            input.focus()
        else{
            if(!e.pointerType == ''){
                input.value = ""
                var event = new CustomEvent("input", { "value": "" })
                input.dispatchEvent(event)
            }
            
        }
    })
}

searchBarEvent()

function cartEvents(){
    cartBar = document.getElementById("cartBar")
    if(cartBar == null){
        return
    }
    const open = document.getElementById("btn_cart")
    close = document.querySelector("#cartBar .close")

    open.addEventListener("click", () => {
        cartBar.classList.remove("hidden")
    })

    close.addEventListener("click", () => {
        cartBar.classList.add("hidden")
    })

    document.getElementById('responsive_cart').addEventListener("click",
    () =>{
        cartBar.classList.remove("hidden")
    })
}

cartEvents()