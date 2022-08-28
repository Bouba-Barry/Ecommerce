let produit_edit = document.getElementById("edit_quantite_produit");
let variations_edit = document.getElementById("edit_quantite_variations");

fetch(`/quantite/variations_produit/${produit_edit.value}`)
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
function check(data) {
  variations_edit.innerHTML = "";
  if (data.length > 0) {
    for (let i = 0; i < data.length; i++) {
      variations_edit.innerHTML += `<div class="form-check" >
        <input type="checkbox" id="quantite_variations_${data[i].id}" name="quantite[variations][]" class="form-check-input" value="${data[i].id}" >
         <label class="form-check-label" for="quantite_variations_${data[i].id}" >${data[i].nom}(${data[i].attribut.nom})</label>      
      </div>`;
    }
  }
}
