document.getElementById("refreshBtn").addEventListener("click", updateStatus);

const ctx = document.getElementById("tempChart").getContext("2d");

let chartLabels = [];
let chartData = [];

const tempChart = new Chart(ctx, {
  type: "line",
  data: {
    labels: chartLabels,
    datasets: [{
      label: "Room Temperature °C",
      data: chartData,
      borderWidth: 3,
      tension: 0.3,
      fill: true
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: true
      }
    },
    scales: {
      y: {
        beginAtZero: false
      }
    }
  }
});

function updateStatus() {

  const tempEl = document.getElementById("temp");
  const acEl = document.getElementById("acStatus");
  const fanEl = document.getElementById("fanStatus");

  const roomTemp = Math.floor(Math.random() * 8) + 23;
  const exhaustTemp = roomTemp - Math.floor(Math.random() * 5);

  const fanStatus = roomTemp >= 26 ? "ON" : "OFF";
  const acStatus = exhaustTemp <= 24 ? "ON" : "OFF";

  tempEl.textContent = roomTemp + " °C";
  acEl.textContent = acStatus;
  fanEl.textContent = fanStatus;

  const time = new Date().toLocaleTimeString();

  chartLabels.push(time);
  chartData.push(roomTemp);

  if (chartLabels.length > 10) {
    chartLabels.shift();
    chartData.shift();
  }

  tempChart.update();

  const log = {
    date: new Date().toLocaleDateString(),
    time: time,
    roomTemp: roomTemp,
    exhaustTemp: exhaustTemp,
    aircon: acStatus,
    exhaustFan: fanStatus
  };

  let logs = JSON.parse(localStorage.getItem("roomLogs")) || [];
  logs.unshift(log);

  localStorage.setItem("roomLogs", JSON.stringify(logs));
}