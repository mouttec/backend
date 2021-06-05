<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/database.php";
include_once "../../models/Teammate.php";

$db = new Database();
$conn = $db->connect();
$teammate = new Teammate($conn);

$decodedData = json_decode(file_get_contents("php://input"));
$teammate->usernameTeammate = $decodedData->usernameTeammate;
$password = htmlspecialchars($decodedData->password);

$teammateExists = $teammate->searchTeammateByUsername($teammate);

if (!empty($teammateExists)) {
	if (password_verify($password, $teammateExists['mixedPassword'])) {
		echo json_encode($teammateExists);
	} else {
		echo json_encode('Le mot de passe est erroné');
	}  		
} else {
  	echo json_encode('Le nom d\'utilisateur n\'existe pas');
}