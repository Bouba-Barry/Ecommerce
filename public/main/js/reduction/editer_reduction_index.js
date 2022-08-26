let edit = document.getElementsByClassName("edit");
let reduction_date_fin_date_month = document.getElementById(
  "reduction_date_fin_date_month"
);
let reduction_date_fin_date_day = document.getElementById(
  "reduction_date_fin_date_day"
);
let reduction_date_fin_date_year = document.getElementById(
  "reduction_date_fin_date_year"
);
let reduction_date_fin_time_hour = document.getElementById(
  "reduction_date_fin_time_hour"
);
let reduction_date_fin_time_minute = document.getElementById(
  "reduction_date_fin_time_minute"
);
for (let i = 0; i < edit.length; i++) {
  edit[i].addEventListener("click", function () {
    let matches = edit[i].id.match(/(\d+)/);
    fetch(`http://127.0.0.1:8000/getReduction/${matches[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        fetch(`/getProduits`)
          .then((response) => {
            if (response.ok) {
              return response.json();
            } else {
              console.log("mauvaise réponse!");
            }
          })
          .then((data1) => {
            // console.log(data.date_fin);
            // const [dateValues, timeValues] = data.date_fin.split("T");
            // console.log(dateValues);
            // const [year, month, day] = data.date_fin.split("-");
            const date = new Date(data.date_fin);
            console.log(date);
            let mois = "";
            let day = "";
            let year = "";
            let hour = "";
            let minute = "";
            for (let array of reduction_date_fin_date_month.children) {
              // console.log(array);
              if (array.value == date.getMonth() + 1) {
                // array.setAttribute("selected", "selected");
                mois += `<option value="${array.value}" selected="selected">${array.innerHTML}</option>`;
              } else
                mois += `<option value="${array.value}" >${array.innerHTML}</option>`;
            }
            for (let array of reduction_date_fin_date_day) {
              // console.log(date.getDate());
              if (array.value == date.getDate()) {
                // console.log(date.getDay());
                // array.setAttribute("selected", "selected");
                day += `<option value="${array.value}" selected="selected">${array.innerHTML}</option>`;
              } else
                day += `<option value="${array.value}" >${array.innerHTML}</option>`;
            }
            for (let array of reduction_date_fin_date_year) {
              if (array.value == date.getFullYear()) {
                // array.setAttribute("selected", "selected");
                year += `<option value="${array.value}" selected="selected">${array.innerHTML}</option>`;
              } else
                year += `<option value="${array.value}" >${array.innerHTML}</option>`;
            }
            for (let array of reduction_date_fin_time_hour) {
              if (array.value == date.getHours()) {
                // array.setAttribute("selected", "selected");
                hour += `<option value="${array.value}" selected="selected">${array.innerHTML}</option>`;
              } else
                hour += `<option value="${array.value}" >${array.innerHTML}</option>`;
            }
            for (let array of reduction_date_fin_time_minute) {
              if (array.value == date.getMinutes()) {
                console.log(date.getMinutes());
                // array.setAttribute("selected", "selected");
                minute += `<option value="${array.value}" selected="selected">${array.innerHTML}</option>`;
              } else
                minute += `<option value="${array.value}" >${array.innerHTML}</option>`;
            }

            // console.log(date.getMonth() + 1);
            // console.log(timeValues);
            let produits = "";
            for (let produit of data1) {
              if (produit.reduction.length != 0) {
                for (let reduction of produit.reduction) {
                  if (reduction.designation == data.designation) {
                    produits += `<div class="form-check">        <input type="checkbox" id="reduction_produits_${produit.id}" name="reduction[produits][]" class="form-check-input" checked="checked" value="${produit.id}" />
                  <label class="form-check-label" for="reduction_produits_${produit.id}">${produit.designation}</label></div>`;
                  } else {
                    produits += `<div class="form-check">        <input type="checkbox" id="reduction_produits_${produit.id}" name="reduction[produits][]" class="form-check-input"  value="${produit.id}" />
                <label class="form-check-label" for="reduction_produits_${produit.id}">${produit.designation}</label></div>`;
                  }
                }
              } else {
                produits += `<div class="form-check">        <input type="checkbox" id="reduction_produits_${produit.id}" name="reduction[produits][]" class="form-check-input"  value="${produit.id}" />
                <label class="form-check-label" for="reduction_produits_${produit.id}">${produit.designation}</label></div>`;
              }
            }

            row.innerHTML = `
            <h3 class="btn btn-md btn-primary">Informations du Reduction</h3>
      <form style="width:100%;"    method="post" enctype="multipart/form-data"  action="/admin/reduction/${matches[0]}/edit"
          class="tab-wizard wizard-circle"  >
         
          <div class="form-group row">
              <label class="col-form-label col-md-2">Désignation</label>
              <div
                  class=" mb-3 col-md-10">
                  
                  <div class="form-group"><input type="text" id="reduction_designation" name="reduction[designation]" required="required" placeholder="Ex: Reduction à Moitié" class="form-control form-control"    value="${data.designation}" /></div>

                  <span class="form-text text-muted"></span>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-form-label col-md-2">Pourcentage</label>
              <div
                  class=" mb-3 col-md-10">
                  
                  <div class="form-group"><input type="text" id="reduction_pourcentage" name="reduction[pourcentage]" required="required" placeholder="Ex: 50%" class="form-control form-control" value="${data.pourcentage}" /></div>

                  <span class="form-text text-muted"></span>
              </div>
          </div>


          <div class="form-group row">
              <label class="col-form-label col-md-2">Date de Fin</label>
              <div
                  class=" mb-3 col-md-10">
                  
                  <fieldset class="form-group"><div id="reduction_date_fin" class="form-control form-inline"><div class="sr-only"><label class="required" for="reduction_date_fin_date_year">Year</label><label class="required" for="reduction_date_fin_date_month">Month</label><label class="required" for="reduction_date_fin_date_day">Day</label><label class="required" for="reduction_date_fin_time_hour">Hour</label><label class="required" for="reduction_date_fin_time_minute">Minute</label></div>
                  <select id="reduction_date_fin_date_month" name="reduction[date_fin][date][month]" class="form-control">
                ${mois}
                  </select>
                  <select id="reduction_date_fin_date_day" name="reduction[date_fin][date][day]" class="form-control">
                  ${day}
                  </select>
                  <select id="reduction_date_fin_date_year" name="reduction[date_fin][date][year]" class="form-control">
                  ${year}
                  </select>
                  <select id="reduction_date_fin_time_hour" name="reduction[date_fin][time][hour]" class="form-control"><option value="0">00</option>
                  ${hour}
                  </select>:
                  <select id="reduction_date_fin_time_minute" name="reduction[date_fin][time][minute]" class="form-control">
                 ${minute}
                  </select></div></fieldset>

                  <span class="form-text text-muted"></span>
              </div>
          </div>


          <div class="form-group row">
              <label class="col-form-label col-md-2">produits Reliée</label>
                                                  <div
                  class=" mb-3 col-md-10">
<fieldset class="form-group"><div id="reduction_produits">       

${produits}

</div></fieldset>

                                                      </div>
                                              </div>

          <div class=" mb-3 col-12">
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
