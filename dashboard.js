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
legend: { display: true },
title:{
display:true,
text:"Room Temperature Monitoring"
}
},
scales: {
y: {
beginAtZero: false,
ticks:{
callback:value => value+"°C"
}
}
}
}
});


/* ROOM DATA */

const rooms = {

room1:{
name:"Lab 1",
lat:8.359612,
lng:124.869155,
temp:0,
circle:null
},

room2:{
name:"Lab 2",
lat:8.359656,
lng:124.869166,
temp:0,
circle:null
}

};

let selectedRoom = "room1";


/* MAP */

let map;

document.addEventListener("DOMContentLoaded", function(){

map = L.map('map').setView([8.359634, 124.869002],200);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
attribution:'© OpenStreetMap'
}).addTo(map);


/* CREATE ROOM CIRCLES */

for(let key in rooms){

let r = rooms[key];

r.circle = L.circle([r.lat,r.lng],{
color:"yellow",
fillColor:"yellow",
fillOpacity:0.2,
radius:10
}).addTo(map);

r.circle.bindPopup(r.name);

/* CLICK EVENT */

r.circle.on("click",function(){

selectedRoom = key;

alert("Selected "+r.name);

});

}

});


function updateStatus(){

let room = rooms[selectedRoom];


const tempEl = document.getElementById("temp");
const acEl = document.getElementById("acStatus");
const fanEl = document.getElementById("fanStatus");


/* RANDOM TEMP PER ROOM */

const roomTemp = Math.floor(Math.random()*8)+23;
const exhaustTemp = roomTemp - Math.floor(Math.random()*5);

room.temp = roomTemp;


const fanStatus = roomTemp >= 26 ? "ON":"OFF";
const acStatus = exhaustTemp <= 24 ? "ON":"OFF";


/* DASHBOARD */

tempEl.textContent = roomTemp+" °C";
acEl.textContent = acStatus;
fanEl.textContent = fanStatus;




/* STATUS COLORS */

acEl.style.color = acStatus === "ON" ? "red" : "green";
fanEl.style.color = fanStatus === "ON" ? "orange" : "gray";


/* CHART */

const time = new Date().toLocaleTimeString();

chartLabels.push(time);
chartData.push(roomTemp);

if(chartLabels.length>10){

chartLabels.shift();
chartData.shift();

}

tempChart.update();


/* MAP COLOR */

let zoneColor = "yellow";

if(acStatus==="OFF"){

zoneColor="red";

}

if(acStatus==="ON"){

zoneColor="blue";

}

room.circle.setStyle({

color:zoneColor,
fillColor:zoneColor

});


room.circle.bindPopup(

"<b>"+room.name+"</b><br>"+
"Temperature: "+roomTemp+"°C<br>"+
"Aircon: "+acStatus+"<br>"+
"Fan: "+fanStatus

);

function updateTime(){

const now = new Date();

const element = document.getElementById("lastUpdate");

if(element){
element.innerText = now.toLocaleTimeString();
}

}

setInterval(updateTime,3000);


/* SAVE LOG */

let logs = JSON.parse(localStorage.getItem("roomLogs")) || [];

let now = new Date();

logs.push({

date: now.toLocaleDateString(),
time: now.toLocaleTimeString(),
room: room.name,
roomTemp: roomTemp,
exhaustTemp: exhaustTemp,
aircon: acStatus,
exhaustFan: fanStatus

});

if(logs.length > 100){

logs.shift();

}

localStorage.setItem("roomLogs", JSON.stringify(logs));

}


/* AUTO REFRESH EVERY 5 SECONDS */

setInterval(function(){

updateStatus();

},5000);