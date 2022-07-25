let user_id = document.getElementById("user_id");
let length = 0;
let panier = document.getElementById("panier");

fetch(`http://127.0.0.1:8000/panier_length/${parseInt(user_id.innerHTML)}`)
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
  for (var i = 0; i < data.length; i++) {
    length = length + data[i].qte_produit;
  }
  panier.innerHTML = length;
}
