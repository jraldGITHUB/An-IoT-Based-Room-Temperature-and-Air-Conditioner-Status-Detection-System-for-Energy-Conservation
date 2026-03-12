document.getElementById("refreshBtn").addEventListener("click", updateStatus);

function updateStatus() {

const tempEl = document.getElementById("temp");
const acEl = document.getElementById("acStatus");
const fanEl = document.getElementById("fanStatus");

const roomTemp = Math.floor(Math.random()*8)+23;
const exhaustTemp = roomTemp - Math.floor(Math.random()*5);

const fanStatus = roomTemp >= 26 ? "ON" : "OFF";
const acStatus = exhaustTemp <= 24 ? "ON" : "OFF";

tempEl.textContent = roomTemp+" °C";
acEl.textContent = acStatus;
fanEl.textContent = fanStatus;

fetch("../database/add_log.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:
"roomTemp="+roomTemp+
"&exhaustTemp="+exhaustTemp+
"&aircon="+acStatus+
"&fan="+fanStatus
});

}