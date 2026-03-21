// REFRESH BUTTON
document.getElementById("refreshBtn").addEventListener("click", updateStatus);

// CHART SETUP
const ctx = document.getElementById("tempChart").getContext("2d");

let chartLabels = [];
let chartData = [];

const tempChart = new Chart(ctx,{
type:"line",
data:{
labels:chartLabels,
datasets:[{
label:"Room Temperature °C",
data:chartData,
borderWidth:3,
tension:0.3,
fill:true
}]
},
options:{
responsive:true,
plugins:{
legend:{display:true},
title:{
display:true,
text:"Room Temperature Monitoring"
}
},
scales:{
y:{
beginAtZero:false,
ticks:{
callback:value=>value+"°C"
}
}
}
}
});


// ROOMS OBJECT
let rooms = {};
let selectedRoom = null;
let map;


// LOAD ROOMS FROM DB
fetch("get_rooms.php")
.then(res => res.json())
.then(data => {

data.forEach(room => {

let key = "room"+room.id;

rooms[key] = {
name: room.room_name,
lat: parseFloat(room.latitude),
lng: parseFloat(room.longitude),
device_id: room.device_id,
sensor: room.sensor_status === "OFF" ? "INACTIVE" : "ACTIVE",
temp:0,
temps:[],
circle:null,
acStart:null
};

});

initMap();

});


// MAP INIT
function initMap(){

map = L.map('map').setView([8.359634,124.869002],20);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
attribution:'© OpenStreetMap'
}).addTo(map);


// CREATE ROOMS
for(let key in rooms){

let r = rooms[key];

let color = r.sensor === "ACTIVE" ? "yellow" : "gray";

r.circle = L.circle([r.lat,r.lng],{
color:color,
fillColor:color,
fillOpacity:0.3,
radius:15
}).addTo(map);


// POPUP
r.circle.bindPopup(`
<b>${r.name}</b><br>
Sensor: ${r.sensor}<br>
<button onclick="toggleSensor(${r.device_id})">Toggle Sensor</button>
`);


// CLICK SELECT
r.circle.on("click",function(){
selectedRoom = key;
document.getElementById("roomName").textContent = r.name;
});

}

// DEFAULT ROOM
selectedRoom = Object.keys(rooms)[0];

}


// TOGGLE SENSOR (NO RELOAD)
function toggleSensor(id){

fetch("add_monitor_rooms/toggle_device.php?id="+id)
.then(()=>{

// update locally
for(let key in rooms){
if(rooms[key].device_id == id){
rooms[key].sensor = (rooms[key].sensor === "ACTIVE") ? "INACTIVE" : "ACTIVE";
}
}

updateStatus();

});

}


// UPDATE STATUS
function updateStatus(){

if(!selectedRoom) return;

let room = rooms[selectedRoom];

const tempEl=document.getElementById("temp");
const acEl=document.getElementById("acStatus");
const fanEl=document.getElementById("fanStatus");
const avgEl=document.getElementById("avgTemp");
const minEl=document.getElementById("minTemp");
const maxEl=document.getElementById("maxTemp");
const runtimeEl=document.getElementById("acRuntime");
const roomName=document.getElementById("roomName");


// 🚨 SENSOR OFF
if(room.sensor === "INACTIVE"){

tempEl.textContent="--";
avgEl.textContent="--";
minEl.textContent="--";
maxEl.textContent="--";

acEl.textContent="OFF";
fanEl.textContent="OFF";
runtimeEl.textContent="Sensor OFF";

room.circle.setStyle({
color:"gray",
fillColor:"gray",
fillOpacity:0.3
});

return; // STOP EVERYTHING
}


// SIMULATION
const roomTemp = Math.floor(Math.random()*8)+23;
const exhaustTemp = roomTemp - Math.floor(Math.random()*5);

room.temp = roomTemp;
room.temps.push(roomTemp);

if(room.temps.length>20){
room.temps.shift();
}


// STATS
let avgTemp = (room.temps.reduce((a,b)=>a+b,0)/room.temps.length).toFixed(1);
let minTemp = Math.min(...room.temps);
let maxTemp = Math.max(...room.temps);

const fanStatus = roomTemp>=26 ? "ON" : "OFF";
const acStatus = exhaustTemp<=24 ? "ON" : "OFF";


// RUNTIME
if(acStatus==="ON" && !room.acStart){
room.acStart=new Date();
}

if(acStatus==="OFF"){
room.acStart=null;
}

let runtime="0 min";

if(room.acStart){
let diff=(new Date()-room.acStart)/60000;
runtime = diff<60 ? diff.toFixed(1)+" min" : (diff/60).toFixed(2)+" hrs";
}

runtimeEl.textContent=runtime;


// UI
roomName.textContent=room.name;

tempEl.textContent=roomTemp+" °C";
avgEl.textContent=avgTemp+" °C";
minEl.textContent=minTemp+" °C";
maxEl.textContent=maxTemp+" °C";

acEl.textContent=acStatus;
fanEl.textContent=fanStatus;

acEl.style.color=acStatus==="ON"?"red":"green";
fanEl.style.color=fanStatus==="ON"?"orange":"gray";


// CHART
const time=new Date().toLocaleTimeString();

chartLabels.push(time);
chartData.push(roomTemp);

if(chartLabels.length>10){
chartLabels.shift();
chartData.shift();
}

tempChart.update();


// HEATMAP COLOR (ONLY WHEN ACTIVE)
let zoneColor="yellow";

if(roomTemp<=23) zoneColor="blue";
else if(roomTemp>=28) zoneColor="red";

room.circle.setStyle({
color:zoneColor,
fillColor:zoneColor
});


// POPUP UPDATE
room.circle.bindPopup(`
<b>${room.name}</b><br>
Sensor: ${room.sensor}<br>
Temp: ${roomTemp}°C<br>
AC: ${acStatus}<br>
Fan: ${fanStatus}<br>
Runtime: ${runtime}<br>
<button onclick="toggleSensor(${room.device_id})">Toggle Sensor</button>
`);


// TIME
document.getElementById("lastUpdate").innerText = new Date().toLocaleTimeString();


// SAVE LOGS
fetch("logs_page/save_logs.php",{
method:"POST",
headers:{"Content-Type":"application/json"},
body:JSON.stringify({
date:new Date().toLocaleDateString(),
time:new Date().toLocaleTimeString(),
room:room.name,
roomTemp:roomTemp,
exhaustTemp:exhaustTemp,
aircon:acStatus,
exhaustFan:fanStatus,
runtime:runtime
})
});

}


// AUTO REFRESH STATUS
setInterval(updateStatus,30000);


// AUTO REFRESH SENSOR FROM DB
function refreshSensors(){

fetch("get_rooms.php")
.then(res => res.json())
.then(data => {

data.forEach(room => {

let key = "room"+room.id;

if(rooms[key]){
rooms[key].sensor = room.sensor_status === "OFF" ? "INACTIVE" : "ACTIVE";
}

});

});

}

// every 5 sec sync
setInterval(refreshSensors,5000);