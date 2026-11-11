import "bootstrap/dist/js/bootstrap.min.js";

const fileInput = document.querySelector('#file-upload input[type=file]');
const inputDescription = document.getElementById('inputDescription');

window.checkIfFileIsNotValid = function (file) {
    const validFileTypes = ['application/pdf', 'video/mp4'];
    if (!file || !validFileTypes.includes(file.type)) {
        alert('Invalid File Type. Only PDF and MP4 files are allowed.');
        if (fileInput) fileInput.value = '';
        return true;
    }
    return false;
}

// Use the file name as description if no description was entered
function fillDescriptionFromFile(file) {
    if (inputDescription && inputDescription.value == "") {
        inputDescription.value = file.name.split('.').slice(0, -1).join('.');
    }
}

if (fileInput) {
    fileInput.onchange = () => {
        if (fileInput.files.length > 0) {
            if (checkIfFileIsNotValid(fileInput.files[0])) {
                return;
            }

            fillDescriptionFromFile(fileInput.files[0]);
        }
    }
}

window.dropHandler = function (ev) {
    ev.preventDefault();

    if (!fileInput || ev.dataTransfer.files.length == 0) {
        return;
    }

    if (checkIfFileIsNotValid(ev.dataTransfer.files[0])) {
        return;
    }

    fileInput.files = ev.dataTransfer.files;
    fillDescriptionFromFile(fileInput.files[0]);
}

window.dragOverHandler = function (ev) {
    // Prevent default behavior (Prevent file from being opened)
    ev.preventDefault();
}

// Hide alert after 5 seconds
window.setTimeout(function () {
    let alert = document.querySelector('.fade-out');
    if (alert) {
        alert.animate([
            { opacity: 1 },
            { opacity: 0 }
        ], {
            duration: 1000
        });

        window.setTimeout(function () {
            alert.style.display = 'none';
        }, 1000);
    }
}, 5000);

document.addEventListener('DOMContentLoaded', function () {
    const darkMode = document.querySelector('.dark-mode');
    const lightMode = document.querySelector('.light-mode');
    const body = document.querySelector('body');
    const switchMode = document.querySelector('.themeButton');

    // Shows the icon of the theme the user can switch to
    function applyTheme(theme) {
        body.dataset.bsTheme = theme === 'dark' ? 'dark' : '';
        if (darkMode) darkMode.classList.toggle('d-none', theme === 'dark');
        if (lightMode) lightMode.classList.toggle('d-none', theme !== 'dark');
    }

    if (switchMode) {
        switchMode.addEventListener('click', () => {
            const theme = body.dataset.bsTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', theme);
            applyTheme(theme);
        });
    }

    applyTheme(localStorage.getItem('theme'));
});
