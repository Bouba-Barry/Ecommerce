let row = document.getElementById("row");
let ajout = document.getElementById("ajout");

ajout.addEventListener("click", function () {
  fetch(`/getProduits`)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      let produits = "";
      for (let produit of data) {
        produits += `<div class="form-check">        <input type="checkbox" id="reduction_produits_${produit.id}" name="reduction[produits][]" class="form-check-input" value="${produit.id}" />
        <label class="form-check-label" for="reduction_produits_${produit.id}">${produit.designation}</label></div>`;
      }

      row.innerHTML = `
              <h3 class="btn btn-md btn-primary">Informations du Reduction</h3>
        <form style="width:100%;"    method="post" enctype="multipart/form-data"  action="/admin/reduction/new"
            class="tab-wizard wizard-circle"  >
           
            <div class="form-group row">
                <label class="col-form-label col-md-2">Désignation</label>
                <div
                    class=" mb-3 col-md-10">
                    
                    <div class="form-group"><input type="text" id="reduction_designation" name="reduction[designation]" required="required" placeholder="Ex: Reduction à Moitié" class="form-control form-control" /></div>

                    <span class="form-text text-muted"></span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-md-2">Pourcentage</label>
                <div
                    class=" mb-3 col-md-10">
                    
                    <div class="form-group"><input type="text" id="reduction_pourcentage" name="reduction[pourcentage]" required="required" placeholder="Ex: 50%" class="form-control form-control" /></div>

                    <span class="form-text text-muted"></span>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-form-label col-md-2">Date de Fin</label>
                <div
                    class=" mb-3 col-md-10">
                    
                  <fieldset class="form-group"><div id="reduction_date_fin" class="form-control form-inline"><div class="sr-only"><label class="required" for="reduction_date_fin_date_year">Year</label><label class="required" for="reduction_date_fin_date_month">Month</label><label class="required" for="reduction_date_fin_date_day">Day</label><label class="required" for="reduction_date_fin_time_hour">Hour</label><label class="required" for="reduction_date_fin_time_minute">Minute</label></div><select id="reduction_date_fin_date_month" name="reduction[date_fin][date][month]" class="form-control"><option value="1">Jan</option><option value="2">Feb</option><option value="3">Mar</option><option value="4">Apr</option><option value="5">May</option><option value="6">Jun</option><option value="7">Jul</option><option value="8">Aug</option><option value="9">Sep</option><option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option></select><select id="reduction_date_fin_date_day" name="reduction[date_fin][date][day]" class="form-control"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select><select id="reduction_date_fin_date_year" name="reduction[date_fin][date][year]" class="form-control"><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option></select><select id="reduction_date_fin_time_hour" name="reduction[date_fin][time][hour]" class="form-control"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option></select>:<select id="reduction_date_fin_time_minute" name="reduction[date_fin][time][minute]" class="form-control"><option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option></select></div></fieldset>

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
                    Ajouter
                </button>
            </div>


        
        </form>

          
      
      
      `;
    })
    .catch((err) => {
      console.log(err);
    });
});
