<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once("../../config/Database.php");
include_once "../../models/Customer.php";

$db = new Database();
$conn = $db->connect();
$customer = new Customer($conn);

$decodedData = json_decode(file_get_contents("php://input"));

$customer->mailCustomer = $decodedData->mailCustomer;
$customer->mixedPassword = $decodedData->oldPassword;
$newPassword = $decodedData->newPassword;

$customerExists = $customer->searchCustomerByEmail($customer);

if ((!empty($customerExists)) {
	if (password_verify($customer->mixedPassword, $customerExists['mixedPassword'])) {
		$customer->mixedPassword = $newPassword;
		$result = $customer->passwordUpdate($customer);
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
