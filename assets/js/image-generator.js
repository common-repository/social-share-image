var color1 = document.querySelector(".color1")
var color2 = document.querySelector(".color2")
var ssi_preview = document.getElementById("ssi_preview")
var ssi_bgImage = document.getElementById("ssi_bgImage")
var linearDirection = document.getElementsByName("toDirection")[0]

function changeColor() {
  ssi_preview.style.background =
    "linear-gradient(" +
    linearDirection.value +
    ", " +
    color1.value +
    "," +
    color2.value +
    ")";
}

window.addEventListener("load", changeColor)
document.querySelector('select[name="toDirection"]').onchange = changeColor;
color1.addEventListener("input", changeColor)
color2.addEventListener("input", changeColor)