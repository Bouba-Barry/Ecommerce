let produits = document.getElementsByClassName("edit");
let row = document.getElementById("row");

for (let i = 0; i < produits.length; i++) {
  produits[i].addEventListener("click", function () {
    let matches = produits[i].id.match(/(\d+)/);
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
            let souscategories = "";
            for (sous of data1) {
              if (data.sous_categorie.id == sous.id) {
                souscategories += `  <div class="form-check"><input type="radio" id="produit_sous_categorie_${sous.id}" name="produit[sous_categorie]" required="required" class="form-check-input" value="${sous.id}" checked="checked" />
<label class="form-check-label required" for="produit_sous_categorie_${sous.id}">${sous.titre}</label></div>`;
              } else {
                souscategories += `  <div class="form-check"><input type="radio" id="produit_sous_categorie_${sous.id}" name="produit[sous_categorie]" required="required" class="form-check-input" value="${sous.id}"  />
            <label class="form-check-label required" for="produit_sous_categorie_${sous.id}">${sous.titre}</label></div>`;
              }
            }
            row.innerHTML = `
            <h3 class="btn btn-md btn-primary">Informations du Produit</h3>
    <form action="/produit/admin/${matches[0]}/edit" method="POST" class="col-12">
    <div class="box-body"> 
   
    <div class="form-group row">
      <label class="col-form-label col-md-2">Désignation</label>
      <div class="mb-3 col-10">
        <div class="form-group"><input type="text" id="produit_designation" name="produit[designation]" required="required" placeholder="designation" class="form-control form-control" value="${data.designation}" /></div>
        <span class="form-text text-muted"></span>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-form-label col-md-2">Description</label>
      <div class="mb-3 col-md-10">
        <div class="form-group"><textarea id="produit_description" name="produit[description]" required="required" placeholder="description" class="form-control form-control">${data.description}</textarea></div>
        <span class="form-text text-muted"></span>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-md-2"
        >Description detaille</label
      >
      <div class="mb-3 col-md-10">
        <div class="form-group"><textarea id="produit_description_detaille" name="produit[description_detaille]" required="required" placeholder="description detaille" class="form-control form-control">${data.description_detaille}</textarea></div>
        <span class="form-text text-muted"></span>
      </div>
    </div>

    
    <div class="form-group row">
      <label class="col-form-label col-md-2">Prix Produit</label>
      <div class="mb-3 col-md-10">
        <div class="form-group"><input type="text" id="produit_ancien_prix" name="produit[ancien_prix]" required="required" placeholder="Prix" class="form-control form-control" inputmode="decimal" value="${data.ancien_prix}" /></div>
        <span class="form-text text-muted"></span>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-form-label col-md-2">Quantite En Stock</label>
      <div class="mb-3 col-md-10">
        <div class="form-group"><input type="text" id="produit_qte_stock" name="produit[qte_stock]" required="required" placeholder="Quantité En Stock" class="form-control form-control" inputmode="decimal" value="${data.qte_stock}" /></div>
        <span class="form-text"></span>
      </div>
    </div>

    

    

    <div class="form-group row">
      <label class="col-form-label col-md-2">Sous Catégorie</label>
      <div class="mb-3 col-md-10">
        <fieldset class="form-group"><div id="produit_sous_categorie" placeholder="Sous Catégorie Produit" class="form-control">
      ${souscategories}     
</div></fieldset>
                        </div>
    </div>

    <div class="form-group row">
      <label class="col-form-label col-md-2">Image du Produit</label>
      <div class="mb-3 col-md-10">
        <div class="form-group"><div class="custom-file"><input type="file" id="produit_photo" name="produit[photo]" lang="en" placeholder="Image Produit" class="form-control custom-file-input" /><label for="produit_photo"  class="custom-file-label">Image Produit</label>
</div>
</div>
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
      })
      .catch((err) => {
        console.log(err);
      });
  });
}
