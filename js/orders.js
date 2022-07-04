function changeOrderStyle() {
    var elements = document.getElementsByClassName("estado");
    for(var i = 0; i < elements.length; i++) {
        if(elements[i].textContent == ' waiting') {
            elements[i].style.color = "red";
        }
        else if(elements[i].textContent == ' ready') {
            elements[i].style.color = "green";

        }
        else if(elements[i].textContent== ' preparing') {
            elements[i].style.color = "blue";
        }
        else if(elements[i].textContent == ' delivered') {
            elements[i].style.color = "green";
        }
    }
} changeOrderStyle();