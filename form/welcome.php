<?php
include "fetch.php";

$html = file_get_contents("welcome.html");

$html = str_replace("{{title}}", $data['title'], $html);
$html = str_replace("{{description}}", $data['description'], $html);
$html = str_replace("{{start_date}}", $data['start_date'], $html);
$html = str_replace("{{end_date}}", $data['end_date'], $html);

echo $html;
?>
