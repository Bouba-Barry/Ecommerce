document.addEventListener("DOMContentLoaded", function () {
  let addcard = document.getElementsByClassName("addcard");
  let buynow = document.getElementById("buy");
  let container = document.getElementsByClassName("container");
  let progressbtn1 = document.getElementsByClassName("progressbtn1");
  let progressbtn2 = document.getElementsByClassName("progressbtn2");
  let valprogress = document.getElementsByClassName("valprogress");

  buynow.addEventListener("click", function () {
    console.log(1);
  });
  console.log(addcard);
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
      addcard[i].style.backgroundColor = "rgb(246, 139, 30)";
      addcard[i].style.border = "none";
      addcard[i].style.outline = "none";
    }
  }

  var noti = document.getElementById("panier");

  for (let but of addcard) {
    but.addEventListener("click", (e) => {
      var add = Number(noti.getAttribute("data-count") || 0);
      i = findindexadd_card(but);
      console.log(but);
      console.log("le i : " + i);
      container[i].style.display = "block";
      but.style.display = "none";
      noti.setAttribute("data-count", add + 1);
      noti.classList.add("zero");
    });
  }
  //progress
  for (let but of progressbtn1) {
    but.addEventListener("click", (e) => {
      console.log(but.id);
      i = findindex1(but);

      valprogress[i].innerHTML++;

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
      if (valprogress[i].innerHTML != 0) {
        valprogress[i].innerHTML--;
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

  noti.addEventListener("click", function () {
    window.location.href = "http://127.0.0.1:8000/panier/" + vals; //on fait pas le panier.php/?.. mais panier.php?...
  });
});
