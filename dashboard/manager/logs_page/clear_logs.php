<?php

include "db.php";

$conn->query("DELETE FROM room_logs");

echo "cleared";

?>