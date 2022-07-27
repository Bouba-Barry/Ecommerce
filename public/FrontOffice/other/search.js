let btn = document.querySelector("#submit");
let inputSearch = document.querySelector("#inputSearch");
// console.log(" lskdjfmslfjms");
console.log(btn);
btn.addEventListener("click", function () {
  let val = inputSearch.value;
  console.log(val);
  if (val != "") {
    $.ajax({
      url: `http://127.0.0.1:8000/search/${val}`,
      method: "GET",
      dataType: "json", // we expect a json response
      // data: { SearchVal: val },
      success: function (response) {
        var json = JSON.parse(response);
        // whatever you want to do here. Let's console.log the response
        // console.log(json[0].id); // should show your ['success'=> $request->id]
        console.log(json);
        giveSearch(json);
        //   addProduct(json);
      },
    });
  }
});
// }}
// });
let block = document.querySelector("#blockSearch");
function giveSearch(res) {
  if (res.length > 0) {
    block.innerHTML = "";
    let ch = "";
    for (let i = 0; i < res.length; i++) {
      ch += `
      <div class = "col-lg-3 col-md-6">
      <div class="top-products-item">
        <div class="products-image">
          <a href="shop-details.html"><img src="FrontOffice/assets/img/top-products/top-products-8.jpg"  alt="image"></a>
          <ul class="products-action">
            <li>
              <a href="cart.html" data-tooltip="tooltip" data-placement="top" title="Add to Cart">
                <i class="flaticon-shopping-cart"></i>
              </a>
            </li>
            <li>
              <a href="#" data-tooltip="tooltip" data-placement="top" title="Add to Wishlist">
                <i class="flaticon-heart"></i>
              </a>
            </li>
            <li>
              <a href="#" data-tooltip="tooltip" data-placement="top" title="Quick View" data-toggle="modal" data-target="#productsQuickView">
                <i class="flaticon-search"></i>
              </a>
            </li>
          </ul>

          <div class="sale">
            <span>Sale</span>
          </div>
        </div>

        <div class="products-content">
          <h3>
            <a href="shop-details.html">${res[i].designation}</a>
          </h3>
          <div class="price">
            <span class="new-price">DHS ${res[i].nouveau_prix}</span>
            <span class="old-price">DHS ${res[i].ancien_prix}
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
    block.innerHTML = ch;
  } else {
    block.innerHTML = "Aucun Resultat disponible Pour Ce Produit !!";
  }
}
// }
