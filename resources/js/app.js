import "bootstrap/dist/js/bootstrap.min.js";

window.checkIfFileIsNotValid = function (file) {
    const fileType = file['type'];
    const validImageTypes = ['application/pdf', 'video/mp4'];
    if (!validImageTypes.includes(fileType)) {
        alert('Invalid File Type. Only PDF and MP4 files are allowed.');
        fileInput.value = '';
        return true;
    }
    return false;
}

const fileInput = document.querySelector('#file-upload input[type=file]');
if (fileInput) {
    fileInput.onchange = () => {
        if (fileInput.files.length > 0) {
            if (checkIfFileIsNotValid(fileInput.files[0])) {
                return;
            }

            if (inputDescription.value == "") {
                let fileNameWithoutExtension = fileInput.files[0].name.split('.').slice(0, -1).join('.');
                inputDescription.value = fileNameWithoutExtension;
            }
        }
    }
}

window.dropHandler = function (ev) {
    ev.preventDefault();

    if (checkIfFileIsNotValid(ev.dataTransfer.files[0])) {
        return;
    }

    if (ev.dataTransfer.files.length > 0) {
        fileInput.files = ev.dataTransfer.files;

        let inputDescription = document.getElementById('inputDescription')

        if (inputDescription.value == "") {
            let fileNameWithoutExtension = fileInput.files[0].name.split('.').slice(0, -1).join('.');
            inputDescription.value = fileNameWithoutExtension;
        }

    }
}

window.dragOverHandler = function (ev) {
    // Prevent default behavior (Prevent file from being opened)
    ev.preventDefault();
}

// Hide alert after 5 seconds
window.setTimeout(function () {
    let alert = document.querySelector('.alert');
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
    document.getElementById('DeviceSortBy').addEventListener('change', function () {
        localStorage.setItem('sort', this.value);
    });

    let sort = localStorage.getItem('sort');
    if (sort) {
        document.getElementById('DeviceSortBy').value = sort;
        document.getElementById('DeviceSortBy').dispatchEvent(new Event('change'));
    }
});
