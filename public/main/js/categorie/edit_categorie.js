let editinf = document.getElementById("editinfos");
let row = document.getElementById("row");
let categorie_id = document.getElementById("categorie_id");

let editsouscategories = document.getElementsByClassName("editsous_Categorie");

let matches = categorie_id.innerHTML.match(/(\d+)/);

editinf.addEventListener("click", function () {
  fetch(`http://127.0.0.1:8000/getCategorie/${matches[0]}`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      row.innerHTML = `

        <h3 class="btn btn-md btn-primary">Informations du Categorie</h3>
    <form style="width:100%;"    method="post" enctype="multipart/form-data"  action="/admin/categorie/${matches[0]}/edit/aside"
        class="tab-wizard wizard-circle"  >
       

        <div class="form-group row">
        <label class="col-form-label col-md-2">Titre Categorie</label>
        <div class="mb-3 col-md-10">
          
          <div class="form-group"><input type="text" id="categorie_titre" name="categorie[titre]" required="required" placeholder="Ex: Telephone" class="form-control form-control" value="${data.titre}" /></div>

          <span class="form-text text-muted"></span>
        </div>
      </div>
        
      <div class="col-4"></div>
      <div class="col-4">
        <button class="btn btn-info btn-rounded margin-top-10">
          Modifier
        </button>
      </div>

      </form>
        `;
    });
});

for (let i = 0; i < editsouscategories.length; i++) {
  editsouscategories[i].addEventListener("click", function () {
    let matches1 = editsouscategories[i].id.match(/(\d+)/);
    fetch(`/getSousCategorie/${matches1[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        row.innerHTML = `
        <h3 class="btn btn-md btn-primary">Informations du sous categorie</h3>
      <form style="width:100%;"    method="post" enctype="multipart/form-data"  action="/admin/sous/categorie/${data.id}/edit/aside/${matches[0]}"
          class="tab-wizard wizard-circle"  >

        <div class="form-group row">
        <label class="col-form-label col-md-2"
          >Titre Sous Categorie</label
        >
        <div class="mb-3 col-md-10">
          
          <div class="form-group"><input type="text" id="sous_categorie_titre" name="sous_categorie[titre]" required="required" placeholder="Ex: Iphone" class="form-control form-control" value="${data.titre}" /></div>

          <span class="form-text text-muted"></span>
        </div>
      </div>

      <div class="form-group row" style="display:none">
        <label class="col-form-label col-md-2">Categorie Reliée</label>
        <div class="mb-3 col-md-10">
          
          <div class="form-group"><select id="sous_categorie_categorie" name="sous_categorie[categorie]" class="form-control"><option value="${matches[0]}" selected="selected">${data.categorie.titre}</option></select></div>

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
          Modifier
        </button>
      </div>

</form>
        
        `;
      });
  });
}
