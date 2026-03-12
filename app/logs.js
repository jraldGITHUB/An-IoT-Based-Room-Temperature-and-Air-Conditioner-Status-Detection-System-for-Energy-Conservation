document.addEventListener("DOMContentLoaded", loadLogs);

function loadLogs(){

fetch("../database/get_logs.php")
.then(res=>res.json())
.then(data=>{

const table = document.getElementById("logTable");

table.innerHTML="";

data.forEach(log=>{

const date = new Date(log.created_at);

const row = document.createElement("tr");

row.innerHTML = `
<td>${date.toLocaleDateString()}</td>
<td>${date.toLocaleTimeString()}</td>
<td>${log.roomTemp}°C</td>
<td>${log.exhaustTemp}°C</td>
<td>${log.aircon}</td>
<td>${log.exhaustFan}</td>
`;

table.appendChild(row);

});

});

}