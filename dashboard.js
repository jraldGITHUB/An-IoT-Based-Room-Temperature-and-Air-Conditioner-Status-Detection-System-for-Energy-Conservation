document.querySelector(".btn-success").addEventListener("click", updateStatus);

function updateStatus() {
  const tempElement = document.getElementById("temp");
  const acElement = document.getElementById("acStatus");

  const temperature = Math.floor(Math.random() * 8) + 23; 
  const acStatus = temperature >= 26 ? "ON" : "OFF";

  tempElement.textContent = temperature + " °C";
  acElement.textContent = acStatus;

  const log = {
    date: new Date().toLocaleDateString(),
    time: new Date().toLocaleTimeString(),
    temperature: temperature,
    acStatus: acStatus
  };

  
  let logs = JSON.parse(localStorage.getItem("roomLogs")) || [];
  logs.unshift(log);

  localStorage.setItem("roomLogs", JSON.stringify(logs));
}
