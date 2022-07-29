let variation_id = document.getElementsByClassName("image");
let image_principale = document.getElementById("image_principale");
let image_principale_hidden = document.getElementById(
  "image_principale_hidden"
);

let variation_nom = document.getElementById("variation_nom");
let variation_nom_hidden = document.getElementsByClassName(
  "hidden_variation_nom"
);

for (let image of variation_id) {
  image.addEventListener("mouseover", function () {
    i = findimage(image);
    image_principale.innerHTML = "<img src=" + image.src + ">";
    if (variation_nom_hidden[i]) {
      variation_nom.innerHTML = variation_nom_hidden[i].innerHTML;
    } else variation_nom.innerHTML = "default";
    image.style.border = "1px solid #f35320";
    for (let image_border of variation_id) {
      if (image_border != image) {
        image_border.style.border = "none";
      }
    }
    console.log("hh");
  });
}

function findimage(image) {
  for (let j = 0; j < variation_id.length; j++) {
    if (variation_id[j] === image) {
      return j;
    } else continue;
  }
}
