class othe {
  /**
   *
   * @param {HtmlElement|null} element
   * @property {HTMLFormElement} form
   *  @property {HTMLElement} content
   *  @property {HTMLElement} container
   */

  constructor(element) {
    if (element === null) {
      return;
    }
    console.log("je me construit");
    this.container = document.querySelector(".js-filter");
    this.content = document.querySelector("#content");
    this.form = document.querySelector("#filter");
    console.log(this.form);
    this.bindEvents();
  }

  /**
   * ajout des elements en fonction du clique du user
   */
  bindEvents() {
    this.form.querySelectorAll("input").forEach((input) => {
      input.addEventListener("change", this.loadForm.bind(this));
    });
    // this.form.querySelectorAll("input[type=text]").forEach((texte) => {
    //   texte.addEventListener("keyup", this.loadForm.bind(this));
    // });
  }

  async loadForm() {
    console.log(this.form);
    const formdata = new FormData(this.form);
    const url = new URL(
      this.form.getAttribute("action") || window.location.href
    );
    const params = new URLSearchParams();
    formdata.forEach((value, key) => {
      params.append(key, value);
    });
    // debugger;
    return this.loadUrl(url.pathname + "?" + params.toString());
  }
  async loadUrl(url) {
    const response = await fetch(url, {
      method: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    });
    if (response.status >= 200 && response.status < 300) {
      const data = await response.json();
      console.log(data.content);
      let ch = `
      
      <div class="col-lg-4">
      <div class="top-products-item">
          <div class="products-image">
              <a href="{{ path('app_shop_details', { id: produit.id }) }}"><img style="width: 250px; height: 200px" src="{{ asset('uploads/produits/' ~ produit.getImageProduit()) }} " alt="image"/></a>

              <ul class="products-action">
                  <li>
                      <a id="addtocart{{ produit.id }}" class="addtocart" data-tooltip="tooltip" data-placement="top" title="Add to Cart">
                          <i class="flaticon-shopping-cart"></i>
                      </a>
                  </li>
                  <li>
                      <a id="addtowish{{ produit.id }}" class="addtowish" data-tooltip="tooltip" data-placement="top" title="Add to Wishlist">
                          <i class="flaticon-heart"></i>
                      </a>
                  </li>
                  <li>
                      <a data-tooltip="tooltip" id="searchbtn{{ produit.id }}" class="search" data-placement="top" title="Quick View" data-toggle="modal" data-target="#productsQuickView">
                          <i class="flaticon-search"></i>
                      </a>
                  </li>
              </ul>

              {% if produit.getReduction()|length != 0 %}
                  <div class="sale">
                      <span>
                          {% for produit_reduction in produit.getReduction() %}
                              -{{
          produit_reduction.getPourcentage()
        }}
                          {% endfor %}
                      </span>
                  </div>
              {% endif %}
          </div>

          <div class="products-content">
              <h3>
                  <a href="{{ path('app_shop_details', { id: produit.id }) }}">{{ produit.designation|slice(0,13) ~ '...' }}</a>
              </h3>
              {% if produit.getReduction()|length != 0 %}
                  <div class="price">
                      <span class="new-price">
                          {{ produit.getNouveauPrix() }}Dhs</span>
                      <span class="old-price">{{ produit.getAncienPrix() }}Dhs</span>
                  </div>
              {% else %}
                  <div class="price">
                      <span class="new-price">{{ produit.getNouveauPrix() }}Dhs</span>
                  </div>
              {% endif %}
              <ul class="rating">
                  <li>
                      <i class="bx bxs-star"></i>
                      <i class="bx bxs-star"></i>
                      <i class="bx bxs-star"></i>
                      <i class="bx bxs-star"></i>
                      <i class="bx bx-star"></i>
                  </li>
              </ul>
          </div>
      </div>
  </div>
      `;
      this.content.innerHTML = ch;
    } else {
      console.log("error");
    }
  }
}

let element = document.querySelector(".js-filter");

new othe(element);
