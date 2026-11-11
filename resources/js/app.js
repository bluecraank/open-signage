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

// Hide all success and error messages after 5 seconds
window.setTimeout(function () {
    document.querySelectorAll('.fade-out').forEach(function (alert) {
        alert.animate([{ opacity: 1 }, { opacity: 0 }], { duration: 600 });
        window.setTimeout(function () {
            alert.style.display = 'none';
        }, 600);
    });
}, 5000);

// Highlight the drop zone while a file is dragged over it
document.addEventListener('DOMContentLoaded', function () {
    const dropZone = document.getElementById('drop_zone');
    if (!dropZone) return;

    ['dragenter', 'dragover'].forEach((event) => dropZone.addEventListener(event, () => dropZone.classList.add('dragover')));
    ['dragleave', 'drop'].forEach((event) => dropZone.addEventListener(event, () => dropZone.classList.remove('dragover')));
});

document.addEventListener('DOMContentLoaded', function () {
    const darkMode = document.querySelector('.dark-mode');
    const lightMode = document.querySelector('.light-mode');
    const root = document.documentElement;
    const switchMode = document.querySelector('.themeButton');

    // Shows the icon of the theme the user can switch to
    function applyTheme(theme) {
        root.dataset.bsTheme = theme;
        if (darkMode) darkMode.classList.toggle('d-none', theme === 'dark');
        if (lightMode) lightMode.classList.toggle('d-none', theme !== 'dark');
    }

    if (switchMode) {
        switchMode.addEventListener('click', () => {
            const theme = root.dataset.bsTheme === 'dark' ? 'light' : 'dark';
            try { localStorage.setItem('theme', theme); } catch (e) {}
            applyTheme(theme);
        });
    }

    applyTheme(root.dataset.bsTheme === 'dark' ? 'dark' : 'light');
});
