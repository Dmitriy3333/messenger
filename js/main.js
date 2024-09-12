

// тут открытие меню на телефоне
var menuButtonPhone = document.getElementById("menu-phone");
var menuPhone = document.querySelector(".main-box-left");

var info = document.querySelector(".info");

menuButtonPhone.onclick = function() {

    if (menuPhone.style.display == "block") {
        menuPhone.style.display = "none";
        info.style.display = "flex";
    } else {
        menuPhone.style.display = "block";
        info.style.display = "none";
    }
}

// тут открытие панели с настройкой и выходом
var buttonExit = document.querySelector(".to-exit");
var exitWindow = document.querySelector(".main-box-left-open");

buttonExit.onclick = function() {
    if (exitWindow.style.display == "block") {
        exitWindow.style.display = "none";
    } else {
        exitWindow.style.display = "block";
    }
}

// это для новостей
var textarea_news = document.querySelector(".text-area-news");
var div_text_area = document.querySelector(".form-main"); // это div, где находится textarea
var height_div = div_text_area.clientHeight;

function countLines() {
        var lines = textarea_news.value.split('\n').length; // количество переносов текста
        var height_izmen = 28 * lines;
        textarea_news.style.height = height_izmen + "px";
        div_text_area.style.height = height_div - 38 + height_izmen + "px";
}

textarea_news.oninput = function() {
    countLines();
}




