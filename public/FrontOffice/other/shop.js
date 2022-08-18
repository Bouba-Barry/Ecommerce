document.addEventListener("DOMContentLoaded", function () {
  // window.location.href = `http://127.0.0.1:8000/shortProduct/${lists.value}`;
  // let lists = document.querySelector("#trieProd");
  // lists.addEventListener("Change", function () {
  //   console.log(lists.value);
  //   fetch(`http://127.0.0.1:8000/shortProduct/${lists.value}`)
  //     .then((response) => {
  //       if (response.ok) {
  //         console.log("okkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk");
  //         return response.json;
  //       } else {
  //         console.log("Mauvaise response");
  //       }
  //     })
  //     .then((response) => {
  //       var json = JSON.parse(response);
  //       console.log(json);
  //       addProduct(response);
  //     })

  //     .catch((err) => {
  //       console.log(err);
  //     });
  // });

  // function addProduct(data) {
  //   if (data.length > 0) {
  //     let bloc = document.querySelector("#blockProd");

  //     bloc.innerHTML = "product: " + data[0].designation;
  //   }
  // }
  let lists = document.querySelector("#trieProd");
  $(function () {
    // when select changes
    // if (lists.value === "default") {

    // } else {
    $("#trieProd").on("change", function () {
      // if (lists.value != "default") {
      $.ajax({
        url: `http://127.0.0.1:8000/shortProduct/${lists.value}`,
        method: "GET",
        dataType: "json", // we expect a json response
        // data: "val=" + $("#produits").value,
        success: function (response) {
          var json = JSON.parse(response);
          console.log("okkkkkkkkkkkkkkkkkkkkkkkkk !!!!!!!!!! ");
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
  // let elt = document.createElement("div");
  // elt.className = "col-lg-3 col-md-6";
  // console.log(elt);
  // console.log(row);
  function addProduct(res) {
    //   let row = document.querySelector("#row");
    // let contents = document.querySelector("#content");

    // contents.innerHTML = "";
    // let elts = document.querySelector("#pred");
    // if (res.length > 0) {
    // contents.innerHTML = "";

    // console.log(elmts);
    if (res.length > 0) {
      row.innerHTML = "";
      let ch = "";
      let img = `
<img src={{ asset('FrontOffice/assets/img/top-products/top-products-8.jpg') }}  alt="image"> `;
      // console.log(src);
      for (let i = 0; i < res.length; i++) {
        // console.log(res[i].designation);
        // let newNode = document.createElement("div");
        // newNode.classList.add("col-lg-3 col-md-6");
        // let elt = document.createElement("div");
        // elt.className = "col-lg-3 col-md-6";
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
              <a href="" data-tooltip="tooltip" data-placement="top" title="Add to Cart">
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
        // elt.innerHTML = ch;

        // ch = "";
        // console.log(ch);
        // let node = document.createTextNode(ch);
        // newNode.appendChild(node);
        // elts.insertBefore(newNode, row.children[i]);

        // console.log(contents);
        // contents.appendChild(ch);
      }
      row.innerHTML = ch;
    } else {
      row.innerHTML = elmts;
    }
    // console.log(ch);

    // document.getElementById("#content").append(ch);
    // console.log(contents);
    // contents.innerHTML += ch;
  }
  // let node = document.createTextNode(ch);

  // console.log(document.getElementById(""));
  // }
});
