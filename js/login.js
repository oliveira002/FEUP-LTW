document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll('form')

    var parts = window.location.search.substr(1).split("&");
    var $_GET = {};
    for (var i = 0; i < parts.length; i++) {
        var temp = parts[i].split("=");
        $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
    }

    if($_GET['register']){
        forms[0].classList.toggle('hidden');
        forms[1].classList.toggle('hidden');
    }

    forms[0].querySelector('a').addEventListener("click", e => {
        e.preventDefault();
        forms[0].classList.toggle('hidden');
        forms[1].classList.toggle('hidden');
    });

    forms[1].querySelector('a').addEventListener("click", e => {
        e.preventDefault();
        forms[0].classList.toggle('hidden');
        forms[1].classList.toggle('hidden');
    });
});