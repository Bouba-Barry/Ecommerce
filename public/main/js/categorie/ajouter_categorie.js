let row = document.getElementById("row");
let ajoutercategorie = document.getElementById("ajoutercategorie");

ajoutercategorie.addEventListener("click", function () {
  row.innerHTML = `

  <h3 class="btn btn-md btn-primary">Informations du Categorie</h3>

    <form style="width:100%" action="/admin/categorie/new/aside" method="POST" enctype="multipart/form-data"  >


    
    <div class="form-group row">
                <label class="col-form-label col-md-2">Titre Categorie</label>
                <div class="mb-3 col-md-10">
                  
                  <div class="form-group"><input type="text" id="categorie_titre" name="categorie[titre]" required="required" placeholder="Ex: Telephone" class="form-control form-control" /></div>

                  <span class="form-text text-muted"></span>
                </div>
              </div>


              <div class="row">
              <div class="mb-3 col-8">
              
            </div>
              
              <div class="mb-3 col-4">
                <button class="btn btn-info btn-rounded margin-top-10">
                  Ajouter
                </button>
              </div>


              </div>



    </form>


    
    `;
});
