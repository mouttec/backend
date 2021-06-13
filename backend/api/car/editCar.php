<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/Car.php";

$db = new Database();
$conn = $db->connect();
$car = new Car($conn);

$decodedData = json_decode(file_get_contents("php://input"));

// $car->idCustomer = $decodedData->idCustomer;
// $car->licensePlateCar = $decodedData->licensePlateCar;
// $car->brandCar = $decodedData->brandCar;
// $car->modelCar = $decodedData->modelCar;
// $car->dateOfCirculationCar = $decodedData->dateOfCirculationCar;
// $car->motorizationCar = $decodedData->motorizationCar;
// $car->versionCar = $decodedData->versionCar;
// $car->colorCar = $decodedData->colorCar;

$uploadDirectory = '../';
$extensions = [
    'jpg',
    'jpeg',
    'png',
    'gif'
];  

$file = $_FILES['file']['name'];
$tempName = $_FILES['file']['tmp_name'];
$error = $_FILES['file']['error'];

if (!empty($file)) {
    $extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
    if (in_array($extension, $extensions)) {
//		$saveName = htmlspecialchars(strip_tags($decodedData->idCustomer)).'-'.htmlspecialchars(strip_tags($decodedData->licensePlateCar)).'-'.uniqid().'.'.$extension;
        $saveName = 'nom_de_sauvegarde'.'-'.uniqid().'.'.$extension;
		move_uploaded_file($tempName, $uploadDirectory.$file);
//		$car->urlGrayCard = $saveName;
    } else {
        echo json_encode('Le format de l\'image '. $file .' n\'est pas bon');
    }
}

if(!empty($decodedData->idCar)) {
    $car->idCar = $decodedData->idCar;
    $result = $car->updateCar($car);
} else {
    $result = $car->createCar($car);
    $car->addGrayCardToCar($car);
}

// if ($result) {
//     echo json_encode([ "message" => "Le véhicule a été édité !" ]);
// }  else { 
//     echo json_encode([ "message" => "Le véhicule n'a pas pu être édité..." ]);
// }