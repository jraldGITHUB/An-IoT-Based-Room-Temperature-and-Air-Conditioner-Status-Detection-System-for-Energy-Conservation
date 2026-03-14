document.addEventListener("DOMContentLoaded", loadLogs);

function loadLogs(){

const table=document.getElementById("logTable");
const logs=JSON.parse(localStorage.getItem("roomLogs"))||[];

table.innerHTML="";

logs.forEach(log=>{

const row=document.createElement("tr");

row.innerHTML=`
<td>${log.date}</td>
<td>${log.time}</td>
<td><strong>${log.room}</strong></td>
<td>${log.roomTemp}°C</td>
<td>${log.exhaustTemp}°C</td>
<td>
<span class="badge ${log.aircon==="ON"?"bg-danger":"bg-success"}">
${log.aircon}
</span>
</td>
<td>
<span class="badge ${log.exhaustFan==="ON"?"bg-warning":"bg-secondary"}">
${log.exhaustFan}
</span>
</td>
`;

table.appendChild(row);

});

}


/* CLEAR LOGS */

document.getElementById("clearLogs")?.addEventListener("click",function(){

if(confirm("Clear all logs?")){

localStorage.removeItem("roomLogs");
loadLogs();

}

});


/* DOWNLOAD CSV */

function downloadCSV(){

const logs=JSON.parse(localStorage.getItem("roomLogs"))||[];

if(logs.length===0){
alert("No logs available");
return;
}

let csv="Date,Time,Room,Room Temp,Exhaust Temp,Aircon Status,Exhaust Fan\n";

logs.forEach(log=>{
csv+=`${log.date},${log.time},${log.room},${log.roomTemp},${log.exhaustTemp},${log.aircon},${log.exhaustFan}\n`;
});

const blob=new Blob([csv],{type:"text/csv"});

const link=document.createElement("a");

link.href=URL.createObjectURL(blob);
link.download="room_logs.csv";
link.click();

}