var photos = document.querySelectorAll(".big");
var bigPhoto = document.querySelector(".big-photo");
var zaslon = document.querySelector(".zaslonka");

for (photo of photos) {
    photo.onclick = function() {
        // alert(this.src);
        var thisSRC = this.src;
        bigPhoto.src = thisSRC
        zaslon.style.display = "block";
        bigPhoto.style.display = "block";
    }
}

zaslon.onclick = function() {
    zaslon.style.display = "none";
    bigPhoto.style.display = "none";
}

bigPhoto.onclick = function() {
    zaslon.style.display = "none";
    bigPhoto.style.display = "none";
}