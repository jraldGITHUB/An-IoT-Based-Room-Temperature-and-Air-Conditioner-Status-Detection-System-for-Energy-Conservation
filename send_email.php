<?php

function sendAlert($room,$runtime){

$to = "admin@email.com";
$subject = "Aircon Runtime Alert";

$message = "
Aircon Runtime Warning

Room: $room
Runtime: $runtime

The air conditioner has been running for more than 4 hours.
";

$headers = "From: yourgmail@gmail.com";

mail($to,$subject,$message,$headers);

}

?>