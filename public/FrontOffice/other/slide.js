let img_slider = document.getElementsByClassName("carousel-item");

// etape pour savoir quel image afficher
let etape = 0;
// le nbre d'images
let nbre_img = img_slider.length;

let precedent = document.querySelector(".precedent");
let suivant = document.querySelector(".suivant");
// une classe active pr savoir le deplacement

function removeActiveImg() {
  for (let i = 0; i < nbre_img; i++) {
    img_slider[i].classList.remove("active");
  }
}

// qd on clique sur next la classe active est enlever => elle se deplace
suivant.addEventListener("click", function () {
  etape++;
  if (etape >= nbre_img) {
    etape = 0;
  }
  removeActiveImg();
  img_slider[etape].classList.add("active");
});
precedent.addEventListener("click", function () {
  etape--;
  if (etape < 0) {
    etape = nbre_img - 1;
  }
  removeActiveImg();
  img_slider[etape].classList.add("active");
});

const Myintervale = setInterval(() => {
  etape++;
  if (etape >= nbre_img) {
    etape = 0;
  }
  removeActiveImg();
  img_slider[etape].classList.add("active");
}, 8000);
