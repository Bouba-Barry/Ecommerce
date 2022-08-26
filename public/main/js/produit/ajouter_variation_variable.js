let ajouter = document.getElementById("ajouter");
// let row = document.getElementById("row");
// let id_produit = document.getElementById("produit_id");

// let matches = id_produit.innerHTML.match(/(\d+)/);
ajouter.addEventListener("click", function () {
  row.innerHTML = "";
  fetch(`http://127.0.0.1:8000/getAttributs/${id_produit.innerHTML}`)
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
            attributs += `  <input
                    type="radio"
                    id="quantite_variable_variations_${variation.id}"
                    name="quantite_variable[variations][${attribut.id}]"
                    class="form-check-input"
                    value="${variation.id}"
                  />
                  <label
                    class="form-check-label"
                    for="quantite_variable_variations_${variation.id}"
                    >${variation.nom}</label
                  >`;

            attributs += `</div>`;
          }
          attributs += ` </div>
                </div`;
        }
      }
      row.innerHTML = `
        <h3 class="btn btn-md btn-primary">Variations</h3>
    <form action="/quantite/admin/new/variable/produit/${matches[0]}" method="POST" class="col-12">
    <div class="box-body">
    <div class="form-group row" style="display:none">
    <label class="col-form-label col-md-2">Produit Concerne</label>
    <div class="mb-3 col-md-10">
      <div class="form-group"><select id="quantite_variable_produit" name="quantite_variable[produit]" placeholder="Produit concerner" readonly="readonly" class="form-control form-control"><option value="${matches[0]}" selected="selected"></option></select></div>
      <span class="form-text text-muted"></span>
    </div>
  </div>

    <div class="form-group row">
    <label class="col-form-label col-md-2">Qantite En Stock</label>
    <div class="mb-3 col-md-10">
      <div class="form-group"><input type="text" id="quantite_variable_qte_stock" name="quantite_variable[qte_stock]" required="required" placeholder="Quantite En Stock" inputmode="decimal" class="form-control" /></div>
      <span class="form-text text-muted"></span>
    </div>
  </div>
  
  <div class="form-group row">
    <label class="col-form-label col-md-2">Prix</label>
    <div class="mb-3 col-md-10">
      <div class="form-group"><input type="text" id="quantite_variable_prix" name="quantite_variable[prix]" required="required" placeholder="Prix" inputmode="decimal" class="form-control" /></div>
      <span class="form-text text-muted"></span>
    </div>
  </div>

                   ${attributs}
          
    <div class="row">
      <div class="mb-3 col-4">
        <button class="btn btn-info btn-rounded margin-top-10">
          Enregistrer
        </button>
      </div>
    </div>
      

      
     
    </div>
  </form>
    
    
    `;
    })
    .catch((err) => {
      console.log(err);
    });
});
