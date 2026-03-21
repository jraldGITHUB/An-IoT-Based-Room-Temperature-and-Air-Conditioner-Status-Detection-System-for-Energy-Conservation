document.addEventListener("DOMContentLoaded", () => {
    loadLogs();

    document.getElementById("roomFilter").addEventListener("change", loadLogs);
    document.getElementById("clearLogs").addEventListener("click", clearLogs);
});

// ✅ SINGLE loadLogs FUNCTION (correct)
function loadLogs(){

    let room = document.getElementById("roomFilter").value;

    fetch("get_logs.php?room=" + room)
    .then(res => res.json())
    .then(data => {

        const table = document.getElementById("logTable");
        table.innerHTML = "";

        data.forEach(log => {

            let row = document.createElement("tr");

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

            <td>
            <span class="badge ${log.runtime.includes("hrs") ? "bg-danger" : "bg-info"}">
            ${log.runtime}
            </span>
            </td>
            `;

            table.appendChild(row);
        });

    });
}

// ✅ CLEAR LOGS
function clearLogs(){

    if(confirm("Clear all logs?")){

        fetch("clear_logs.php")
        .then(() => {
            alert("Logs cleared");
            loadLogs();
        });

    }
}

// ✅ DOWNLOAD CSV (WITH FILTER SUPPORT)
function downloadCSV(){

    let room = document.getElementById("roomFilter").value;

    fetch("get_logs.php?room=" + room)
    .then(res => res.json())
    .then(data => {

        let csv = "Date,Time,Room,Temp,Exhaust,Aircon,Fan,Runtime\n";

        data.forEach(log => {
            csv += `${log.date},${log.time},${log.room},${log.roomTemp},${log.exhaustTemp},${log.aircon},${log.exhaustFan},${log.runtime}\n`;
        });

        let blob = new Blob([csv], { type: "text/csv" });
        let url = window.URL.createObjectURL(blob);

        let a = document.createElement("a");
        a.href = url;
        a.download = "logs.csv";
        a.click();

    });
}