document.addEventListener("DOMContentLoaded", function () {
  var myButton = document.querySelector("#click");

  myButton.addEventListener("click", function () {
    // window.location.href = `http://127.0.0.1:8000/shortProduct/${lists.value}`;
    console.log("you clicked  on Me");
    myButton.textContent = "You just clicked on Me !!!!!!!!!!!!!!!!!";
    myButton.style.color = "red";
    console.log("you clicked  on Me");
  });
  console.log(myButton);
  let lists = document.querySelector("#trieProd");

  // console.log(lists.value);
  // window.location.href = `http://127.0.0.1:8000/shortProduct/${lists.value}`;
  lists.addEventListener("change", function () {
    console.log(lists.value);
    // window.location.href = `http://127.0.0.1:8000/shortProduct/${lists.value}`;
    // if (lists.value == "") {
    //   opt1.textContent = "Triez Selon votre choix";
    // } else {
    //   opt1.textContent = "";
    // fetch(`http://127.0.0.1:8000/shortProduct/${lists.value}`)
    //   .then((response) => {
    //     if (response.ok) {
    //       console.log("okkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk");
    //       return response.json;
    //     } else {
    //       console.log("Mauvaise response");
    //     }
    //   })
    //   .then((json) => {
    //     console.log(json);
    //   })
    //   // addProduct(data);
    //   .catch((err) => {
    //     console.log(err);
    //   });
    // // }
  });
  let short = document.querySelector("#trieProd");
  short.addEventListener("change", () => {
    console.log(short.value);
  });
  // function getProds() {
  //   // window.location.href = "http://127.0.0.1:8000/shortProduct/" + lists.value;
  //   if (lists.value == "") {
  //     opt1.textContent = "Triez Selon votre choix";
  //   } else {
  //     opt1.textContent = "";
  //     fetch("http://127.0.0.1:8000/shortProduct/${lists.value}") // va retourner une response
  //       .then((response) => {
  //         if (response.ok) {
  //           return response.json;
  //         } else {
  //           console.log("Mauvaise response");
  //         }
  //       })
  //       .then((data) => {
  //         // json.forEach((element) => {
  //         //   console.log(element);
  //         // });
  //         addProduct(data);
  //       })
  //       .catch((err) => {
  //         console.log(err);
  //       });
  //   }
  // }

  // function addProduct(data) {
  //   if (data.length > 0) {
  //     let bloc = document.querySelector("#blockProd");

  //     bloc.innerHTML = "product: " + data[0].designation;
  //   }
  // }
  // $(function () {
  //   // when select changes
  //   $("#produits").on("change", function () {
  //     // create data from form input(s)
  //     // let formData = $("#myForm").serialize();

  //     // send data to your endpoint
  //     $.ajax({
  //       url: "http://127.0.0.1:8000/shortProduit",
  //       method: "post",
  //       dataType: "html", // we expect a json response
  //       data: "val=" + $("#produits").value,
  //       success: function (response) {
  //         // whatever you want to do here. Let's console.log the response
  //         console.log(response); // should show your ['success'=> $request->id]
  //       },
  //     });
  //   });
  // });
});
