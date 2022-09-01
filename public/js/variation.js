let variation_id = document.getElementsByClassName("image");
let image_principale = document.getElementById("image_principale");
let image_principale_hidden = document.getElementById(
  "image_principale_hidden"
);
let valprogress = document.getElementsByClassName("valprogress");
let prix = document.getElementById("old_prix_variable");
let addcard = document.getElementsByClassName("addcard");
let qte_image = document.getElementsByClassName("qte_image");
let qte_reste = document.getElementById("qte_reste");
let qte_reste_hidden = document.getElementById("qte_reste_hidden");
let variation_nom = document.getElementsByClassName("variation_nom");
let variation_choisi = document.getElementsByClassName("variation-choisi");
let variation = document.getElementsByClassName("variation");
let message = document.getElementById("message");
let product_add_to_cart = document.getElementById("product-add-to-cart");
let produit_id = document.getElementById("produit_id");
let matches = produit_id.innerHTML.match(/(\d+)/);
const loader = document.querySelector("#loadingdetails");
loader.style.display = "none";
let vals = [];

setTimeout(function () {
  document.getElementById("message").style.display = "none";
}, 5000);
message.innerHTML = "veuillez chosir la variation que vous voulez";

// variation_id[0].style.border = "1px solid #f35320";

// for (i of variation_nom) {
//   if (i.firstElementchild.classList.includes("btn-light")) {
//     i.firstElementchild.classList.remove("btn-light");
//     i.firstElementchild.classList.add("btn-warning");
//   }
// }
// for (var_nom of variation_nom) {
//3awd chof hadi
// if (variation_nom.innerHTML != "") {
//   console.log("adem");
//   let matches = variation_nom.innerHTML.match(/(\d+)/);
//   vals.push(matches[0]);
//   let matches1 = variation_nom_hidden.innerHTML.match(/(\d+)/);
//   vals.push(matches1[0]);
//   // }
//   window.location.href = "http://127.0.0.1:8000/quantite/check/" + vals;
// }
// htal hna

// fetch(`http://127.0.0.1:8000/quantite/check/${vals}`)
//   .then((response) => {
//     if (response.ok) {
//       return response.json();
//     } else {
//       console.log("mauvaise réponse!");
//     }
//   })
//   .then((data) => {
//     console.log(data);
//     check(data);
//   })
//   .catch((err) => {
//     console.log(err);
//   });

for (let variable of variation_nom) {
  variable.addEventListener("click", function () {
    displayLoading();
    i = findvariation_nom(variable);
    let numero;

    for (let j = 0; j < variation_nom[i].classList.length; j++) {
      if (variation_nom[i].classList[j].includes("variation-choisi")) {
        numero = j;
      }
    }
    j = find_variation_choisi(variation_nom[i].classList[numero]);
    variation_choisi[j].innerHTML = variation_nom[i].innerHTML;
    variable.classList.remove("btn-light");
    variable.classList.add("btn-warning");

    let vals = [];
    for (let choisi of variation_choisi) {
      if (choisi.innerHTML != "") {
        vals.push(choisi.innerHTML.trim());
      }
    }
    // }

    // window.location.href = "http://127.0.0.1:8000/quantite/check/" + vals;
    fetch(`/quantite/check/${vals}/${matches[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        // hideLoading();
        check(data);
      })
      .catch((err) => {
        console.log(err);
      });
    fetch(`/quantite/panier/check/${vals}/${parseInt(user_id.innerHTML)}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        // hideLoading();
        paniercheck(data);
      })
      .catch((err) => {
        console.log(err);
      });

    for (let nom of variation_nom) {
      if (nom != variable) {
        nom.classList.remove("btn-warning");
      }
    }
  });
}

// function findvariation_nom(button) {
//   for (let j = 0; j < variation_nom_others.length; j++) {
//     if (variation_nom_others[j] === button) {
//       return j;
//     } else continue;
//   }
// }

