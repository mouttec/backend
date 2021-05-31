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

$car->idCustomer = $decodedData->idCustomer;
$car->licensePlateCar = $decodedData->licensePlateCar;
$car->brandCar = $decodedData->brandCar;
$car->modelCar = $decodedData->modelCar;
$car->dateOfCirculationCar = $decodedData->dateOfCirculationCar;
$car->motorizationCar = $decodedData->motorizationCar;

$uploadDirectory = 'grayCards/';
$extensions = [
    'jpg',
    'jpeg',
    'png',
    'gif'
];

if (isset($_FILES) && !empty($_FILES['image'])) {
    $extension = strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
    if (in_array($extension, $extensions)) {
		$saveName = htmlspecialchars(strip_tags($decodedData->idCustomer)).'-'.htmlspecialchars(strip_tags($decodedData->licensePlateCar)).'-'.uniqid().$extension;
		move_uploaded_file($_FILES['image']['tmp_name'], '../../' . $uploadDirectory . $saveName);
		$car->urlGrayCard = $saveName;
    } else {
        echo json_encode('Le format de l\'image '. $_FILES['image']['name'] .' n\'est pas bon');
    }
} else {
    echo json_encode('Pas d\'image attachée');
}

if(!empty($decodedData->idCar)) {
    $car->idCar = $decodedData->idCar;
    $result = $car->updateCar($car);
} else {
    $result = $car->createCar($car);
}

if ($result) {
    echo json_encode([ "message" => "Le véhicule a été édité !" ]);
}  else { 
    echo json_encode([ "message" => "Le véhicule n'a pas pu être édité..." ]);
}