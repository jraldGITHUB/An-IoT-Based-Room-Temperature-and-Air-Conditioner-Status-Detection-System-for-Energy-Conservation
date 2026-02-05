document.addEventListener("DOMContentLoaded", loadLogs);

function loadLogs() {
  const table = document.getElementById("logTable");
  const logs = JSON.parse(localStorage.getItem("roomLogs")) || [];

  table.innerHTML = "";

  logs.forEach(log => {
    const row = document.createElement("tr");

    row.innerHTML = `
      <td>${log.date}</td>
      <td>${log.time}</td>
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
}
