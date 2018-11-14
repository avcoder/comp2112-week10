<?php
header("Access-Control-Allow-Origin: *");
require_once 'db.php';

$sql = 'SELECT vote as voted, COUNT(vote) as tally FROM votes GROUP BY vote';
$cmd = $conn->prepare($sql);
$cmd->execute();
$votes = $cmd->fetchAll();
$conn=null;


// convert to json
$json_votes = json_encode($votes);

// output json
echo $json_votes;

?>