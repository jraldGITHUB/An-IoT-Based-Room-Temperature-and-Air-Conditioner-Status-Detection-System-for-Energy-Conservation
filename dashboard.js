document.getElementById("refreshBtn").addEventListener("click", updateStatus);

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

  
  const log = {
    date: new Date().toLocaleDateString(),
    time: new Date().toLocaleTimeString(),
    roomTemp: roomTemp,
    exhaustTemp: exhaustTemp,
    aircon: acStatus,
    exhaustFan: fanStatus
  };


  let logs = JSON.parse(localStorage.getItem("roomLogs")) || [];
  logs.unshift(log);
  localStorage.setItem("roomLogs", JSON.stringify(logs));
}
