// let total = document.getElementById("total");
// console.log(total);

// function getCookie(cname) {
//   let name = cname + "=";
//   let decodedCookie = decodeURIComponent(document.cookie);
//   let ca = decodedCookie.split(";");
//   for (let i = 0; i < ca.length; i++) {
//     let c = ca[i];
//     while (c.charAt(0) == " ") {
//       c = c.substring(1);
//     }
//     if (c.indexOf(name) == 0) {
//       return c.substring(name.length, c.length);
//     }
//   }
//   return "";
// }
// total = getCookie("total");
// console.log(clientiD);
// console.log(total);
paypal
  .Buttons({
    // Sets up the transaction when a payment button is clicked
    createOrder: (data, actions) => {
      return actions.order.create({
        purchase_units: [
          {
            amount: {
              value: 10, // Can also reference a variable or function
            },
          },
        ],
      });
    },
    // Finalize the transaction after payer approval
    onApprove: (data, actions) => {
      return actions.order.capture().then(function (orderData) {
        // Successful capture! For dev/demo purposes:
        console.log(
          "Capture result",
          orderData,
          JSON.stringify(orderData, null, 2)
        );
        const transaction = orderData.purchase_units[0].payments.captures[0];
        console.log(orderData);
        // alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
        // When ready to go live, remove the alert and show a success message within this page. For example:
        // const element = document.getElementById('paypal-button-container');
        // element.innerHTML = '<h3>Thank you for your payment!</h3>';
        // Or go to another URL:  actions.redirect('thank_you.html');
        // console.log("Affichage de la devise");
        // console.log(orderData.purchase_units[0]['amount'].currency_code);

        let devise = orderData.purchase_units[0]["amount"].currency_code;
        let montant = orderData.purchase_units[0]["amount"].value;
        let nom =
          orderData.payer.name.given_name + " " + orderData.payer.name.surname;
        let email = orderData.payer.email_address;
        let adresse = orderData.payer.address;
        let payer_id = orderData.payer.payer_id;
        let status = orderData.status;
        let date_p = orderData.update_time;

        let date_pay = date_p.substring(0, 10);

        // createCookie("nomPayer", nom);
        // createCookie("montant", montant);
        // createCookie("devise", devise);
        // createCookie("email", email);
        // createCookie("payer_id", payer_id);
        // createCookie("status", status);
        // createCookie("date_pay", date_pay);

        console.log("nom=  " + nom);
        console.log("email = " + email);
        console.log("payer id = " + payer_id);
        console.log(" montant " + montant);
        console.log("status = " + status);
        console.log("date = " + date_pay);
        console.log("adresse = " + adresse);

        // function setCookie(nom){

        // }

        let vJson = {
          nom: nom,
          email: email,
          payer_id: payer_id,
          montant: montant,
          status: status,
          date_pay: date_pay,
        };

        let str_json = JSON.stringify(vJson);
        console.log(str_json);

        createCookie("val", str_json);

        // let doc = document.querySelector("#container");
        // doc = "";
        function createCookie(name, value) {
          let date = new Date(Date.now() + 900000); // 15 minutes de durée
          // date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
          let expires = "; expires=" + date.toUTCString();

          document.cookie = name + "=" + value + expires + "; path=/";
        }

        // if (status === accepted)
        // $.ajax({
        //   url: `http://127.0.0.1:8000/payment/facture/${str_json}`,
        //   method: "GET",
        //   dataType: "json", // we expect a json response
        //   // data: "val=" + $("#produits").value,
        //   success: function (response) {
        //     // var json = JSON.parse(response);
        //     console.log("it okayyyyyyyy");
        //     console.log(response);
        //     // facture(response);
        //   },
        // });
        // function facture(data) {
        //   let res = `
        //   <h2 class="bg-light text-primary text-center mt-3 fs-5 p-4">Merci Pour Votre Commande</h2>
        //   <table class="table table-hover table-bordered">
        //   <h4>RÉCAPUTILATIF DE VOTRE PAIEMENT</h4>
        //       <thead class="thead-dark">
        //           <tr>
        //               <th scope="col">Date de la Commande</th>
        //               <th scope="col">Adresse de Livraison</th>
        //               {# <th scope="col">Produit concerné</th> #}
        //               <th scope="col">Montant Total</th>
        //           </tr>
        //       </thead>
        //       <tbody>
        //           <tr>
        //               <th scope="row">{{ commandes.getDateCmd() | date("d/m/Y", "Europe/Paris") }}</th>
        //               <td>{{ commandes.getAdresseLivraison()  }}</td>
        //               <td>{{ total }}</td>
        //           </tr>
        //       </tbody>
        //   </table>

        //   `;

        //   doc.innerHTML = res;
        // }
        window.location.href = `/payment/facture/`;
      });
    },
    // style: {
    //   layout: "horizontal",
    //   color: "blue",
    //   shape: "rect",
    //   label: "paypal",
    // },
  })
  .render("#paypal-button-container");
