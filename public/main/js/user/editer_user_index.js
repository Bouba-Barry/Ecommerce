let row = document.getElementById("row");
let users = document.getElementsByClassName("edit");

for (let i = 0; i < users.length; i++) {
  users[i].addEventListener("click", function () {
    let matches = users[i].id.match(/(\d+)/);
    console.log(matches[0]);
    fetch(`http://127.0.0.1:8000/getUser/${matches[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        fetch(`/getVilles`)
          .then((response) => {
            if (response.ok) {
              return response.json();
            } else {
              console.log("mauvaise réponse!");
            }
          })
          .then((data1) => {
            let villes = "";
            for (let ville of data1) {
              if (ville.id == data.ville.id) {
                villes += ` <option value="${ville.id}" selected="selected">${ville.nom_ville}
               </option>`;
              } else {
                villes += ` <option value="${ville.id}" >${ville.nom_ville}
               </option>`;
              }
            }
            let roles = "";
            if (data.roles.includes("ROLE_SUPER_ADMIN")) {
              roles += ` <div class="form-check">  <input type="checkbox" id="edit_user_roles_0" name="edit_user[roles][]" class="form-check-input" value="ROLE_SUPER_ADMIN" checked="checked" />
                <label class="form-check-label" for="edit_user_roles_0">ROLE_SUPER_AdMIN </label></div><div class="form-check"> </div>
                `;
            } else {
              roles += `<div class="form-check">   <input type="checkbox" id="edit_user_roles_0" name="edit_user[roles][]" class="form-check-input" value="ROLE_SUPER_ADMIN" />
                <label class="form-check-label" for="edit_user_roles_0">ROLE_SUPER_AdMIN </label></div><div class="form-check"> </div>
                `;
            }
            if (data.roles.includes("ROLE_ADMIN")) {
              roles += ` <div class="form-check">  <input type="checkbox" id="edit_user_roles_1" name="edit_user[roles][]" class="form-check-input" value="ROLE_ADMIN"  checked="checked" />
                  <label class="form-check-label" for="edit_user_roles_1">ROLE_ADMIN</label></div>`;
            } else {
              roles += `<div class="form-check"> <input type="checkbox" id="edit_user_roles_1" name="edit_user[roles][]" class="form-check-input" value="ROLE_ADMIN"   />
                  <label class="form-check-label" for="edit_user_roles_1">ROLE_ADMIN</label></div>`;
            }
            if (data.roles.includes("ROLE_COMPTABLE")) {
              roles += ` <div class="form-check"> <input type="checkbox" id="edit_user_roles_2" name="edit_user[roles][]" class="form-check-input" value="ROLE_COMPTABLE" checked="checked" />
                <label class="form-check-label" for="edit_user_roles_2">ROLE_COMPTABLE</label></div>`;
            } else {
              roles += `<div class="form-check">  <input type="checkbox" id="edit_user_roles_2" name="edit_user[roles][]" class="form-check-input" value="ROLE_COMPTABLE" />
              <label class="form-check-label" for="edit_user_roles_2">ROLE_COMPTABLE</label></div>`;
            }

            row.innerHTML = `
              <h3 class="btn btn-md btn-primary">Informations d Utilisateur</h3>
              <form style="width:100%" action="/admin/user/${matches[0]}/edit" name="edit_user" method="post" enctype="multipart/form-data">
              <br>
              <div class="row">
                <div class="col-md-6">
               
                  <div class="form-group">
                    <label for="firstName5">Email :</label>
                    <div class="form-group"><input type="email" id="edit_user_email" name="edit_user[email]" required="required" placeholder="Email" class="form-control form-control" value="${data.email}" /></div>
                    <span class="form-text text-muted"></span>
                  </div>
                </div>
                <div class="col-md-6">
                  
                  <div class="form-group">
                    <label for="lastName1">Nom :</label>
                    
                    <div class="form-group"><input type="text" id="edit_user_nom" name="edit_user[nom]" required="required" placeholder="Nom" class="form-control form-control" value="${data.nom}" /></div>
                  <span class="form-text text-muted"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="emailAddress1">Prenom :</label>
                    <div class="form-group"><input type="text" id="edit_user_prenom" name="edit_user[prenom]" required="required" placeholder="Prenom" class="form-control form-control" value="${data.prenom}" /></div>
                  <span class="form-text text-muted"></span>              
                  </div>
                </div>
                <div class="col-md-6">
                                        <div class="form-group">
                    <label for="phoneNumber1">Telephone :</label>
                    <div class="form-group"><input type="text" id="edit_user_telephone" name="edit_user[telephone]" required="required" placeholder="Telephone" class="form-control form-control" value="${data.telephone}" /></div>
                          <span class="form-text text-muted"></span>
                  </div>
                </div>
              </div>
              <div class="row">
               
                <div class="col-md-6">
                                        <div class="form-group">
                    <label for="addressline1">Address :</label>
                    <div class="form-group"><input type="text" id="edit_user_adresse" name="edit_user[adresse]" required="required" placeholder="Adresse" class="form-control form-control" value="${data.adresse}" /></div>
                        <span class="form-text text-muted"></span>
                  </div>
                </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                    <label for="addressline1">Modifier la photo</label>
                    <div class="form-group"><div class="custom-file"><input type="file" id="edit_user_profile" name="edit_user[profile]" lang="en" placeholder="Votre Profile SVP" class="form-control custom-file-input" /><label for="edit_user_profile"  class="custom-file-label">Votre Profile SVP</label>
</div>
</div>
                    <span class="form-text text-muted"></span>
                  </div>
                </div>
             </div>
              <div class="row">
                 <div class="col-md-6">
                                          <div class="form-group">
                    <label for="location3">Roles :</label>
       
                    <fieldset class="form-group"><div id="edit_user_roles"> ${roles}</div></fieldset>
                  </div>
                </div> 
                <div class="col-md-6">
                <div class="form-group">
                  <div >
                    
                    <div class="form-group">
                      <label >Ville :</label>
                      <div class="form-group"><select id="edit_user_ville" name="edit_user[ville]" placeholder="Produit concerner" class="form-control form-control">${villes}</select></div>
                    </div>
                  </div>
                  
                </div>
              </div>
              

              </div>
              <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                  <div class="form-group">
                    <br><br>
                                            <button class="btn btn-primary btn-rounded margin-top-10">
                      Modifier
                  </div>
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
