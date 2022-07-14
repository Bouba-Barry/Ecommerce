document.addEventListener("DOMContentLoaded", function () {
  let addcard = document.getElementsByClassName("addcard");
  let buynow = document.getElementById("buy");

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

  for (var but of addcard) {
    but.addEventListener("click", (e) => {
      var add = Number(noti.getAttribute("data-count") || 0);
      if (add !== 0) {
        noti.setAttribute("data-count", add + 1);
        noti.classList.add("zero");
      } else {
        noti.setAttribute("data-count", add + 1);
        noti.classList.add("zero");
      }
    });
  }
  noti.addEventListener("click", function () {
    window.location.href = "http://127.0.0.1:8000/panier/" + vals; //on fait pas le panier.php/?.. mais panier.php?...
  });
});
