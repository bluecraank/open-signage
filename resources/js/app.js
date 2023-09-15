import bulmaCalendar from 'bulma-calendar/dist/js/bulma-calendar.min.js';

let secrets = document.querySelectorAll('.secret');

for (let i = 0; i < secrets.length; i++) {
    secrets[i].addEventListener("click", function () {
        secrets[i].classList.toggle("blur");
    });
}

let slideout = document.querySelector('.slideout');
if (slideout) {
    setTimeout(function () {
        slideout.classList.add('slideout_hide');
    }, 4000);
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
