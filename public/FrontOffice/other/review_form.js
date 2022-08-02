let submit = document.querySelector("#submit");
submit.addEventListener("click", function () {
  //   e.preventDefault();
  //   let errorNom = document.querySelector("#error_name").innerHTML;
  //   let errorTitle = document.querySelector("#error_title").innerHTML;
  //   let errorbody = document.querySelector("#error_body").innerHTML;

  //   errorNom = "";
  //   errorTitle = "";
  //   errorbody = "";
  console.log("you click");

  let nom = document.querySelector("#name").value;
  let reviewtitle = document.querySelector("#review-title").value;
  let reviewbody = document.querySelector("#review-body").value;
  let prod = document.querySelector("#prod").value;

  //   if (nom.length == 0 && reviewtitle.length == 0 && reviewbody.length == 0) {
  //     errorNom = "le champ est requis";
  //     errorTitle = "le champ est requis";
  //     errorbody = "le champ est requis";
  //   } else {
  // submit.addEventListener("click", function () {
  let retour = document.querySelector("#response");

  $.ajax({
    url: `http://127.0.0.1:8000/feedback/product`,
    method: "POST",
    dataType: "json", // we expect a json response
    data: {
      name: nom,
      title: reviewtitle,
      body: reviewbody,
      produit: prod,
    },
    success: function (response) {
      var json = JSON.parse(response);
      // console.log(json.msg);
      retour.innerHTML = json.msg;
    },
    error: function () {
      retour.innerHTML = "Vérifiez si vous avez tout saisie";
    },
  });
  // });
  //   }
});
//    url: `http://127.0.0.1:8000/feedback/product/${nom}/${reviewtitle}/${reviewbody}/${prod}`,

// $('#reviewForm').submit(function(e) {
//     e.preventDefault();
//     $.ajax({
//          type: 'POST',
//          url: `http://127.0.0.1:8000/feedback/new`,
//          data: $(this).serialize(),
//          beforeSend: //do something
//          complete: //do something
//          success: //do something for example if the request response is success play your animation...
//     });

//  })
