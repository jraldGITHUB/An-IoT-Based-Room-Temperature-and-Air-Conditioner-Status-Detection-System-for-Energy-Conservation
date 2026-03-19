document.getElementById("refreshBtn").addEventListener("click", updateStatus);

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


/* ROOM DATA */

const rooms={

room1:{
name:"Lab 1",
lat:8.359612,
lng:124.869155,
temp:0,
temps:[],
circle:null,
acStart:null
},

room2:{
name:"Lab 2",
lat:8.359656,
lng:124.869166,
temp:0,
temps:[],
circle:null,
acStart:null
}

};

let selectedRoom="room1";


/* MAP */

let map;

document.addEventListener("DOMContentLoaded",function(){

map=L.map('map').setView([8.359634,124.869002],200);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
attribution:'© OpenStreetMap'
}).addTo(map);


/* CREATE ROOM CIRCLES */

for(let key in rooms){

let r=rooms[key];

r.circle=L.circle([r.lat,r.lng],{
color:"yellow",
fillColor:"yellow",
fillOpacity:0.2,
radius:15
}).addTo(map);

r.circle.bindPopup(r.name);

r.circle.on("click",function(){

selectedRoom=key;

alert("Selected "+r.name);

});

}

});

function checkRuntime(runtime){

if(runtime >= 1){

fetch("send_email.php")
.then(response => response.text())
.then(data => console.log(data));

}

}


function updateStatus(){

let room=rooms[selectedRoom];

const tempEl=document.getElementById("temp");
const acEl=document.getElementById("acStatus");
const fanEl=document.getElementById("fanStatus");
const avgEl=document.getElementById("avgTemp");
const minEl=document.getElementById("minTemp");
const maxEl=document.getElementById("maxTemp");
const runtimeEl=document.getElementById("acRuntime");
const roomName=document.getElementById("roomName");


/* RANDOM TEMP */

const roomTemp=Math.floor(Math.random()*8)+23;
const exhaustTemp=roomTemp-Math.floor(Math.random()*5);

room.temp=roomTemp;

room.temps.push(roomTemp);

if(room.temps.length>20){
room.temps.shift();
}


/* TEMPERATURE STATISTICS */

let avgTemp=(room.temps.reduce((a,b)=>a+b,0)/room.temps.length).toFixed(1);

let minTemp=Math.min(...room.temps);
let maxTemp=Math.max(...room.temps);


const fanStatus=roomTemp>=26?"ON":"OFF";
const acStatus=exhaustTemp<=24?"ON":"OFF";


/* AIRCON TIME TRACKING */

if(acStatus==="ON" && !room.acStart){
room.acStart=new Date();
}

if(acStatus==="OFF"){
room.acStart=null;
}

let runtime="0 min";

if(room.acStart){

let diff=(new Date()-room.acStart)/60000;

if(diff<60){
runtime=diff.toFixed(1)+" min";
}else{
runtime=(diff/60).toFixed(2)+" hrs";
}

}

runtimeEl.textContent=runtime;


/* DASHBOARD */

roomName.textContent=room.name;

tempEl.textContent=roomTemp+" °C";
avgEl.textContent=avgTemp+" °C";
minEl.textContent=minTemp+" °C";
maxEl.textContent=maxTemp+" °C";

acEl.textContent=acStatus;
fanEl.textContent=fanStatus;


/* COLORS */

acEl.style.color=acStatus==="ON"?"red":"green";
fanEl.style.color=fanStatus==="ON"?"orange":"gray";


/* CHART */

const time=new Date().toLocaleTimeString();

chartLabels.push(time);
chartData.push(roomTemp);

if(chartLabels.length>10){
chartLabels.shift();
chartData.shift();
}

tempChart.update();


/* HEATMAP TEMPERATURE COLOR */

let zoneColor="yellow";

if(roomTemp<=23){
zoneColor="blue";
}

else if(roomTemp>=28){
zoneColor="red";
}

else{
zoneColor="yellow";
}

room.circle.setStyle({
color:zoneColor,
fillColor:zoneColor
});

room.circle.bindPopup(
"<b>"+room.name+"</b><br>"+
"Temperature: "+roomTemp+"°C<br>"+
"Aircon: "+acStatus+"<br>"+
"Fan: "+fanStatus+"<br>"+
"AC Runtime: "+runtime
);


/* UPDATE TIME */

const now=new Date();
document.getElementById("lastUpdate").innerText=now.toLocaleTimeString();


/* SAVE LOG */

fetch("save_logs.php",{
method:"POST",
headers:{
"Content-Type":"application/json"
},
body:JSON.stringify({

date:now.toLocaleDateString(),
time:now.toLocaleTimeString(),
room:room.name,
roomTemp:roomTemp,
exhaustTemp:exhaustTemp,
aircon:acStatus,
exhaustFan:fanStatus,
runtime:runtime

})

});

}
/* AUTO REFRESH */

setInterval(function(){
updateStatus();
},30000);
