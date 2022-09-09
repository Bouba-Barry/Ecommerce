let closebtn = document.getElementsByClassName("closebtn");

for (let close of closebtn) {
  close.addEventListener("click", function () {
    let close = document.getElementsByClassName("mfp-close");
    close[0].addEventListener("click", function () {
      location.reload();
    });
  });
}
