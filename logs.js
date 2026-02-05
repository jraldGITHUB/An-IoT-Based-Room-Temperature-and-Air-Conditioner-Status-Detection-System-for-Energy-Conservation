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
      <td>${log.temperature}°C</td>
      <td>
        <span class="badge ${log.acStatus === "ON" ? "bg-danger" : "bg-success"}">
          ${log.acStatus}
        </span>
      </td>
    `;

    table.appendChild(row);
  });
}
