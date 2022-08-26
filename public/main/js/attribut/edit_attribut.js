let editinf = document.getElementById("editinfos");

let editvariations = document.getElementsByClassName("editvariations");

// let matches = categorie_id.innerHTML.match(/(\d+)/);

editinf.addEventListener("click", function () {
  fetch(`http://127.0.0.1:8000/getAttribut/${matches[0]}`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      row.innerHTML = `

        <h3 class="btn btn-md btn-primary">Informations d Attribut </h3>
    <form style="width:100%;"    method="post" enctype="multipart/form-data"  action="/admin/attribut/${matches[0]}/edit"
        class="tab-wizard wizard-circle"  >
       
        <div class="form-group row">
        <label class="col-form-label col-md-2">Désignation</label>
        <div class="mb-3 col-md-10">
                            <div class="form-group"><input type="text" id="attribut_nom" name="attribut[nom]" required="required" placeholder="Nom Attribut" class="form-control form-control" value="${data.nom}" /></div>
          <span class="form-text text-muted"></span>
        </div>
      </div>

      <div class="row">
        <div class="mb-3 col-4">
          
        </div>
        <div class="col-4"></div>
        <div class="mb-3 col-4">
          <button class="btn btn-info btn-rounded margin-top-10">
            Modifier
          </button>
        </div>
      </div>

      </form>
        `;
    });
});

for (let i = 0; i < editvariations.length; i++) {
  editvariations[i].addEventListener("click", function () {
    let matches1 = editvariations[i].id.match(/(\d+)/);
    fetch(`/getVariation/${matches1[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        let code = "";
        if (data.code != null) code = data.code;

        row.innerHTML = `
        <h3 class="btn btn-md btn-primary">Informations du sous categorie</h3>
      <form style="width:100%;"    method="post" enctype="multipart/form-data"  action="/admin/variation/${matches1[0]}/edit"
          class="tab-wizard wizard-circle"  >

          <div class="form-group row">
          <label class="col-form-label col-md-2">Nom</label>
          <div class="mb-3 col-md-10">
            
            <div class="form-group"><input type="text" id="variation_nom" name="variation[nom]" required="required" placeholder="Ex: BLANC" class="form-control form-control" value="${data.nom}" /></div>

                            </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-md-2">Code</label>
          <div class="mb-3 col-md-10">
            
            <div class="form-group"><input type="text" id="variation_code" name="variation[code]"  placeholder="Ex: #FFF " class="form-control form-control" value="${code}" /></div>

                            </div>
        </div>

        <div class="form-group row" style="display:none">
          <label class="col-form-label col-md-2">Attribut</label>
          <div class="mb-3 col-md-10">
            
            <div class="form-group"><select id="variation_attribut" name="variation[attribut]" class="form-control form-control"><option value="${matches[0]}" selected="selected"></option></select></div>

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
