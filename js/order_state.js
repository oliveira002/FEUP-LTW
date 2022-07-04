orderStates = document.querySelectorAll("select")

orderStates.forEach(state => {
    state.addEventListener("change", e => {
        orderID = e.path[1].querySelector('span').textContent.slice(1)
        newState = e.target.options[e.target.selectedIndex].text

        const query = window.location.search
        const urlParams = new URLSearchParams(query)        
        const restID = urlParams.get('id')

        location.replace('../actions/action_change_order_state.php?idOrder=' + orderID + "&newState=" + newState + "&idRest=" + restID)
    })
});