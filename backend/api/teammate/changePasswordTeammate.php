<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
include_once("../../config/database.php");
include_once "../../models/Teammate.php";

$db = new Database();
$conn = $db->connect();
$teammate = new Teammate($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$teammate->usernameTeammate = $decodedData->usernameTeammate;
$teammate->mixedPassword = $decodedData->oldPassword;
$newPassword = $decodedData->newPassword;

$teammateExists = $teammate->searchTeammateByUsername($teammate);

if ((!empty($teammateExists)) {
	if (password_verify($teammate->mixedPassword, $teammateExists['mixedPassword'])) {
		$teammate->mixedPassword = $newPassword;
		$result = $teammate->passwordUpdate($teammate);
		if ($result) {
			echo json_encode('Le mot de passe a été modifié');
		} else {
			echo json_encode('Le mot de passe n\'a pas pu être modifié');
		}
	} else {
		echo json_encode('Le mot de passe est erroné');
	}  		
} else {
  	echo json_encode('Le nom d\'utilisateur n\'existe pas');
}
