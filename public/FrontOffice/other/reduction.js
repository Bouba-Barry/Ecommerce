class reduction {
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
    // console.log("je me construit");
    this.container = document.querySelector(".js-filter");
    this.content = document.querySelector("#content");
    this.form = document.querySelector("#filter");
    // console.log(this.form);
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
    produits.style.display = "none";
    displayLoading();
    // console.log(this.form);
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
      // console.log(data.content);
      hideLoading();
      produits.style.display = "flex";
      this.content.innerHTML = data.content;
    } else {
      console.log("error");
    }
  }
}

let element = document.querySelector(".js-filter");
let produits = document.getElementById("produits");
const loader = document.querySelector("#loadingshop");
loader.style.display = "none";
new reduction(element);
function displayLoading() {
  loader.style.display = "block";
  loader.style.width = "20rem";
  loader.style.height = "20rem";
  loader.classList.add("display");
  // to stop loading after some time
  setTimeout(() => {
    loader.classList.remove("display");
  }, 5000);
}

// hiding loading
function hideLoading() {
  loader.style.display = "none";
  loader.classList.remove("display");
}
