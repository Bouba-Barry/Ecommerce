let categorie = document.getElementById("categorie");
let sous_categorie = document.getElementById("produit_variable_sous_categorie");
let description_detaille = document.getElementById(
  "produit_variable_description_detaille"
);
sous_categorie.innerHTML = "";
description_detaille.style.height = "200px";
fetch(`http://127.0.0.1:8000/admin/categorie/all`)
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
  if (data.length > 0) {
    for (let i = 0; i < data.length; i++) {
      categorie.innerHTML += `<div class="form-check" >
        <input type="radio" id="categorie${data[i].id}" name="categorie[]" class="form-check-input" value="${data[i].id}" >
         <label class="form-check-label" for="categorie${data[i].id}" >${data[i].titre}</label>   
         
      </div>`;
    }
  }
}

categorie.addEventListener("change", function () {
  let valeur;
  for (let but of categorie.children) {
    if (but.firstElementChild.checked) valeur = but.firstElementChild.value;
  }
  fetch(`http://127.0.0.1:8000/admin/categorie/sous_categorie/${valeur}`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      console.log(data);
      setSous_categorie(data);
    })
    .catch((err) => {
      console.log(err);
    });
});

function setSous_categorie(data) {
  sous_categorie.innerHTML = "";
  if (data.length > 0) {
    for (let i = 0; i < data.length; i++) {
      sous_categorie.innerHTML += `<div class="form-check" >
        <input type="radio" id="produit_variable_sous_categorie_${data[i].id}" name="produit_variable[sous_categorie]" class="form-check-input" value="${data[i].id}" >
         <label class="form-check-label" for="produit_variable_sous_categorie_${data[i].id}" >${data[i].titre}</label>      
      </div>`;
    }
  }
}
