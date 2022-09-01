let radio = document.getElementsByClassName("radio");

for (let choisi of radio) {
  choisi.addEventListener("click", function () {
    let matches2 = choisi.id.match(/(\d+)/);
    fetch(`/setchoisiSlide/${matches2[0]}`)
      .then((response) => {
        if (response.ok) {
          return response.json();
        } else {
          console.log("mauvaise réponse!");
        }
      })
      .then((data) => {});
  });
}
