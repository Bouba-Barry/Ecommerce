let variation_id = document.getElementsByClassName("image");
let image_principale = document.getElementById("image_principale");

for (let image of variation_id) {
  image.addEventListener("click", function () {
    image_principale.innerHTML = "<img src=" + image.src + ">";
    image.style.border = "1px solid #f35320";
    for (let image_border of variation_id) {
      if (image_border != image) {
        image_border.style.border = "none";
      }
    }
    console.log("hh");
  });
}
