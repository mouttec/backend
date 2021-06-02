<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/TechnicalControlInvoice.php";

$db = new Database();
$conn = $db->connect();
$technicalControlInvoice = new TechnicalControlInvoice($conn);
$target_dir = 'technicalControlInvoice/';

$decodedData = json_decode(file_get_contents("php://input"));

$technicalControlInvoice->idPartner = $decodedData->idPartner;
$technicalControlInvoice->monthlyInvoice = $decodedData->monthlyInvoice;
$technicalControlInvoice->priceInvoice = $decodedData->priceInvoice;
$filenameTechnicalControlInvoice = $decodedData->filenameTechnicalControlInvoice;
$technicalControlInvoice->urlInvoice = $technicalControlInvoice->$target_dir.$idPartner."/".$filenameTechnicalControlInvoice;

foreach($technicalControlInvoice as $value) {
    move_uploaded_file($value['filenameTechnicalControlInvoice'], './'.$target_dir.$idPartner."/".$value['filenameTechnicalControlInvoice']);
}

$result = $technicalControlInvoice->createInvoice($technicalControlInvoice);

if ($result) {
    echo json_encode([ "message" => "La facture a été édité !" ]);
}  else { 
    echo json_encode([ "message" => "La facture n'a pas pu être édité..." ]);
}