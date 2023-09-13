import './bootstrap';

let secrets = document.querySelectorAll('.secret');

for (let i = 0; i < secrets.length; i++) {
    secrets[i].addEventListener("click", function() {
        secrets[i].classList.toggle("blur");
    });
}

let slideout = document.querySelector('.slideout');
setTimeout(function() {
    slideout.classList.add('slideout_hide');
}, 4000);
