document.addEventListener("DOMContentLoaded", function () {
  let progressbtn1 = document.getElementsByClassName("progressbtn1");
  let progressbtn2 = document.getElementsByClassName("progressbtn2");
  console.log(progressbtn1);
  let valprogress = document.getElementsByClassName("valprogress");
  let prix = document.getElementsByClassName("prix");
  console.log(valprogress);
  var i;
  var total = 0;
  console.log(prix);
  for (let i = 0; i < prix.length; i++) {
    console.log(prix[i].innerHTML);
    total = total + parseInt(prix[i].innerHTML.replace("DHS", ""));
  }
  var apayer = document.getElementById("total");

  apayer.innerHTML = "a payer  <br>" + total + "DH";
  //   const beasts = ["ant", "bison", "camel", "duck", "bison"];

  //   console.log(beasts.indexOf("bison"));
  for (let but of progressbtn1) {
    but.addEventListener("click", (e) => {
      console.log(but.id);
      i = findindex1(but);

      valprogress[i].innerHTML++;

      let prix = document
        .getElementById("prix" + but.id.charAt(but.id.length - 1))
        .innerHTML.replace("DHS", "");
      console.log(prix);
      total = total + parseInt(prix);
      apayer.innerHTML = "a payer  <br>" + total + "DH";
    });
  }
  for (let but of progressbtn2) {
    but.addEventListener("click", (e) => {
      i = findindex2(but);
      valprogress[i].innerHTML--;
      let prix = document
        .getElementById("prix" + but.id.charAt(but.id.length - 1))
        .innerHTML.replace("DHS", "");
      console.log(prix);
      total -= parseInt(prix);
      apayer.innerHTML = "a payer  <br>" + total + "DH";
    });
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

  //   if (progressbtn1) {
  //     progressbtn1.addEventListener("click", function () {
  //       //   if (total == "" || terminerachat.style.display != "none") {
  //       //     valprogress.innerHTML++;
  //       //   }
  //       valprogress.innerHTML++;
  //     });
  //   }
  //   if (progressbtn2) {
  //     progressbtn2.addEventListener("click", function () {
  //       if (valprogress.innerHTML == 0) valprogress.innerHTML = 0;
  //       else valprogress.innerHTML--;
  //       //   } else {
  //       //     if (total == "" || terminerachat.style.display != "none")
  //       //       valprogress.innerHTML--;
  //       //   }
  //     });
  //   }

  //   let prix = document.getElementById("prix").innerHTML.replace("dhs", "");
  //   let total = prix * valprogress.innerHTML;
});
