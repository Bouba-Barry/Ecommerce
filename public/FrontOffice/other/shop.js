document.addEventListener("DOMContentLoaded", function () {
  let lists = document.querySelector("#trieProd");
  $(function () {
    $("#trieProd").on("change", function () {
      // if (lists.value != "default") {
      $.ajax({
        url: `http://127.0.0.1:8000/shortProduct/${lists.value}`,
        method: "GET",
        dataType: "json", // we expect a json response
        // data: "val=" + $("#produits").value,
        success: function (response) {
          var json = JSON.parse(response);
          // console.log("okkkkkkkkkkkkkkkkkkkkkkkkk !!!!!!!!!! ");
          // whatever you want to do here. Let's console.log the response
          // console.log(json[0].id); // should show your ['success'=> $request->id]
          console.log(json);
          addProduct(json);
        },
      });
      // }}
    });
    // }
  });
  let row = document.querySelector("#row");
  let elmts = row.innerHTML;

  function addProduct(res) {
    if (res.length > 0) {
      row.innerHTML = "";
      let totalRes = document.querySelector("#count");
      totalRes.innerHTML = res.length;
      let ch = "";

      for (let i = 0; i < res.length; i++) {
        let red = "";
        if (res[i].reduction.length > 0) {
          red = "-" + res[i].reduction[0].pourcentage;
        }

        let new_prix = 0;
        if (res[i].nouveau_prix) {
          new_prix = res[i].nouveau_prix;
        } else {
          new_prix = res[i].ancien_prix;
        }
        ch += `
      <div class = "col-lg-3 col-md-6">
      <div class="top-products-item">
        <div class="products-image">
          <a href="http://127.0.0.1:8000/shop_details/${res[i].id}"><img src="uploads/produits/${res[i].image_produit}" style="width: 250px; height: 200px"  alt="image"></a>
          <ul class="products-action">
          <li>
             <a    id="addtocart{{ ${res[i].id} }}"
              class="addtocart" data-tooltip="tooltip" data-placement="top" title="Add to Cart">
    <i class="flaticon-shopping-cart"></i>
  </a>
        </li>
          <li>
            <a  id="addtowish{{ ${res[i].id} }}"
              class="addtowish" data-tooltip="tooltip" data-placement="top" title="Add to Wishlist">
  <i class="flaticon-heart"></i>
  </a>
        </li>
        <li>
          <a data-tooltip="tooltip" id="searchbtn{{ ${res[i].id} }}" class="search" data-placement="top" title="Quick View" data-toggle="modal" data-target="#productsQuickView">
  <i class="flaticon-search"></i>
</a>
        </li>
      </ul>

          <div class="sale">
            <span>${red}</span>
          </div>
        </div>

        <div class="products-content">
          <h3>
            <a href="http://127.0.0.1:8000/shop_details/${res[i].id}">${res[i].designation}</a>
          </h3>
          <div class="price">
          
            <span class="new-price">DHS ${new_prix}</span>
           
            </span>
          </div>
          <ul class="rating">
            <li>
              <i class='bx bxs-star'></i>
              <i class='bx bxs-star'></i>
              <i class='bx bxs-star'></i>
              <i class='bx bxs-star'></i>
              <i class='bx bx-star'></i>
            </li>
          </ul>
        </div>
      </div> 
      </div>
      `;
      }
      row.innerHTML = ch;
    } else {
      row.innerHTML = elmts;
    }
  }
});
