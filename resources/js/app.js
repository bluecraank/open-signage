import bulmaCalendar from 'bulma-calendar/dist/js/bulma-calendar.min.js';

let secrets = document.querySelectorAll('.secret');

for (let i = 0; i < secrets.length; i++) {
    secrets[i].addEventListener("click", function () {
        secrets[i].classList.toggle("blur");
    });
}

// Initialize all input of type date
let options = {
    type: 'datetime',
    showFooter: true,
    startDate: new Date(),
};

var calendars = bulmaCalendar.attach('[type="date"]', options);

// Loop on each calendar initialized
for(var i = 0; i < calendars.length; i++) {
	// Add listener to select event
	calendars[i].on('select', date => {
		console.log(date);
	});
}

window.checkIfFileIsNotValid = function(file) {
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

fileInput.onchange = () => {
    if (fileInput.files.length > 0) {
        if(checkIfFileIsNotValid(fileInput.files[0])) {
            return;
        }

        const fileName = document.querySelector('#file-upload .file-name');
        fileName.textContent = fileInput.files[0].name;
    }
}

window.dropHandler = function(ev) {
    ev.preventDefault();

    if(checkIfFileIsNotValid(ev.dataTransfer.files[0])) {
        return;
    }

    if (ev.dataTransfer.files.length > 0) {
        fileInput.files = ev.dataTransfer.files;
        const fileName = document.querySelector('#file-upload .file-name');
        fileName.textContent = fileInput.files[0].name;
    }
}

window.dragOverHandler = function(ev) {
    console.log("File(s) in drop zone");

    // Prevent default behavior (Prevent file from being opened)
    ev.preventDefault();
}
