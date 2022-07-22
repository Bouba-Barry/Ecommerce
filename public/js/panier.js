console.log("dfff");
document.addEventListener("DOMContentLoaded", function () {
  let addcard = document.getElementsByClassName("addcard");
  let buynow = document.getElementById("buy");
  let container = document.getElementsByClassName("container");
  let progressbtn1 = document.getElementsByClassName("progressbtn1");
  let progressbtn2 = document.getElementsByClassName("progressbtn2");
  let valprogress = document.getElementsByClassName("valprogress");
  let user_id = document.getElementById("user_id");

  console.log(user_id.innerHTML);
  // buynow.addEventListener("click", function () {
  //   console.log(1);
  // });
  // console.log(addcard);
  var vals = [];
  for (let i = 0; i < addcard.length; i++) {
    addcard[i].addEventListener("click", test);
    function test() {
      testclick();
    }

    function testclick() {
      var matches = addcard[i].id.match(/(\d+)/);
      //   sessionStorage.setItem("clone", matches[0]);
      vals.push(matches[0]);
      addcard[i].innerHTML = "added to card";
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

  var noti = document.getElementById("panier");
  console.log(noti);

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
      noti.innerHTML = Number(valprogress[i].value);
    });
  }
  //progress
  for (let but of progressbtn1) {
    but.addEventListener("click", (e) => {
      // console.log(but.id);
      i = findindex1(but);
      console.log(addcard[i].textContent);

      // console.log(add);

      if (addcard[i].textContent.includes("Add to cart") == false) {
        console.log(valprogress[i].value);
        noti.innerHTML = Number(noti.innerHTML) + 1;
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
      console.log("fin");
      console.log(valprogress[i]);
      // console.log(add);
      if (Number(valprogress[i].value) == 1) {
        console.log(valprogress[i].parentNode.parentNode.children[1]);
        valprogress[i].parentNode.parentNode.children[1].innerHTML =
          "add to cart";

        valprogress[i].parentNode.parentNode.children[1].style.display =
          "inline";

        valprogress[i].parentNode.parentNode.children[1].style.backgroundColor =
          "#f99459";
      }
      if (Number(noti.innerHTML) != 0) {
        noti.innerHTML = Number(noti.innerHTML) - 1;
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
    console.log(addcard.length);
    for (let j = 0; j < addcard.length; j++) {
      if (addcard[j].id === button.id) {
        console.log(j);
        return j;
      } else continue;
    }
  }
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
  var valeurs = [];
  var qte = [];
  noti.addEventListener("click", function () {
    console.log("m3lm");

    for (let i = 0; i < addcard.length; i++) {
      var matches = addcard[i].id.match(/(\d+)/);
      //   sessionStorage.setItem("clone", matches[0]);
      valeurs.push(matches[0]);
      qte.push(Number(valprogress[i].value));
    }
    window.location.href =
      "http://127.0.0.1:8000/panier/" +
      valeurs +
      "/" +
      qte +
      "/" +
      Number(user_id.innerHTML); //on fait pas le panier.php/?.. mais panier.php?...
  });
});
