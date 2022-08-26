let row = document.getElementById("row");
let ajouterattribut = document.getElementById("ajouterattribut");

ajouterattribut.addEventListener("click", function () {
  row.innerHTML = `

  <h3 class="btn btn-md btn-primary">Informations d Attribut</h3>

    <form style="width:100%" action="/admin/attribut/new/aside" method="POST" enctype="multipart/form-data"  >


    
    <div class="form-group row">
                <label class="col-form-label col-md-2">Désignation</label>
                <div class="mb-3 col-md-10">
                                    <div class="form-group"><input type="text" id="attribut_nom" name="attribut[nom]" required="required" placeholder="Nom Attribut" class="form-control form-control" /></div>
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
