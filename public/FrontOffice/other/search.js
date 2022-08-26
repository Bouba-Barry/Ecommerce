class search {
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
    this.form.querySelectorAll("input[type=checkbox]").forEach((input) => {
      input.addEventListener("change", this.loadForm.bind(this));
    });
    this.form.querySelectorAll("input[type=text]").forEach((texte) => {
      texte.addEventListener("keyup", this.loadForm.bind(this));
    });
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
      this.content.innerHTML = data.content;
      // history.replaceState({}, "", this.url);
    } else {
      console.log("error");
    }
  }
}

let element = document.querySelector(".js-filter");

new search(element);
