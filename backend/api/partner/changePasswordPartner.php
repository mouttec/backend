<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/Partner.php";

$db = new Database();
$conn = $db->connect();
$partner = new Partner($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$partner->idPartner = $decodedData->idPartner;
$partner->mixedPassword = $decodedData->password;

if ((!empty($partnerExists)) {
	if (password_verify($partner->mixedPassword, $partnerExists['mixedPassword'])) {
		$partner->mixedPassword = $newPassword;
		$result = $partner->passwordUpdate($partner);
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