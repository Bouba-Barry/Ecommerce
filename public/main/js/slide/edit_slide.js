let editinf = document.getElementById("editinfos");
let slideid = document.getElementById("slide_id");
let ajouterimages = document.getElementById("ajouterimages");
let matches = slideid.innerHTML.match(/(\d+)/);
let editimage = document.getElementsByClassName("editimage");

editinf.addEventListener("click", function () {
  fetch(`/getSlide/${matches[0]}`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      let etat = "";
      if (data.etat == "image") {
        etat += `<div class="form-check">
       <input
         type="radio"
         id="slide_etat_0"
         name="slide[etat]"
         required="required"
         class="form-check-input"
         value="image"
         checked="checked"
       />
       <label class="form-check-label required" for="slide_etat_0">
         image
       </label>
     </div>`;
        etat += `<div class="form-check">
     <input
       type="radio"
       id="slide_etat_1"
       name="slide[etat]"
       required="required"
       class="form-check-input"
       value="video"
     />
     <label class="form-check-label required" for="slide_etat_1">
       video
     </label>
   </div>`;
      } else {
        etat += `<div class="form-check">
        <input
          type="radio"
          id="slide_etat_1"
          name="slide[etat]"
          required="required"
          class="form-check-input"
          value="video"
          checked="checked"
        />
        <label class="form-check-label required" for="slide_etat_1">
          video
        </label>
      </div>`;
        etat += `<div class="form-check">
      <input
        type="radio"
        id="slide_etat_0"
        name="slide[etat]"
        required="required"
        class="form-check-input"
        value="image"
    
      />
      <label class="form-check-label required" for="slide_etat_0">
        image
      </label>
    </div>`;
      }

      row.innerHTML = `

        <h3 class="btn btn-md btn-primary">Informations du Slide </h3>
    <form style="width:100%;"    method="post" enctype="multipart/form-data"  action="/admin/slide/${matches[0]}/edit"
        class="tab-wizard wizard-circle"  >
    
        

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
                            <fieldset class="form-group"><div id="slide_etat">
        
                            ${etat}
                           
</div></fieldset>
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

ajouterimages.addEventListener("click", function () {
  fetch(`/getSlides/`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      let slides = "";
      for (let slide of data) {
        if (slide.id == matches[0]) {
          slides += `<option value="${slide.id}" selected="selected">${slide.id}</option>`;
        } else {
          slides += `<option value="${slide.id}" >${slide.id}</option>`;
        }
      }
      row.innerHTML = `
          <h3 class="btn btn-md btn-primary"> Ajout des Images </h3>
      <form style="width:100%;"    method="post" enctype="multipart/form-data"  action="/admin/lien/new/${matches[0]}"
          class="tab-wizard wizard-circle"  >
      
          <div class="form-group row">
          <label class="col-form-label col-md-2">Image</label>
          <div class="mb-3 col-md-10">
                              <div class="form-group"><div class="custom-file"><input type="file" id="lien_image" name="lien[image]" lang="en" placeholder="Votre Image SVP" class="form-control custom-file-input" /><label for="lien_image"  class="custom-file-label">Votre Image SVP</label>
</div>
</div>
            <span class="form-text text-muted"></span>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-md-2">URL</label>
          <div class="mb-3 col-md-10">
                              <div class="form-group"><input type="text" id="lien_url" name="lien[url]" required="required" placeholder="URL" class="form-control form-control" /></div>
            <span class="form-text text-muted"></span>
          </div>
        </div>

        <div class="form-group row" style="display:none">
          <label class="col-form-label col-md-2">Slide</label>
          <div class="mb-3 col-md-10">
                              <div class="form-group"><select id="lien_slide" name="lien[slide]" required="required" placeholder="Slide Concerner" class="form-control form-control">${slides}</select></div>
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
});

for (let image of editimage) {
  image.addEventListener("click", function () {
    let matches1 = image.id.match(/(\d+)/);
    fetch(`/getlien/${matches1[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        fetch(`/getSlides`)
          .then((response) => {
            if (response.ok) {
              return response.json();
            } else {
              console.log("mauvaise réponse!");
            }
          })
          .then((data1) => {
            let slides = "";
            for (let slide of data1) {
              if (slide.id == matches[0]) {
                slides += `<option value="${slide.id}" selected="selected">${slide.id}</option>`;
              } else {
                slides += `<option value="${slide.id}" >${slide.id}</option>`;
              }
            }
            row.innerHTML = `
          <h3 class="btn btn-md btn-primary"> Ajout des Images </h3>
      <form style="width:100%;"    method="post" enctype="multipart/form-data"  action="/admin/lien/${matches1[0]}/edit/${matches[0]}"
          class="tab-wizard wizard-circle"  >
      
          
          <div class="form-group row">
          <label class="col-form-label col-md-2">Image</label>
          <div class="mb-3 col-md-10">
                              <div class="form-group"><div class="custom-file"><input type="file" id="lien_image" name="lien[image]" lang="en" placeholder="Votre Image SVP" class="form-control custom-file-input" /><label for="lien_image"  class="custom-file-label">Votre Image SVP</label>
</div>
</div>
            <span class="form-text text-muted"></span>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-form-label col-md-2">URL</label>
          <div class="mb-3 col-md-10">
                              <div class="form-group"><input type="text" id="lien_url" name="lien[url]" required="required" placeholder="URL" class="form-control form-control" value="${data.url}" /></div>
            <span class="form-text text-muted"></span>
          </div>
        </div>

        <div class="form-group row" style="display:none">
          <label class="col-form-label col-md-2">Slide</label>
          <div class="mb-3 col-md-10">
                              <div class="form-group"><select id="lien_slide" name="lien[slide]" placeholder="Slide Concerner" class="form-control form-control">${slides}</select></div>
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
  });
}
