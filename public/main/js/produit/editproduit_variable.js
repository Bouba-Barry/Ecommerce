let editproduitvariable = document.getElementById("editproduitvariable");

editproduitvariable.addEventListener("click", function () {
  fetch(`http://127.0.0.1:8000/getProduit/${matches[0]}`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      fetch(`http://127.0.0.1:8000/getsouscategories`)
        .then((response) => {
          if (response.ok) {
            return response.json();
          } else {
            console.log("mauvaise réponse!");
          }
        })
        .then((data1) => {
          fetch(`/getAttributs`)
            .then((response) => {
              if (response.ok) {
                return response.json();
              } else {
                console.log("mauvaise réponse!");
              }
            })
            .then((data2) => {
              let attributs = "";
              let array = [];
              for (attribut_produit of data.attributs) {
                array.push(attribut_produit.id);
              }
              for (let attribut of data2) {
                if (array.includes(attribut.id)) {
                  attributs += `<div class="form-check">   <input type="checkbox" id="produit_variable_attributs_${attribut.id}" name="produit_variable[attributs][]" class="form-check-input" value="${attribut.id}" checked="checked" />
                  <label class="form-check-label" for="produit_variable_attributs_${attribut.id}">${attribut.nom}</label></div>`;
                } else {
                  attributs += `<div class="form-check">   <input type="checkbox" id="produit_variable_attributs_${attribut.id}" name="produit_variable[attributs][]" class="form-check-input" value="${attribut.id}"  />
                    <label class="form-check-label" for="produit_variable_attributs_${attribut.id}">${attribut.nom}</label></div>`;
                }
              }

              let souscategories = "";
              for (sous of data1) {
                if (data.sous_categorie.id == sous.id) {
                  souscategories += `  <div class="form-check"><input type="radio" id="produit_variable_sous_categorie_1" name="produit_variable[sous_categorie]" required="required" class="form-check-input" value="${sous.id}" checked="checked" />
           <label class="form-check-label required" for="produit_variable_sous_categorie_${sous.id}">${sous.titre}</label></div>`;
                } else {
                  souscategories += `  <div class="form-check"><input type="radio" id="produit_variable_sous_categorie_${sous.id}" name="produit_variable[sous_categorie]" required="required" class="form-check-input" value="${sous.id}"  />
          <label class="form-check-label required" for="produit_variable_sous_categorie_${sous.id}">${sous.titre}</label></div>`;
                }
              }
              row.innerHTML = `
          <h3 class="btn btn-md btn-primary">Informations Globale du Produit</h3>
  <form action="/produit/admin/variable/${matches[0]}/edit" method="POST" class="col-12">
  <div class="box-body"> 
 
  <div class="form-group row">
    <label class="col-form-label col-md-2">Désignation</label>
    <div class="mb-3 col-10">
      <div class="form-group"><input type="text" id="produit_variable_designation" name="produit_variable[designation]" required="required" placeholder="designation" class="form-control form-control" value="${data.designation}" /></div>
      <span class="form-text text-muted"></span>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-form-label col-md-2">Description</label>
    <div class="mb-3 col-md-10">
      <div class="form-group"><textarea id="produit_variable_description" name="produit_variable[description]" required="required" placeholder="description" class="form-control form-control">${data.description}</textarea></div>
      <span class="form-text text-muted"></span>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-form-label col-md-2"
      >Description detaille</label
    >
    <div class="mb-3 col-md-10">
      <div class="form-group"><textarea id="produit_variable_description_detaille" name="produit_variable[description_detaille]" required="required" placeholder="description detaille" class="form-control form-control">${data.description_detaille}</textarea></div>
      <span class="form-text text-muted"></span>
    </div>
  </div>

  


  <div class="form-group row" style="display:none">
  <label class="col-form-label col-md-2">User</label>
  <div class="mb-3 col-md-10">
    <div class="form-group"><select id="produit_variable_user" name="produit_variable[user]" placeholder="User qui a ajouter" class="form-control form-control"><option value="${matches2[0]}">Barry</option></select></div>
                                  </div>
</div>

  

  <div class="form-group row">
    <label class="col-form-label col-md-2">Sous Catégorie</label>
    <div class="mb-3 col-md-10">
      <fieldset class="form-group"><div id="produit_variable_sous_categorie" placeholder="Sous Catégorie Produit" class="form-control">
    ${souscategories}     
</div></fieldset>
                      </div>
  </div>

 

  <div class="form-group row">
  <label class="col-form-label col-md-2"
    >Attributs</label
  >
  <div class="mb-3 col-md-10">
    <fieldset class="form-group"><div id="produit_variable_attributs" class="form-control">

    ${attributs}

</div></fieldset>

                                  </div>
</div>



  <div class="mb-3 col-12">
    <button class="btn btn-info btn-rounded margin-top-10">
      Modifier
    </button>
  </div>
</div>
</form>
  
  
  `;
            });
        });
    })
    .catch((err) => {
      console.log(err);
    });
});
