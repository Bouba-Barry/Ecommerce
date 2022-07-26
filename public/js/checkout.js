let select_region = document.getElementById("select_region");
let select_ville = document.getElementById("select_ville");
console.log(select_region);
console.log(select_ville);
function func() {
  console.log(select_region.value);
  if (select_region.value == 0) {
    select_ville.innerHTML =
      '<select name="ville" class="form-control"><option>choisissez une ville</option></select>';
  } else {
    fetch(`http://127.0.0.1:8000/getVilles/${parseInt(select_region.value)}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        console.log(data);
        villes(data);
      })
      .catch((err) => {
        console.log("error");
      });

    function villes(data) {
      ch = "";
      for (let i = 0; i < data.length; i++) {
        ch +=
          "<option value=" + data[i].id + ">" + data[i].nom_ville + "</option>";
      }
      ch = '<select name="ville" class="form-control">' + ch + "</select>";

      select_ville.innerHTML = ch;
    }
  }
}

let ship_different_address = document.getElementById("ship-different-address");
let adresse_livraison = document.getElementById("adresse_livraison");
ship_different_address.addEventListener("click", function () {
  if (ship_different_address.checked == true) {
    adresse_livraison.style.display = "block";
  } else {
    adresse_livraison.style.display = "none";
  }
});
