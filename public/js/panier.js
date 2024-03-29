document.addEventListener("DOMContentLoaded", function () {
  let addcard = document.getElementsByClassName("addcard");
  let buynow = document.getElementById("buy");
  let container = document.getElementsByClassName("container");
  let progressbtn1 = document.getElementsByClassName("progressbtn1");
  let progressbtn2 = document.getElementsByClassName("progressbtn2");
  let valprogress = document.getElementsByClassName("valprogress");
  let user_id = document.getElementById("user_id");
  var noti = document.getElementById("panier");
  let search = document.getElementsByClassName("search");
  let productsQuickView = document.getElementById("productsQuickView");
  let titre_produit = document.getElementById("titre_produit");
  let image_produit = document.getElementById("image_produit");
  let new_price = document.getElementById("new_price");
  let old_price = document.getElementById("old_price");
  let description_produit = document.getElementById("description_produit");
  let product_review = document.getElementById("product_review");
  let variation_choisi = document.getElementsByClassName("variation-choisi");
  let message = document.getElementById("message");
  let row = document.getElementById("row");
  const loader = document.querySelector("#loading");
  row.style.display = "none";
  for (let but of search) {
    but.addEventListener("click", (e) => {
      row.style.display = "none";

      function displayLoading() {
        loader.classList.add("display");
        // to stop loading after some time
        setTimeout(() => {
          loader.classList.remove("display");
        }, 5000);
      }

      // hiding loading
      function hideLoading() {
        loader.classList.remove("display");
      }
      displayLoading();

      i = findindex_search(but);
      // console.log("le i : " + i);

      let vals = [];
      let matches = search[i].id.match(/(\d+)/);
      vals.push(matches[0]);

      // vals.push(search[i].id.charAt(search[i].id.length - 1));

      // vals.push(search[i].id.charAt(search[i].id.length - 1));
      // console.log("search id: " + search[i].id);

      fetch(`/getProduit/${vals}`)
        .then((response) => {
          if (response.ok) {
            return response.json();
          } else {
            console.log("mauvaise réponse!");
          }
        })
        .then((data) => {
          hideLoading();
          row.style.display = "flex";
          getinfos_produits(data);
        })
        .catch((err) => {
          console.log(err);
        });
    });

    function getinfos_produits(data) {
      titre_produit.innerHTML = data.designation;
      new_price.innerHTML = data.nouveau_prix + "DH";
      old_price.innerHTML = data.ancien_prix + "DH";
      image_produit.style.backgroundImage =
        "url(../../../uploads/produits/" + data.image_produit + ")";

      if (data.attributs.length != 0) {
        for (let produit_attribut of data.attributs) {
          if (produit_attribut.variations.length != 0) {
            for (let produit_variation of produit_attribut.variations) {
              for (let data_variation of data.variation) {
                if (data_variation.id == produit_variation.id) {
                  product_review.innerHTML = `<div class="item"><a href="${data.id}" class="btn btn-light">Consulter les variations ! </a></div>`;
                  // for (let image of produit_variation.images) {
                  //   for (let data_image of data.images) {
                  //     if (data_image.id == image.id) {
                  //       product_review_images.innerHTML +=
                  //         '<a href="#" ><img class="image1" src="../uploads/others/' +
                  //         image.url +
                  //         '"style="height:50px;width:60px;margin-right:10px;"></a>';
                  //     }
                  //   }
                  // }
                }
              }
            }
          }
        }
      }

      description_produit.innerHTML = data.description;
      addcard[1].setAttribute("id", "addcard" + data.id);
      progressbtn1[1].setAttribute("id", "progressbtn1" + data.id);
      progressbtn2[1].setAttribute("id", "progressbtn2" + data.id);
      valprogress[1].setAttribute("max", data.qte_stock);
      // console.log("id: " + addcard[1].id);
      // console.log("id: " + progressbtn1[1].id);

      fetch(`/panier_length/${parseInt(user_id.innerHTML)}`)
        .then((response) => {
          if (response.ok) {
            return response.json();
          } else {
            console.log("mauvaise réponse!");
          }
        })
        .then((data) => {
          filter_produits(data);
        })
        .catch((err) => {
          console.log("error");
        });

      function filter_produits(data) {
        if (data.length > 0) {
          for (let i = 0; i < data.length; i++) {
            for (let j = 0; j < progressbtn1.length; j++) {
              // console.log(progressbtn1[j]);
              if (
                progressbtn1[j].id.charAt(progressbtn1[j].id.length - 1) ==
                data[i].produit_id
              ) {
                addcard[j].style.display = "none";
                valprogress[j].value = data[i].qte_produit;
              }
            }
          }
        }
      }
      // image_produit.setAttribute(
      //   "src",
      //   "{{ asset('uploads/produits/" + data.image_produit + "') }}"
      // );
    }
  }

  function findindex_search(button) {
    for (let j = 0; j < search.length; j++) {
      if (search[j].id === button.id) {
        return j;
      } else continue;
    }
  }
  // buynow.addEventListener("click", function () {
  //   console.log(1);
  // });
  // console.log(addcard);

  fetch(`/panier_length/${parseInt(user_id.innerHTML)}`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      filter_produits(data);
    })
    .catch((err) => {
      console.log("error");
    });

  function filter_produits(data) {
    if (data.length > 0) {
      for (let i = 0; i < data.length; i++) {
        for (let j = 0; j < progressbtn1.length; j++) {
          // console.log(progressbtn1[j]);
          if (
            progressbtn1[j].id.charAt(progressbtn1[j].id.length - 1) ==
            data[i].produit_id
          ) {
            addcard[j].style.display = "none";
            valprogress[j].value = data[i].qte_produit;
          }
        }
      }
    }
  }

  for (let i = 0; i < addcard.length; i++) {
    addcard[i].addEventListener("click", test);
    function test() {
      testclick();
    }

    function testclick() {
      // let vals = [];
      // var matches = addcard[i].id.match(/(\d+)/);
      // console.log(matches[0]);

      //   sessionStorage.setItem("clone", matches[0]);
      let verified = 0;
      if (variation_choisi.length != 0 && i == 0) {
        for (let j = 0; j < variation_choisi.length; j = j + 2) {
          // console.log(variation_choisi[j]);
          if (variation_choisi[j].innerHTML != "") {
            verified = 1;
          } else verified = 0;
          // console.log("verified:" + verified);
        }
        if (verified == 0) {
          message.innerHTML =
            "vous devez choisir les variations que vous voulez";
          message.style.display = "block";
          setTimeout(function () {
            message.style.display = "none";
          }, 5000);
        } else {
          addcard[i].innerHTML = "ajouté à la carte";
          setTimeout(function () {
            addcard[i].innerHTML = "";
          }, 2000);
          setTimeout(function () {
            addcard[i].style.display = "none";
          }, 2000);
          addcard[i].style.backgroundColor = "#28a745";
          addcard[i].style.border = "none";
          addcard[i].style.outline = "none";
        }
      } else {
        addcard[i].innerHTML = "ajouté à la carte";
        setTimeout(function () {
          addcard[i].innerHTML = "";
        }, 2000);
        setTimeout(function () {
          addcard[i].style.display = "none";
        }, 2000);
        addcard[i].style.backgroundColor = "#28a745";
        addcard[i].style.border = "none";
        addcard[i].style.outline = "none";
      }
    }
  }

  for (let but of addcard) {
    but.addEventListener("click", (e) => {
      // var add = Number(noti.getAttribute("data-count") || 0);
      i = findindexadd_card(but);
      // console.log(but);
      // console.log("le i : " + i);
      // container[i].style.display = "block";
      // but.style.display = "none";
      // noti.setAttribute("data-count", add + 1);
      // noti.classList.add("zero");
      let vals = [];
      let qte = [];
      if (variation_choisi.length != 0 && i == 0) {
        let verified = 0;

        for (let j = 0; j < variation_choisi.length; j = j + 2) {
          if (variation_choisi[j].innerHTML != "") {
            verified = 1;
          } else verified = 0;
        }
        if (verified == 0) {
          message.innerHTML =
            "vous devez choisir les variations que vous voulez";
          message.style.display = "block";
          setTimeout(function () {
            message.style.display = "none";
          }, 5000);
        } else {
          let vals = [];
          let qte = [];
          let variations = [];
          // vals.push(progressbtn1[i].id.charAt(progressbtn2[i].id.length - 1));
          // console.log("voila : " + progressbtn1[i].id);

          let matches = addcard[i].id.match(/(\d+)/);

          vals.push(matches[0]);

          for (let choisi of variation_choisi) {
            if (choisi.innerHTML != "") {
              variations.push(choisi.innerHTML.trim());
            }
          }
          // qte.push(Number(valprogress[i].value));
          // console.log("qte : " + qte[0]);

          qte.push(Number(valprogress[i].value));

          fetch(
            `/quantite/panier/${vals}/${qte}/${variations}/${parseInt(
              user_id.innerHTML
            )}`
          );
          // .then((response) => {
          //   if (response.ok) {
          //     return response.json();
          //   } else {
          //     console.log("mauvaise réponse!");
          //   }
          // })
          // .then((data) => {
          //   console.log(data);
          // })
          // .catch((err) => {
          //   console.log("error");
          // });
          noti.innerHTML =
            parseInt(noti.innerHTML) + Number(valprogress[i].value);
        }
      } else {
        // vals.push(progressbtn1[i].id.charAt(progressbtn2[i].id.length - 1));
        // console.log("voila : " + progressbtn1[i].id);

        let matches = addcard[i].id.match(/(\d+)/);
        // console.log(matches[0]);
        vals.push(matches[0]);

        qte.push(Number(valprogress[i].value));
        // console.log("qte : " + qte[0]);

        fetch(`/panier/${vals}/${qte}/${parseInt(user_id.innerHTML)}`);
        // .then((response) => {
        //   if (response.ok) {
        //     return response.json();
        //   } else {
        //     console.log("mauvaise réponse!");
        //   }
        // })
        // .then((data) => {
        //   console.log(data);
        // })
        // .catch((err) => {
        //   console.log("error");
        // });
        noti.innerHTML =
          parseInt(noti.innerHTML) + Number(valprogress[i].value);
      }
    });
  }
  //progress
  for (let but of progressbtn1) {
    but.addEventListener("click", (e) => {
      // console.log(but.id);
      i = findindex1(but);
      //hadi hta t3awd tchofha

      if (Number(valprogress[i].value) > Number(valprogress[i].max)) {
        valprogress[i].value = valprogress[i].value - 1;
        noti.innerHTML = Number(noti.innerHTML);
      } else if (
        addcard[i].textContent.includes("Ajouter au panier") == false ||
        addcard[i].style.display == "none"
      ) {
        noti.innerHTML = Number(noti.innerHTML) + 1;
      }
      //ha la fin dylha

      // console.log(addcard[i].style.display);

      if (
        addcard[i].textContent.includes("Ajouter au panier") == false ||
        addcard[i].style.display == "none"
      ) {
        if (variation_choisi.length != 0 && i == 0) {
          let vals = [];
          let qte = [];
          let variations = [];
          // vals.push(progressbtn1[i].id.charAt(progressbtn2[i].id.length - 1));
          // console.log("voila : " + progressbtn1[i].id);

          let matches = addcard[i].id.match(/(\d+)/);
          vals.push(matches[0]);

          for (let choisi of variation_choisi) {
            if (choisi.innerHTML != "") {
              variations.push(choisi.innerHTML.trim());
            }
          }
          fetch(`/variations_panier_check/${vals}/${variations}`)
            .then((response) => {
              if (response.ok) {
                return response.json();
              } else {
                console.log("mauvaise réponse!");
              }
            })
            .then((data) => {
              // console.log(data);
            })
            .catch((err) => {
              console.log("error");
            });

          // qte.push(Number(valprogress[i].value));
          // console.log("qte : " + qte[0]);
          // if (Number(valprogress[i].value) > Number(valprogress[i].max)) {
          //   valprogress[i].value = valprogress[i].value - 1;
          //   noti.innerHTML = Number(noti.innerHTML);
          //   console.log("nadi");
          // } else {
          //   noti.innerHTML = Number(noti.innerHTML) + 1;
          // }
          qte.push(Number(valprogress[i].value));
          // console.log("qte : " + qte[0]);

          fetch(
            `/quantite/panier_edit/${vals}/${qte}/${variations}/${parseInt(
              user_id.innerHTML
            )}`
          );
          // .then((response) => {
          //   if (response.ok) {
          //     return response;
          //   } else {
          //     console.log("mauvaise réponse!");
          //   }
          // })
          // .then((data) => {
          //   console.log(data);
          // })
          // .catch((err) => {
          //   console.log("error");
          // });
          // noti.innerHTML =
          //   parseInt(noti.innerHTML) + Number(valprogress[i].value);
        } else {
          let vals = [];
          let qte = [];
          // vals.push(progressbtn1[i].id.charAt(progressbtn2[i].id.length - 1));
          let matches = addcard[i].id.match(/(\d+)/);
          vals.push(matches[0]);
          qte.push(Number(valprogress[i].value));
          // if (Number(valprogress[i].value) > Number(valprogress[i].max)) {
          //   valprogress[i].value = valprogress[i].value - 1;
          //   noti.innerHTML = Number(noti.innerHTML);
          //   console.log("nadi");
          // } else {
          //   noti.innerHTML = Number(noti.innerHTML) + 1;
          // }
          fetch(`/panier_edit/${vals}/${qte}/${parseInt(user_id.innerHTML)}`);
          // .then((response) => {
          //   if (response.ok) {
          //     return response;
          //   } else {
          //     console.log("mauvaise réponse!");
          //   }
          // })
          // .then((data) => {
          //   console.log(data);
          // })
          // .catch((err) => {
          //   console.log("error");
          // });

          // console.log(valprogress[i].value);
          // console.log(noti.innerHTML);
        }
      }

      // valprogress[i].innerHTML++;

      // let prix = document
      //   .getElementById("prix" + but.id.charAt(but.id.length - 1))
      //   .innerHTML.replace("DHS", "");
      // console.log(prix);

      // total = total + parseInt(prix);
      // apayer.innerHTML = "a payer  <br>" + total + "DH";
    });
  }
  for (let but of progressbtn2) {
    but.addEventListener("click", (e) => {
      i = findindex2(but);
      // if (valprogress[i].innerHTML != 0) {
      //   valprogress[i].innerHTML--;
      // }
      // console.log("fin");
      // console.log(valprogress[i]);
      // console.log(add);
      let vals = [];
      let qte = [];
      // vals.push(progressbtn1[i].id.charAt(progressbtn2[i].id.length - 1));
      let matches = addcard[i].id.match(/(\d+)/);
      vals.push(matches[0]);
      qte.push(Number(valprogress[i].value));

      if (
        addcard[i].textContent.includes("Ajouter au panier") == false ||
        addcard[i].style.display == "none"
      ) {
        fetch(`/panier_edit/${vals}/${qte}/${parseInt(user_id.innerHTML)}`);
        // .then((response) => {
        //   if (response.ok) {
        //     return response;
        //   } else {
        //     console.log("mauvaise réponse!");
        //   }
        // })
        // .then((data) => {
        //   console.log(data);
        // })
        // .catch((err) => {
        //   console.log("error");
        // });

        // console.log(valprogress[i].value);
      }
      if (
        Number(valprogress[i].value) != 0 &&
        addcard[i].style.display == "none"
      ) {
        noti.innerHTML = Number(noti.innerHTML) - 1;
      }

      if (Number(valprogress[i].value) == 0) {
        if (addcard[i].style.display == "none") {
          noti.innerHTML = Number(noti.innerHTML) - 1;
        }
        valprogress[i].value = 1;

        // console.log(valprogress[i].parentNode.parentNode.children[1]);
        valprogress[i].parentNode.parentNode.children[1].innerHTML =
          "Ajouter au panier";

        valprogress[i].parentNode.parentNode.children[1].style.display =
          "inline";

        valprogress[i].parentNode.parentNode.children[1].style.backgroundColor =
          "#f99459";

        fetch(`/panier_delete/${vals}/${parseInt(user_id.innerHTML)}`);
        // .then((response) => {
        //   if (response.ok) {
        //     return response;
        //   } else {
        //     console.log("mauvaise réponse!");
        //   }
        // })
        // .then((data) => {
        //   console.log(data);
        // })
        // .catch((err) => {
        //   console.log("error");
        // });
      }

      // let prix = document
      //   .getElementById("prix" + but.id.charAt(but.id.length - 1))
      //   .innerHTML.replace("DHS", "");
      // console.log(prix);
      // if (total != 0) {
      //   total -= parseInt(prix);
      // }
      // apayer.innerHTML = "a payer  <br>" + total + "DH";
    });
  }

  function findindexadd_card(button) {
    // console.log(addcard.length);
    for (let j = 0; j < addcard.length; j++) {
      if (addcard[j].id === button.id) {
        // console.log(j);
        return j;
      } else continue;
    }
  }
  function findindex2(button) {
    // console.log(progressbtn2.length);
    for (let j = 0; j < progressbtn2.length; j++) {
      if (progressbtn2[j].id === button.id) return j;
      else continue;
    }
  }
  function findindex1(button) {
    // console.log(progressbtn1.length);
    for (let j = 0; j < progressbtn1.length; j++) {
      if (progressbtn1[j].id === button.id) return j;
      else continue;
    }
  }
  var valeurs = [];
  var qte = [];
  noti.addEventListener("click", function () {
    // console.log("m3lm");

    for (let i = 0; i < addcard.length; i++) {
      var matches = addcard[i].id.match(/(\d+)/);
      //   sessionStorage.setItem("clone", matches[0]);
      valeurs.push(matches[0]);
      qte.push(Number(valprogress[i].value));
    }
    window.location.href = "/panier_infos/" + Number(user_id.innerHTML); //on fait pas le panier.php/?.. mais panier.php?...
  });
});
