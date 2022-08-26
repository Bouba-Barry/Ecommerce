// let row = document.getElementById("row");
let ajout = document.getElementById("ajout");
// let user_id = document.getElementById("user_id");
// let matches = user_id.innerHTML.match(/(\d+)/);

ajout.addEventListener("click", function () {
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
        villes += ` <option value="${ville.id}" >${ville.nom_ville}
               </option>`;
      }
      let roles = "";
      roles += `<div class="form-check">   <input type="checkbox" id="user_roles_0" name="user[roles][]" class="form-check-input" value="ROLE_SUPER_ADMIN" />
                <label class="form-check-label" for="user_roles_0">ROLE_SUPER_AdMIN </label></div><div class="form-check"> </div>
                `;
      roles += `<div class="form-check"> <input type="checkbox" id="user_roles_1" name="user[roles][]" class="form-check-input" value="ROLE_ADMIN"   />
                  <label class="form-check-label" for="user_roles_1">ROLE_ADMIN</label></div>`;
      roles += `<div class="form-check">  <input type="checkbox" id="user_roles_2" name="user[roles][]" class="form-check-input" value="ROLE_COMPTABLE" />
              <label class="form-check-label" for="user_roles_2">ROLE_COMPTABLE</label></div>`;
      row.innerHTML = `
              <h3 class="btn btn-md btn-primary">Informations d Utilisateur</h3>
              <form
              style="width:100%" 
              method="post" enctype="multipart/form-data"
              action="/admin/user/new"
              class="tab-wizard wizard-circle"
            >
              <!-- Step 1 -->
              
              <section>
                <br>
                <div class="row">
                  <div class="col-md-6">
                                      <div class="form-group">
                      <label for="firstName5">Email :</label>
                      <div class="form-group"><input type="email" id="user_email" name="user[email]" required="required" placeholder="Email" class="form-control form-control" /></div>
             <span class="form-text text-muted"></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="lastName1">Nom :</label>
                      <div class="form-group"><input type="text" id="user_nom" name="user[nom]" required="required" placeholder="Nom" class="form-control form-control" /></div>
                          <span class="form-text text-muted"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="emailAddress1">Prenom :</label>
                      <div class="form-group"><input type="text" id="user_prenom" name="user[prenom]" required="required" placeholder="Prenom" class="form-control form-control" /></div>
                          <span class="form-text text-muted"></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                                      <div class="form-group">
                      <label for="phoneNumber1">Telephone :</label>
                      <div class="form-group"><input type="text" id="user_telephone" name="user[telephone]" required="required" placeholder="Telephone" class="form-control form-control" /></div>
                          <span class="form-text text-muted"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                 
                  <div class="col-md-6">
                                      <div class="form-group">
                      <label for="addressline1">Address :</label>
                      <div class="form-group"><input type="text" id="user_adresse" name="user[adresse]" required="required" placeholder="Adresse" class="form-control form-control" /></div>
                          <span class="form-text text-muted"></span>
                    </div>
                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                      <label for="addressline1">Photo profile :</label>
                      <div class="form-group"><div class="custom-file"><input type="file" id="user_profile" name="user[profile]" lang="en" placeholder="Votre Profile SVP" class="form-control custom-file-input" /><label for="user_profile"  class="custom-file-label">Votre Profile SVP</label>
      </div>
  </div>
                    </div>
                  </div>
                </div>
                             <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="addressline2">Password :</label>
                      <div class="form-group"><input type="password" id="user_password_first" name="user[password][first]" required="required" placeholder="Mot de passe" class="form-control form-control" /></div>
                          <span class="form-text text-muted"></span>
                    </div>
                    <div class="form-group">
                      <label for="addressline2">confirmation :</label>
                      <div class="form-group"><input type="password" id="user_password_second" name="user[password][second]" required="required" placeholder="Confirmation" class="form-control form-control" /></div>
                                            </div>
                  </div>
  
                  <div class="col-md-6">
                 
               
                    <div class="form-group">
                      <div >
                        
                        <div class="form-group">
                          <label >Ville :</label>
                          <div class="form-group"><select id="user_ville" name="user[ville]" placeholder="Produit concerner" class="form-control form-control">${villes}</select></div>
                        </div>
                      </div>
                      
                    </div>
                  
                
                    <div class="form-group">
                    <label for="location3">Roles :</label>
       
                    <fieldset class="form-group"><div id="user_roles"> ${roles}</div></fieldset>
                  </div>
  
                
                </div>
             </div>
  
  
  
  
                <div class="row">
                  <div class="col-md-6"></div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <br><br>
                                          <button style="width:150px;" class="btn  btn-primary btn-rounded margin-top-10">
                        Enregistrer
                      </button>
                    </div>
                  </div>
                  
                </div>
              </section>
             
              
              </section>
            </form>
           
      
      
      `;
    })
    .catch((err) => {
      console.log(err);
    });
});
