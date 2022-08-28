let quantites = document.getElementsByClassName("editquantite");
// let row = document.getElementById("row");
// let id_produit = document.getElementById("produit_id");
// let matches1 = id_produit.innerHTML.match(/(\d+)/);
for (let i = 0; i < quantites.length; i++) {
  quantites[i].addEventListener("click", function () {
    let matches1 = quantites[i].id.match(/(\d+)/);
    row.innerHTML = "";
    fetch(`http://127.0.0.1:8000/getQuantite/${matches1[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        fetch(`http://127.0.0.1:8000/getAttributs/${matches[0]}`)
          .then((response) => {
            if (response.ok) {
              return response.json();
            } else {
              console.log("mauvaise réponse!");
            }
          })
          .then((data1) => {
            let attributs = "";
            for (let attribut of data1) {
              if (attribut.variations.length != 0) {
                attributs += ` <div class="form-group row">
                    <label class="col-form-label col-md-2">${attribut.nom}</label>
                    <div
                      class="mb-3 col-md-10 attributs"
                      id="${attribut.id}"
                    >`;
                for (variation of attribut.variations) {
                  attributs += `<div class="form-check">`;
                  if (data.variations.includes(variation.nom)) {
                    attributs += `<input
                        type="radio"
                        checked
                        id="edit_quantite_variable_variations_${variation.id}"
                        name="edit_quantite_variable[variations][${attribut.id}]"
                        class="form-check-input"
                        value="${variation.id}"
                      />
                      <label
                        class="form-check-label"
                        for="edit_quantite_variable_variations_${variation.id}"
                        >${variation.nom}</label
                      >`;
                  } else {
                    attributs += `  <input
                    type="radio"
                    id="edit_quantite_variable_variations_${variation.id}"
                    name="edit_quantite_variable[variations][${attribut.id}]"
                    class="form-check-input"
                    value="${variation.id}"
                  />
                  <label
                    class="form-check-label"
                    for="edit_quantite_variable_variations_${variation.id}"
                    >${variation.nom}</label
                  >`;
                  }
                  attributs += `</div>`;
                }
                attributs += ` </div>
                </div>`;
              }
            }
            row.innerHTML = `
        <h3 class="btn btn-md btn-primary">Variations</h3>
    <form action="/quantite/admin/edit/variable/${matches1[0]}/${matches[0]}" method="POST" class="col-12">
    <div class="box-body">
    <div class="form-group row" style="display:none">
      <label class="col-form-label col-md-2">Produit Concerne</label>
      <div class="mb-3 col-md-10">
        <div class="form-group"><select id="edit_quantite_variable_produit" name="edit_quantite_variable[produit]" placeholder="Produit concerner" class="form-control form-control"><option value="${data.produit.id}" selected="selected">${data.produit.designation}</option></select></div>
        <span class="form-text text-muted"></span>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-form-label col-md-2">Qantite En Stock</label>
      <div class="mb-3 col-md-10">
        <div class="form-group"><input type="text" id="edit_quantite_variable_qte_stock" name="edit_quantite_variable[qte_stock]" required="required" inputmode="decimal" class="form-control" value="${data.qte_stock}" /></div>
        <span class="form-text text-muted"></span>
      </div>
    </div>

    <div class="form-group row">
                    <label class="col-form-label col-md-2">Prix</label>
                    <div class="mb-3 col-md-10">
                      <div class="form-group"><input type="text" id="edit_quantite_variable_prix" name="edit_quantite_variable[prix]" required="required" inputmode="decimal" class="form-control" value="${data.prix}" /></div>
                      <span class="form-text text-muted"></span>
                    </div>
   </div>

                   ${attributs}
          
    <div class="row">
      <div class="mb-3 col-4">
        <button class="btn btn-info btn-rounded margin-top-10">
          Modifier
        </button>
      </div>
    </div>
      

      
     
    </div>
  </form>
    
    
    `;
          });
      })
      .catch((err) => {
        console.log(err);
      });
  });
}
