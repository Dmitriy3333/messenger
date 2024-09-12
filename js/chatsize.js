// изменение размера блока текста при его заполнениии в чате 
var textarea_chat = document.querySelector(".text-area-chat");
var div_chat_area = document.querySelector(".form-main"); // это div, где находится textarea
var height_div_chat = div_chat_area.clientHeight;

function countLines() {
        var lines = textarea_chat.value.split('\n').length; // количество переносов текста
        var height_izmen = 38 * lines;
        div_chat_area.style.height = height_izmen + "px";
        textarea_chat.style.height = height_div_chat - 120 + height_izmen + "px";
}

textarea_chat.oninput = function() {
    countLines();
}