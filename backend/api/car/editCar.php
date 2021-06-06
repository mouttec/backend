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

// $uploadDirectory = 'grayCards/';
$uploadDirectory = '';
$extensions = [
    'jpg',
    'jpeg',
    'png',
    'gif'
];

echo json_encode($_FILES['saveName']);
echo json_encode($_FILES['saveName']['error'])

if ((isset($_FILES)) && (!empty($_FILES['saveName']))) {
    echo json_encode('isset$_FILES ok');
    $extension = strtolower(pathinfo($_FILES['saveName']['name'],PATHINFO_EXTENSION));
    if (in_array($extension, $extensions)) {
        echo json_encode('extension ok');
//		$saveName = htmlspecialchars(strip_tags($decodedData->idCustomer)).'-'.htmlspecialchars(strip_tags($decodedData->licensePlateCar)).'-'.uniqid().$extension;
        $saveName = 'nom_de_sauvegarde'.'-'.uniqid().$extension;
        echo json_encode('$saveName = '.$saveName);
		move_uploaded_file($_FILES['saveName']['tmp_name'], $uploadDirectory . $saveName);
//		$car->urlGrayCard = $saveName;
    } else {
        echo json_encode('Le format de l\'image '. $_FILES['saveName']['name'] .' n\'est pas bon');
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