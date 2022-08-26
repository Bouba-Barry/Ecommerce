// let row = document.getElementById("row");
let ajoutervariation = document.getElementById("ajoutervariation");
let attribut_id = document.getElementById("attribut_id");
let matches = attribut_id.innerHTML.match(/(\d+)/);

ajoutervariation.addEventListener("click", function () {
  row.innerHTML = `

  <h3 class="btn btn-md btn-primary">Informations du Variation</h3>

    <form style="width:100%" action="/admin/variation/new/aside/${matches[0]}" method="POST" enctype="multipart/form-data"  >


    
    <div class="form-group row">
    <label class="col-form-label col-md-2">Nom</label>
    <div class="mb-3 col-md-10">
      
      <div class="form-group"><input type="text" id="variation_nom" name="variation[nom]" required="required" placeholder="Ex: BLANC" class="form-control form-control" /></div>

                      </div>
  </div>

  <div class="form-group row">
    <label class="col-form-label col-md-2">Code</label>
    <div class="mb-3 col-md-10">
      
      <div class="form-group"><input type="text" id="variation_code" name="variation[code]"  placeholder="Ex: #FFF " class="form-control form-control" /></div>

                      </div>
  </div>

  <div class="form-group row" style="display:none">
    <label class="col-form-label col-md-2">Attribut</label>
    <div class="mb-3 col-md-10">
      
      <div class="form-group"><select id="variation_attribut" name="variation[attribut]" class="form-control form-control"><option value="${matches[0]}"></option></select></div>

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
