import './bootstrap';

let secrets = document.querySelectorAll('.secret');

for (let i = 0; i < secrets.length; i++) {
    secrets[i].addEventListener("click", function() {
        secrets[i].classList.toggle("blur");
    });
}
