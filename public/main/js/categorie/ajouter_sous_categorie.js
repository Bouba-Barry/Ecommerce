// let row = document.getElementById("row");
let ajoutersouscategorie = document.getElementById("ajoutersouscategorie");

ajoutersouscategorie.addEventListener("click", function () {
  row.innerHTML = `

  <h3 class="btn btn-md btn-primary">Informations du Sous Categorie</h3>

    <form style="width:100%" action="/admin/sous/categorie/new/aside/${matches[0]}" method="POST" enctype="multipart/form-data"  >


    
    <div class="form-group row">
                <label class="col-form-label col-md-2"
                  >Titre Sous Categorie</label
                >
                <div class="mb-3 col-md-10">
                  
                  <div class="form-group"><input type="text" id="sous_categorie_titre" name="sous_categorie[titre]" required="required" placeholder="Ex: Iphone" class="form-control form-control" /></div>

                  <span class="form-text text-muted"></span>
                </div>
              </div>

              <div class="form-group row" style="display:none">
                <label class="col-form-label col-md-2">Categorie Reliée</label>
                <div class="mb-3 col-md-10">
                  
                  <div class="form-group"><select id="sous_categorie_categorie" name="sous_categorie[categorie]" class="form-control"><option value="${matches[0]}" selected="selected"></option><option value="2">Téléphone portable</option><option value="3">Imprimantes</option><option value="4">Accessoires PC</option><option value="5">Clavier PC</option><option value="28">jgjjg</option><option value="29">xhcv</option><option value="30">jxj</option></select></div>

                  <span class="form-text text-muted"></span>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-form-label col-md-2"
                  >Image de couverture</label
                >
                <div class="mb-3 col-md-10">
                  <div class="form-group"><div class="custom-file"><input type="file" id="sous_categorie_photo" name="sous_categorie[photo]" lang="en" placeholder="Image Sous Categorie" class="form-control custom-file-input" /><label for="sous_categorie_photo"  class="custom-file-label">Image Sous Categorie</label>
    </div>
</div>
                  <span class="form-text text-muted"></span>
                </div>
              </div>

              <div class="mb-3 col-12">
                <button class="btn btn-info btn-rounded margin-top-10">
                  Ajouter
                </button>
              </div>



    </form>


    
    `;
});
