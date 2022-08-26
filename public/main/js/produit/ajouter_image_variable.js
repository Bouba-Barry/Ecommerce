let ajouterimage = document.getElementById("ajouterimage");
// let row = document.getElementById("row");
// let id_produit = document.getElementById("produit_id");

let matches = id_produit.innerHTML.match(/(\d+)/);
ajouterimage.addEventListener("click", function () {
  row.innerHTML = "";
  fetch(`http://127.0.0.1:8000/getVariations/${id_produit.innerHTML}`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      let variations = "";
      for (let variation of data) {
        variations += `<option value="${variation.id}">${variation.nom}</option>`;
      }

      row.innerHTML = `

          <form  enctype="multipart/form-data" action="/admin/image/new/variable/produit/${matches[0]}" method="POST"  class="col-12">

          <div class="box-body">

          <div class="form-group row">
          <label class="col-form-label col-md-2"
            >Images Pour le produit</label
          >
          <div class="mb-3 col-md-10">
            <div class="form-group"><div class="custom-file"><input type="file" id="image_variable_photo" name="image_variable[photo]" lang="en" placeholder="Image Produit" class="form-control custom-file-input" /><label for="image_variable_photo"  class="custom-file-label">Image Produit</label>
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
                                <div class="form-group"><select id="image_variable_produit" name="image_variable[produit]" placeholder="Produit associé" class="form-control form-control"><option value="${matches[0]}"></option></select></div>
              <span class="form-text text-muted"></span>
            </div>
          </div>

          <div class="mb-3 col-12">
            <button class="btn btn-info btn-rounded margin-top-10"  >
              Ajouter
            </button>
          </div>
        </div>

          </form>

          `;
      //   console.log(document.getElementById("form"));
      //   let form = document.getElementById("form");
      //   let buttonenvoie = document.getElementById("buttonenvoie");

      //   row.innerHTML = form.innerHTML;
      //   row.innerHTML = "fjfjfj";
      //   row = transform(row);
      //   console.log(row);
      //   row = new FormData(row);

      //   buttonenvoie.addEventListener("click", function () {
      //     fetch(form.action, {
      //       // if you need the response, you can assign fetch to a variable
      //       method: `POST`,
      //       body,
      //     });
      //   });
    })
    .catch((err) => {
      console.log(err);
    });
});

function transform(str) {
  var parser = new DOMParser();
  var doc = parser.parseFromString(str, "text/html");
  return doc;
}
