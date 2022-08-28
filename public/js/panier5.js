var noti = document.getElementById("panier");
let addtowish = document.getElementsByClassName("addtowish");

for (let butt of top_products_item) {
  butt.addEventListener("mouseover", (e) => {
    console.log(butt);
    let element =
      butt.firstElementChild.children[1].children[1].firstElementChild;

    let vals = [];
    // vals.push(element.id.charAt(element.id.length - 1));
    let matches = element.id.match(/(\d+)/);
    vals.push(matches[0]);
    fetch(`/wish_check/${vals}/${parseInt(user_id.innerHTML)}`)
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
        element.setAttribute("data-original-title", "remove from wishlist");
        element.innerHTML = '<i class="flaticon-cancel"></i>';
      } else {
        element.setAttribute("data-original-title", "Add to wishlist");
        element.innerHTML = '<i class="flaticon-heart"></i>';
      }
    }
  });
}

for (let but of addtowish) {
  but.addEventListener("click", (e) => {
    // console.log(but.id);

    i = findindex_addtowish(but);
    console.log("le i : " + i);

    let vals = [];

    // vals.push(addtowish[i].id.charAt(addtowish[i].id.length - 1));
    // console.log("search id: " + addtowish[i].id);
    let matches = addtowish[i].id.match(/(\d+)/);
    vals.push(matches[0]);

    if (but.getAttribute("data-original-title") == "remove from wishlist") {
      console.log("awdi rk nadi");
      but.setAttribute("data-original-title", "Add to wishlist");
      but.innerHTML = '<i class="flaticon-heart"></i>';
      fetch(`/wish_delete/${vals}/${parseInt(user_id.innerHTML)}`);
    } else {
      but.setAttribute("data-original-title", "remove from wishlist");
      but.innerHTML = '<i class="flaticon-cancel"></i>';
      fetch(`/wish/${vals}/${parseInt(user_id.innerHTML)}`);
    }
  });
}

function findindex_addtowish(button) {
  for (let j = 0; j < addtowish.length; j++) {
    if (addtowish[j].id === button.id) {
      return j;
    } else continue;
  }
}
