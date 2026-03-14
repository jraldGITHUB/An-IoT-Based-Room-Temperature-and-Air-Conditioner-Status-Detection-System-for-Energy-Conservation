document.addEventListener("DOMContentLoaded", loadLogs);

document.getElementById("roomFilter")?.addEventListener("change", loadLogs);

let currentLogs = []; // store logs from database


function loadLogs(){

const table = document.getElementById("logTable");
table.innerHTML = "";

fetch("get_logs.php")
.then(res => res.json())
.then(logs => {

currentLogs = logs; // save logs globally for CSV

const filter = document.getElementById("roomFilter")?.value || "all";

logs.forEach(log => {

if(filter !== "all" && log.room !== filter){
return;
}

const row = document.createElement("tr");

row.innerHTML = `
<td>${log.date}</td>
<td>${log.time}</td>
<td><strong>${log.room}</strong></td>
<td>${log.roomTemp}°C</td>
<td>${log.exhaustTemp}°C</td>
<td>
<span class="badge ${log.aircon === "ON" ? "bg-danger" : "bg-success"}">
${log.aircon}
</span>
</td>
<td>
<span class="badge ${log.exhaustFan === "ON" ? "bg-warning" : "bg-secondary"}">
${log.exhaustFan}
</span>
</td>
`;

table.appendChild(row);

});

});

}


/* CLEAR LOGS */

document.getElementById("clearLogs")?.addEventListener("click", function(){

if(confirm("Clear all logs?")){

fetch("clear_logs.php")
.then(() => loadLogs());

}

});


/* DOWNLOAD CSV */

function downloadCSV(){

if(currentLogs.length === 0){
alert("No logs available");
return;
}

let csv = "Date,Time,Room,Room Temp,Exhaust Temp,Aircon Status,Exhaust Fan\n";

currentLogs.forEach(log => {
csv += `${log.date},${log.time},${log.room},${log.roomTemp},${log.exhaustTemp},${log.aircon},${log.exhaustFan}\n`;
});

const blob = new Blob([csv], {type:"text/csv"});

const link = document.createElement("a");

link.href = URL.createObjectURL(blob);
link.download = "room_logs.csv";
link.click();

}