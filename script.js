const daysContainer = document.querySelector(".days"),
  nextBtn = document.querySelector(".next-btn"),
  prevBtn = document.querySelector(".prev-btn"),
  month = document.querySelector(".month"),
  todayBtn = document.querySelector(".today-btn");

const months = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

const days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

// get current date
const date = new Date();

// get current month
let currentMonth = date.getMonth();

// get current year
let currentYear = date.getFullYear();

// function to render days
// async function renderCalendar() {
//     // get prev month current month and next month days
//     date.setDate(1);
//     const firstDay = new Date(currentYear, currentMonth, 1);
//     const lastDay = new Date(currentYear, currentMonth + 1, 0);
//     const lastDayIndex = lastDay.getDay();
//     const lastDayDate = lastDay.getDate();
//     const prevLastDay = new Date(currentYear, currentMonth, 0);
//     const prevLastDayDate = prevLastDay.getDate();
//     const nextDays = 7 - lastDayIndex - 1;

//     // update current year and month in header
//     month.innerHTML = `${months[currentMonth]} ${currentYear}`;

//     // update days html
//     let days = "";

//     // prev days html
//     for (let x = firstDay.getDay(); x > 0; x--) {
//         days += `<div class="day prev">${prevLastDayDate - x + 1}</div>`;
//     }

//     // current month days
//     for (let i = 1; i <= lastDayDate; i++) {
//         let linkDay = i < 10 ? '0' + i : i;
//         let link = `${currentYear}-${currentMonth + 1}-${linkDay}`;
//         // check if its today then add today class
//         const response = await fetch(`partials/getRecords.php?date=${link}`);
//         const data = await response.json();
//         let totalRecords = data.totalRecords;
//         if (i === new Date().getDate() && currentMonth === new Date().getMonth() && currentYear === new Date().getFullYear()) {
//             if(totalRecords != null) {
//                 days += `<a class="day today" href="records.php?date=${link}"><div class="day-container">${i} <span class="badge">${totalRecords}<span></div></a>`;
//             } else {
//                 days += `<a class="day today" href="records.php?date=${link}"><div class="day-container">${i} </div></a>`;
//             }
//             // if date month year matches add today
//         } else {
//             //else dont add today
//             if(totalRecords != null) {
//                 days += `<a class="day" href="records.php?date=${link}"><div class="day-container">${i} <span class="badge">${totalRecords}</span></div></a>`;
//             } else {
//                 days += `<a class="day" href="records.php?date=${link}"><div class="day-container">${i} </div></a>`;
//             }
//         }
//     }

//     // next MOnth days
//     for (let j = 1; j <= nextDays; j++) {
//         days += `<div class="day next">${j}</div>`;
//     }

//     // run this function with every calendar render
//     hideTodayBtn();
//     daysContainer.innerHTML = days;
// }

async function renderCalendar() {
  // get prev month current month and next month days
  date.setDate(1);
  const firstDay = new Date(currentYear, currentMonth, 1);
  const lastDay = new Date(currentYear, currentMonth + 1, 0);
  const lastDayIndex = lastDay.getDay();
  const lastDayDate = lastDay.getDate();
  const prevLastDay = new Date(currentYear, currentMonth, 0);
  const prevLastDayDate = prevLastDay.getDate();
  const nextDays = 7 - lastDayIndex - 1;

  // update current year and month in header
  month.innerHTML = `${months[currentMonth]} ${currentYear}`;

  // update days html
  let days = "";

  // Prepare an array to store all fetch promises
  const fetchPromises = [];

  // prev days html
  for (let x = firstDay.getDay(); x > 0; x--) {
    days += `<div class="day prev">${prevLastDayDate - x + 1}</div>`;
  }

  // current month days
  for (let i = 1; i <= lastDayDate; i++) {
    let linkDay = i < 10 ? "0" + i : i;
    let link = `${currentYear}-${currentMonth + 1}-${linkDay}`;
    // Add fetch promise to the array
    fetchPromises.push(fetch(`partials/getRecords.php?date=${link}`));
  }

  // Use Promise.all to wait for all fetch requests to complete
  const responses = await Promise.all(fetchPromises);

  // Use another array to store all JSON data
  const dataArr = await Promise.all(
    responses.map((response) => response.json())
  );

  // Update days html
  for (let i = 1; i <= lastDayDate; i++) {
    let totalRecords = dataArr[i - 1].totalRecords;

    // check if it's today then add today class
    if (
      i === new Date().getDate() &&
      currentMonth === new Date().getMonth() &&
      currentYear === new Date().getFullYear()
    ) {
      if (totalRecords != null) {
        days += `<a class="day today" href="records.php?date=${link}"><div class="day-container">${i} <span class="badge">${totalRecords}</span></div></a>`;
      } else {
        days += `<a class="day today" href="records.php?date=${link}"><div class="day-container">${i}</div></a>`;
      }
      // if date month year matches add today
    } else {
      // else don't add today
      if (totalRecords != null) {
        days += `<a class="day" href="records.php?date=${link}"><div class="day-container">${i} <span class="badge">${totalRecords}</span></div></a>`;
      } else {
        days += `<a class="day" href="records.php?date=${link}"><div class="day-container">${i}</div></a>`;
      }
    }
  }

  // next Month days
  for (let j = 1; j <= nextDays; j++) {
    days += `<div class="day next">${j}</div>`;
  }

  // run this function with every calendar render
  hideTodayBtn();
  daysContainer.innerHTML = days;
}

renderCalendar();

nextBtn.addEventListener("click", () => {
  // increase current month by one
  currentMonth++;
  if (currentMonth > 11) {
    // if month gets greater that 11 make it 0 and increase year by one
    currentMonth = 0;
    currentYear++;
  }
  // rerender calendar
  renderCalendar();
});

// prev monyh btn
prevBtn.addEventListener("click", () => {
  // increase by one
  currentMonth--;
  // check if let than 0 then make it 11 and deacrease year
  if (currentMonth < 0) {
    currentMonth = 11;
    currentYear--;
  }
  renderCalendar();
});

// go to today
todayBtn.addEventListener("click", () => {
  // set month and year to current
  currentMonth = date.getMonth();
  currentYear = date.getFullYear();
  // rerender calendar
  renderCalendar();
});

// lets hide today btn if its already current month and vice versa

function hideTodayBtn() {
  if (
    currentMonth === new Date().getMonth() &&
    currentYear === new Date().getFullYear()
  ) {
    todayBtn.style.display = "none";
  } else {
    todayBtn.style.display = "flex";
  }
}
