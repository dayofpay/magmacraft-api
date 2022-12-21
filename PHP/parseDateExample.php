<?php


$url = "https://v-devs.online/api.php?projectName=magmacraft&functionName=getClanPlayers&clanName=Gapple";
$response = file_get_contents($url);


$data = json_decode($response, true);



?>
