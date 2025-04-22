window.addEventListener("load", function (event) {
    let today = new Date();
    let currentDate = new Date();

    let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    let weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];


    let dateLabelNode = document.getElementById("dateLabel");
    let weekdaysNode = document.getElementById("weekdays");
    let daysNode = document.getElementById("days");
    let prevBtn = document.getElementById("prev");
    let todayBtn = document.getElementById("today");
    let nextBtn = document.getElementById("next");

    weekdays.forEach(day => {
        let dayNode = document.createElement("div");
        let text = document.createElement("strong");
        text.innerHTML = day;
        dayNode.appendChild(text);
        weekdaysNode.appendChild(dayNode);
    });

    prevBtn.addEventListener("click", function (event) {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCal();
    });

    nextBtn.addEventListener("click", function (event) {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCal();
    });

    todayBtn.addEventListener("click", function (event) {
        currentDate = new Date();
        renderCal();
    });

    function renderCal() {
        dateLabelNode.innerHTML = months[currentDate.getMonth()] + " " + currentDate.getFullYear();

        daysNode.innerHTML = '';

        let firstDayMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        let lastDayMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
        let monthLength = lastDayMonth.getDate();

        let startDay = firstDayMonth.getDay();
        let daysPrevMonth = startDay;

        let endDay = lastDayMonth.getDay();
        let daysNextMonth = 6 - endDay;

        let prevMonthLastDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();

        // previous month's days
        for (let i = daysPrevMonth - 1; i >= 0; i--) {
            let dayNode = document.createElement("div");
            let dayTextNode = document.createElement("p"); 
            let eventSpace = document.createElement("div");

            eventSpace.classList.add("event");
            
            dayTextNode.innerHTML = prevMonthLastDay - i; 
            dayNode.classList.add("day", "otherMonth");

            dayNode.appendChild(eventSpace);
            dayNode.appendChild(dayTextNode); 
            daysNode.appendChild(dayNode);
        }

        // current month
        for (let i = 1; i <= monthLength; i++) {
            let dayNode = document.createElement("div");
            let dayTextNode = document.createElement("p"); 
            let eventSpace = document.createElement("div");

            eventSpace.classList.add("event");

            dayTextNode.innerHTML = i; 
            dayNode.classList.add("day");

            dayNode.appendChild(eventSpace);
            dayNode.appendChild(dayTextNode); 
            daysNode.appendChild(dayNode);
        }

        // next month's days
        for (let i = 1; i <= daysNextMonth; i++) {
            let dayNode = document.createElement("div");
            let dayTextNode = document.createElement("p"); 
            let eventSpace = document.createElement("div");

            eventSpace.classList.add("event");

            dayTextNode.innerHTML = i;
            dayNode.classList.add("day", "otherMonth");

            dayNode.appendChild(eventSpace);
            dayNode.appendChild(dayTextNode); 
            daysNode.appendChild(dayNode);
        }
    }

    //render on load!
    renderCal();

}); 