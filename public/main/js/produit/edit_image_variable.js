let images = document.getElementsByClassName("editimage");
let row = document.getElementById("row");
let id_produit = document.getElementById("produit_id");
let matches1 = id_produit.innerHTML.match(/(\d+)/);
for (let i = 0; i < images.length; i++) {
  images[i].addEventListener("click", function () {
    let matches = images[i].id.match(/(\d+)/);
    row.innerHTML = "";
    fetch(`http://127.0.0.1:8000/getImage/${matches[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        fetch(`http://127.0.0.1:8000/getVariations/${matches1[0]}`)
          .then((response) => {
            if (response.ok) {
              return response.json();
            } else {
              console.log("mauvaise réponse!");
            }
          })
          .then((data1) => {
            let variations = "";
            for (let variation of data1) {
              if (variation.id == data.variation.id) {
                variations += `<option value="${variation.id}" selected="selected">${variation.nom}</option>`;
              } else {
                variations += `<option value="${variation.id}" >${variation.nom}</option>`;
              }
            }
            row.innerHTML = `
        <h3 class="btn btn-md btn-primary">Variations</h3>
    <form enctype="multipart/form-data" action="/admin/image/variable/edit/${matches[0]}/${matches1[0]}" method="POST" class="col-12">
    <div class="box-body">
    <div class="form-group row">
    <label class="col-form-label col-md-2"
      >Images Pour le produit</label
    >
    <div class="mb-3 col-md-10">
      <div class="form-group"><div class="custom-file"><input type="file" id="image_variable_photo" name="image_variable[photo]" lang="en" placeholder="Image" class="form-control custom-file-input" /><label for="image_variable_photo"  class="custom-file-label">Image</label>
</div>
</div>
      <span class="form-text text-muted"></span>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-form-label col-md-2"
      >Variation Asscocié</label
    >
    <div class="mb-3 col-md-10">
                        <div class="form-group"><select id="image_variable_variation" name="image_variable[variation]" placeholder="Variation associé" class="form-control form-control">${variations}</select></div>
      <span class="form-text text-muted"></span>
    </div>
  </div>

  <div class="form-group row" style="display:none">
    <label class="col-form-label col-md-2">Produit Associé</label>
    <div class="mb-3 col-md-10">
                        <div class="form-group"><select id="image_variable_produit" name="image_variable[produit]" placeholder="Produit associé" class="form-control form-control"><option value="${data.produit.id}" selected="selected">${data.produit.designation}</option></select></div>
      <span class="form-text text-muted"></span>
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