for (let image of variation_id) {
  image.addEventListener("mouseover", function () {
    displayLoading();
    i = findimage(image);

    image_principale.innerHTML = "<img src=" + image.src + ">";
    if (variation_nom[i]) {
      // console.log(" le max : " + valprogress[0].getAttribute("max"));
      // valprogress[0].setAttribute("max", parseInt(qte_image[i].innerHTML));
      // valprogress[0].value = 1;
      // console.log(" le max  aprs " + valprogress[0].getAttribute("max"));
      // if (
      //   Number(valprogress[0].value) >=
      //   Number(valprogress[0].getAttribute("max"))
      // ) {
      //   valprogress[0].value = valprogress[0].getAttribute("max");
      // }
      // qte_reste.innerHTML = "il reste " + qte_image[i].innerHTML + " en Stock";
      j = find_variation_choisi(variation_nom[i].classList[1]);

      variation_choisi[j].innerHTML = variation_nom[i].innerHTML;
    } //else {
    //   variation_nom.innerHTML = "default";
    //   qte_reste.innerHTML = qte_reste_hidden.innerHTML;
    // }
    image.style.border = "1px solid #f35320";
    for (let image_border of variation_id) {
      if (image_border != image) {
        image_border.style.border = "none";
      }
    }
    // if (variation_nom.innerHTML == "default") {
    //   let matches = qte_reste.innerHTML.match(/(\d+)/);
    //   valprogress[0].setAttribute("max", matches[0]);
    //   console.log("Number : " + matches[0]);
    //   valprogress[0].value = 1;
    // }

    let vals = [];
    for (let choisi of variation_choisi) {
      if (choisi.innerHTML != "") {
        vals.push(choisi.innerHTML.trim());
      }
    }
    // }

    // window.location.href = "http://127.0.0.1:8000/quantite/check/" + vals;
    fetch(`/quantite/check/${vals}/${matches[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        // hideLoading();
        check(data);
      })
      .catch((err) => {
        console.log(err);
      });

    fetch(`/quantite/panier/check/${vals}/${parseInt(user_id.innerHTML)}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        paniercheck(data);
      })
      .catch((err) => {
        console.log(err);
      });
  });
}

function check(data) {
  if (data.length > 0) {
    for (let i = 0; i < data.length; i++) {
      qte_reste.innerHTML = "il reste " + data[i].qte_stock + " en Stock";
      prix.innerHTML =
        `<strong style="font-size:25px">` + data[i].prix + " DH</strong>";
      let matches = qte_reste.innerHTML.match(/(\d+)/);
      valprogress[0].setAttribute("max", matches[0]);
    }
    product_add_to_cart.style.display = "inline-block";
    // hideLoading();
  } else {
    qte_reste.innerHTML = "";
  }
}

function paniercheck(data) {
  if (data.length > 0) {
    // for (let i = 0; i < data.length; i++) {
    //   qte_reste.innerHTML = "il reste " + data[i].qte_stock + " en Stockjj";
    //   let matches = qte_reste.innerHTML.match(/(\d+)/);
    valprogress[0].value = data[0].qte_produit;
    addcard[0].innerHTML = "ajouté à la carte";
    addcard[0].style.display = "none";
    hideLoading();
  } else {
    addcard[0].innerHTML = "Ajouter au panier";
    valprogress[0].value = 1;
    addcard[0].style.display = "inline-block";
    hideLoading();
  }
}

function findimage(image) {
  for (let j = 0; j < variation_id.length; j++) {
    if (variation_id[j] === image) {
      return j;
    } else continue;
  }
}
function findvariation_nom(variable) {
  for (let j = 0; j < variation_nom.length; j++) {
    if (variation_nom[j] === variable) {
      return j;
    } else continue;
  }
}
function find_variation_choisi(variable) {
  for (let j = 0; j < variation_choisi.length; j++) {
    if (variation_choisi[j].id === variable) {
      return j;
    } else continue;
  }
}

function displayLoading() {
  loader.style.display = "block";
  // loader.style.width = "20rem";
  // loader.style.height = "20rem";
  loader.classList.add("display");
  // to stop loading after some time
  setTimeout(() => {
    loader.classList.remove("display");
  }, 5000);
}

// hiding loading
function hideLoading() {
  loader.style.display = "none";
  loader.classList.remove("display");
}
