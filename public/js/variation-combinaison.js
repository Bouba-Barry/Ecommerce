console.log("i am");
let produit = document.getElementById("quantite_produit");
let variations = document.getElementById("quantite_variations");
// let produit_id = produit.options[produit.selectedIndex].value;
// variations.innerHTML = "";
fetch(`http://127.0.0.1:8000/quantite/variations_produit/${produit.value}`)
  .then((response) => {
    if (response.ok) {
      return response.json();
    } else {
      console.log("mauvaise réponse!");
    }
  })
  .then((data) => {
    console.log(data);
    check(data);
  })
  .catch((err) => {
    console.log(err);
  });

produit.addEventListener("click", function () {
  console.log("voila : " + produit.value);
  fetch(`http://127.0.0.1:8000/quantite/variations_produit/${produit.value}`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      console.log(data);
      check(data);
    })
    .catch((err) => {
      console.log(err);
    });
});

function check(data) {
  variations.innerHTML = "";
  if (data.length > 0) {
    for (let i = 0; i < data.length; i++) {
      variations.innerHTML += `<div class="form-check" >
      <input type="checkbox" id="quantite_variations_${data[i].id}" name="quantite[variations][]" class="form-check-input" value="${data[i].id}" >
       <label class="form-check-label" for="quantite_variations_${data[i].id}" >${data[i].nom}(${data[i].attribut.nom})</label>      
    </div>`;
    }
  }
}
