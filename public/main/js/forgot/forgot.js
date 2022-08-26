// let forgot = document.getElementById("forgot");
let submit = document.getElementById("submit");
let form_group = document.getElementById("form-group");
let email = document.getElementById("email");
let code = document.getElementById("code");
let text = document.getElementById("text");
console.log(email);

const data = new FormData();

submit.addEventListener("click", function () {
  data.append("email", email.value);
  form_group.style.display = "block";
  submit.innerHTML = "envoyer";
  submit.setAttribute("id", "envoyer");
  fetch(`/email`, { method: "POST", body: data })
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        console.log("mauvaise réponse!");
      }
    })
    .then((data) => {
      // console.log(data);
      let envoyer = document.getElementById("envoyer");
      envoyer.addEventListener("click", function () {
        if (code.value == data) {
          text.innerHTML = "le code est correct";
          text.style.color = "green";
          text.style.display = "block";
          window.location.href = "/resetpassword/" + email.value;
        } else {
          text.innerHTML = "le code n est pas correct";
          text.style.color = "red";
          text.style.display = "block";
        }
      });
    });
});
