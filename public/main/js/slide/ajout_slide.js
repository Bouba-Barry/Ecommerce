let row = document.getElementById("row");
let ajouterslide = document.getElementById("ajouterslide");

ajouterslide.addEventListener("click", function () {
  row.innerHTML = `

  <h3 class="btn btn-md btn-primary">Informations du Slide</h3>

    <form style="width:100%" action="/admin/slide/new" method="POST" enctype="multipart/form-data"  >

    <div class="form-group row">
    <label class="col-form-label col-md-2">Video</label>
    <div class="mb-3 col-md-10">
                        <div class="form-group"><div class="custom-file"><input type="file" id="slide_video" name="slide[video]" lang="en" placeholder="Votre video SVP" class="form-control custom-file-input" /><label for="slide_video"  class="custom-file-label">Votre video SVP</label>
</div>
</div>
      <span class="form-text text-muted"></span>
    </div>
  </div>

  <div class="form-group row">
    <label class="col-form-label col-md-2">Etat</label>
    <div class="mb-3 col-md-10">
                        <fieldset class="form-group"><div id="slide_etat"><div class="form-check">        <input type="radio" id="slide_etat_0" name="slide[etat]" required="required" class="form-check-input" value="image" />
<label class="form-check-label required" for="slide_etat_0">images</label></div><div class="form-check">        <input type="radio" id="slide_etat_1" name="slide[etat]" required="required" class="form-check-input" value="video" />
<label class="form-check-label required" for="slide_etat_1">video</label></div></div></fieldset>
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
