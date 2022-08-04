var noti = document.getElementById("panier");
let addtocart = document.getElementsByClassName("addtocart");
let top_products_item = document.getElementsByClassName("top-products-item");

console.log(top_products_item);

for (let butt of top_products_item) {
  butt.addEventListener("mouseover", (e) => {
    console.log(butt);
    let element =
      butt.firstElementChild.children[1].firstElementChild.firstElementChild;
    console.log(element);

    let vals = [];
    vals.push(element.id.charAt(element.id.length - 1));
    fetch(
      `http://127.0.0.1:8000/panier_check/${vals}/${parseInt(
        user_id.innerHTML
      )}`
    )
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {
        console.log(data);
        check(data);
      })
      .catch((err) => {
        console.log(err);
      });

    function check(data) {
      if (data == 1) {
        element.setAttribute("data-original-title", "remove from panier");
        element.innerHTML = '<i class="flaticon-cancel"></i>';
      } else {
        element.setAttribute("data-original-title", "Add to cart");
        element.innerHTML = '<i class="flaticon-shopping-cart"></i>';
      }
    }
  });
}

for (let but of addtocart) {
  but.addEventListener("click", (e) => {
    // console.log(but.id);

    i = findindex_addtocart(but);
    console.log("le i : " + i);

    let vals = [];

    vals.push(addtocart[i].id.charAt(addtocart[i].id.length - 1));
    console.log("search id: " + addtocart[i].id);

    if (but.getAttribute("data-original-title") == "remove from panier") {
      console.log("awdi rk nadi");
      but.setAttribute("data-original-title", "Add to cart");
      but.innerHTML = '<i class="flaticon-shopping-cart"></i>';
      fetch(
        `http://127.0.0.1:8000/panier_delete/${vals}/${parseInt(
          user_id.innerHTML
        )}`
      )
        .then((response) => {
          if (response.ok) {
            return response;
          } else {
            console.log("mauvaise réponse!");
          }
        })
        .then((data) => {
          console.log(data);
        })
        .catch((err) => {
          console.log("error");
        });

      noti.innerHTML = parseInt(noti.innerHTML) - 1;
    } else {
      but.setAttribute("data-original-title", "remove from panier");
      but.innerHTML = '<i class="flaticon-cancel"></i>';

      fetch(
        `http://127.0.0.1:8000/panier/${vals}/1/${parseInt(user_id.innerHTML)}`
      )
        .then((response) => {
          if (response.ok) {
            return response.json();
          } else {
            console.log("mauvaise réponse!");
          }
        })
        .then((data) => {
          console.log(data);
        })
        .catch((err) => {
          console.log("error");
        });

      noti.innerHTML = parseInt(noti.innerHTML) + 1;
    }
  });
}

function findindex_addtocart(button) {
  for (let j = 0; j < addtocart.length; j++) {
    if (addtocart[j].id === button.id) {
      return j;
    } else continue;
  }
}
