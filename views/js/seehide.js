let inicial = document.querySelector(".inicial");
let instance = document.querySelector(".instance");
let path = document.querySelector(".path");

function uploadTexto(){
    inicial.classList.add("hide");
    instance.classList.remove("hide");
    path.classList.add("hide");
}

function selectInstance(){
    inicial.classList.add("hide");
    instance.classList.add("hide");
    path.classList.remove("hide");
}