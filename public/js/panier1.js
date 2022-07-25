document.addEventListener("DOMContentLoaded", function () {
  let progressbtn1 = document.getElementsByClassName("progressbtn1");
  let progressbtn2 = document.getElementsByClassName("progressbtn2");
  let valprogress = document.getElementsByClassName("valprogress");
  let prix = document.getElementsByClassName("prix");
  let total_par_produit = document.getElementsByClassName("total_par_produit");
  let user_id = document.getElementById("user_id");
  let subtotal = document.getElementById("subtotal");
  let total = document.getElementById("total");
  let tva = document.getElementById("tva");
  let panierlength = document.getElementById("panierlength");
  let somme_subtotal = 0;
  let somme_total = 0;
  let panier = document.getElementById("panier");
  console.log(panier);

  //panier number
  // panier.innerHTML = panierlength.innerHTML;

  //   console.log(valprogress);
  //   var i;
  //   var total = 0;
  //   console.log(prix);
  // for (let i = 0; i < prix.length; i++) {
  //    console.log(prix[i].innerHTML);
  //    total = total + parseInt(prix[i].innerHTML.replace("DHS", ""));
  //  }
  //   var apayer = document.getElementById("total");

  //   apayer.innerHTML = "a payer  <br>" + total + "DH";
  //   //   const beasts = ["ant", "bison", "camel", "duck", "bison"];

  //   //   console.log(beasts.indexOf("bison"));

  for (let but of progressbtn1) {
    but.addEventListener("click", (e) => {
      let vals = [];
      let qte = [];

      i = findindex1(but);
      vals.push(progressbtn1[i].id.charAt(progressbtn1[i].id.length - 1));
      qte.push(Number(valprogress[i].value));
      //           valprogress[i].innerHTML++;
      //         let prix = document
      //   .getElementById("prix" + but.id.charAt(but.id.length - 1))
      //   .innerHTML.replace("DHS", "");
      console.log(vals);
      console.log(qte);
      console.log(parseInt(user_id.innerHTML));
      fetch(
        `http://127.0.0.1:8000/panier_edit/${vals}/${qte}/${parseInt(
          user_id.innerHTML
        )}`
      )
        .then((response) => {
          if (response.ok) {
            return response.json();
          } else {
            console.log("mauvaise réponse!");
          }
        })
        .then((data) => {
          console.log(data);
          update_total_par_produit(data);
        })
        .catch((err) => {
          console.log("error");
        });

      function update_total_par_produit(data) {
        console.log(valprogress[i].value);
        let length = 0;
        for (let j = 0; j < valprogress.length; j++) {
          length = length + parseInt(valprogress[j].value);
        }
        console.log(length);
        panier.innerHTML = length;
        let somme =
          parseInt(valprogress[i].value) *
          parseInt(prix[i].innerHTML.replace("DH", ""));
        total_par_produit[i].innerHTML = somme + "DH";
      }

      // for (let i = 0; i < total_par_produit.length; i++) {
      somme_subtotal =
        somme_subtotal + parseInt(prix[i].innerHTML.replace("DH", ""));
      // }
      subtotal.innerHTML = somme_subtotal + "DH";

      somme_total =
        (parseInt(subtotal.innerHTML.replace("DH", "")) *
          parseInt(tva.innerHTML.replace("%", ""))) /
          100 +
        parseInt(subtotal.innerHTML.replace("DH", ""));
      total.innerHTML = somme_total + "DH";

      // let somme =
      //   parseInt(total_par_produit[i].innerHTML.replace("DH", "")) +
      //   parseInt(prix[i].innerHTML);
      // console.log("le : " + somme);
      // total_par_produit[i].innerHTML = somme + "DH";
    });
  }
  //   }
  for (let but of progressbtn2) {
    but.addEventListener("click", (e) => {
      let vals = [];
      let qte = [];
      i = findindex2(but);

      vals.push(progressbtn2[i].id.charAt(progressbtn2[i].id.length - 1));
      qte.push(Number(valprogress[i].value));

      fetch(
        `http://127.0.0.1:8000/panier_edit/${vals}/${qte}/${parseInt(
          user_id.innerHTML
        )}`
      )
        .then((response) => {
          if (response.ok) {
            return response;
          } else {
            console.log("mauvaise réponse!");
          }
        })
        .then((data) => {
          console.log(data);
          update_total_par_produit(data);
        })
        .catch((err) => {
          console.log("error");
        });

      function update_total_par_produit(data) {
        console.log(valprogress[i].value);
        let length = 0;
        for (let j = 0; j < valprogress.length; j++) {
          length = length + parseInt(valprogress[j].value);
        }
        console.log(length);
        panier.innerHTML = length;

        let somme =
          parseInt(valprogress[i].value) *
          parseInt(prix[i].innerHTML.replace("DH", ""));
        total_par_produit[i].innerHTML = somme + "DH";
      }

      for (let i = 0; i < total_par_produit.length; i++) {
        somme_subtotal =
          somme_subtotal +
          parseInt(total_par_produit[i].innerHTML.replace("DH", ""));
      }
      subtotal.innerHTML = somme_subtotal + "DH";

      somme_total =
        (parseInt(subtotal.innerHTML.replace("DH", "")) *
          parseInt(tva.innerHTML.replace("%", ""))) /
          100 +
        parseInt(subtotal.innerHTML.replace("DH", ""));
      total.innerHTML = somme_total + "DH";
      /*
      // console.log("voila : " + parseInt(valprogress[i].value));
      // if (parseInt(valprogress[i].value) > 1) {
      //   let somme =
      //     parseInt(total_par_produit[i].innerHTML.replace("DH", "")) -
      //     parseInt(prix[i].innerHTML);
      //   console.log("le : " + somme);
      //   total_par_produit[i].innerHTML = somme + "DH";
      // }

      // if (parseInt(valprogress[i].value - 1) < 1) {
      //   console.log("avant " + parseInt(valprogress[i].value));
      //   valprogress[i].value = 2;
      //   console.log("apres " + parseInt(valprogress[i].value));
      // } */

      //       let prix = document
      //         .getElementById("prix" + but.id.charAt(but.id.length - 1))
      //         .innerHTML.replace("DHS", "");
      //       console.log(prix);

      //       if (total != 0) {
      //         total -= parseInt(prix);
      //       }

      //       apayer.innerHTML = "a payer  <br>" + total + "DH";
    });
  }

  for (let i = 0; i < total_par_produit.length; i++) {
    somme_subtotal =
      somme_subtotal +
      parseInt(total_par_produit[i].innerHTML.replace("DH", ""));
  }
  subtotal.innerHTML = somme_subtotal + "DH";

  somme_total = 0;
  somme_total =
    (parseInt(subtotal.innerHTML.replace("DH", "")) *
      parseInt(tva.innerHTML.replace("%", ""))) /
      100 +
    parseInt(subtotal.innerHTML.replace("DH", ""));
  total.innerHTML = somme_total + "DH";

  function findindex2(button) {
    console.log(progressbtn2.length);
    for (let j = 0; j < progressbtn2.length; j++) {
      if (progressbtn2[j].id === button.id) return j;
      else continue;
    }
  }
  function findindex1(button) {
    console.log(progressbtn1.length);
    for (let j = 0; j < progressbtn1.length; j++) {
      if (progressbtn1[j].id === button.id) return j;
      else continue;
    }
  }

  //   //   if (progressbtn1) {
  //   //     progressbtn1.addEventListener("click", function () {
  //   //       //   if (total == "" || terminerachat.style.display != "none") {
  //   //       //     valprogress.innerHTML++;
  //   //       //   }
  //   //       valprogress.innerHTML++;
  //   //     });
  //   //   }
  //   //   if (progressbtn2) {
  //   //     progressbtn2.addEventListener("click", function () {
  //   //       if (valprogress.innerHTML == 0) valprogress.innerHTML = 0;
  //   //       else valprogress.innerHTML--;
  //   //       //   } else {
  //   //       //     if (total == "" || terminerachat.style.display != "none")
  //   //       //       valprogress.innerHTML--;
  //   //       //   }
  //   //     });
  //   //   }

  //   //   let prix = document.getElementById("prix").innerHTML.replace("dhs", "");
  //   //   let total = prix * valprogress.innerHTML;
});
