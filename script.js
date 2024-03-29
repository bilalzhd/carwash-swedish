function showLoadingIndicator() {
  const loadingIndicator = document.getElementById("loading-indicator");
  if (loadingIndicator) {
    loadingIndicator.style.display = "block";
  }
}

function hideLoadingIndicator() {
  const loadingIndicator = document.getElementById("loading-indicator");
  if (loadingIndicator) {
    loadingIndicator.style.display = "none";
  }
}

function showLoadingMessage() {
  const loadingMessage = document.getElementById("loading-message");
  if (loadingMessage) {
    loadingMessage.style.display = "block";
  }
}

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
async function renderCalendar() {
    daysContainer.innerHTML = "<div id='loading-indicator'></div>";
  // Set loading state to true initially
  let isLoading = true;
  showLoadingIndicator();
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
  let totalRecords;
  // prev days html
  for (let x = firstDay.getDay(); x > 0; x--) {
    days += `<div class="day prev">${prevLastDayDate - x + 1}</div>`;
  }

  // current month days
  for (let i = 1; i <= lastDayDate; i++) {
    isLoading = true;
    showLoadingIndicator();
    let linkDay = i < 10 ? "0" + i : i;
    let link = `${currentYear}-${currentMonth + 1}-${linkDay}`;
    // check if its today then add today class
    try {
      const response = await fetch(`partials/getRecords.php?date=${link}`);
      const data = await response.json();
      totalRecords = data.totalRecords;
    } catch (error) {
      console.error(error);
    }

    isLoading = false;
    hideLoadingIndicator();

    function updateCalendar() {
      if (
        i === new Date().getDate() &&
        currentMonth === new Date().getMonth() &&
        currentYear === new Date().getFullYear()
      ) {
        if (totalRecords != null) {
          days += `<a class="day today" href="records.php?date=${link}"><div class="day-container">${i} <span class="badge">${totalRecords}<span></div></a>`;
        } else {
          days += `<a class="day today" href="records.php?date=${link}"><div class="day-container">${i} </div></a>`;
        }
        // if date month year matches add today
      } else {
        //else dont add today
        if (totalRecords != null) {
          days += `<a class="day" href="records.php?date=${link}"><div class="day-container">${i} <span class="badge">${totalRecords}</span></div></a>`;
        } else {
          days += `<a class="day" href="records.php?date=${link}"><div class="day-container">${i} </div></a>`;
        }
      }
    }

    if (isLoading) {
      showLoadingIndicator();
    } else {
      updateCalendar();
    }
  }

  // next MOnth days
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
