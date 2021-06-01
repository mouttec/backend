<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-With");
include_once "../../config/Database.php";
include_once "../../models/Booking.php";
include_once "../../models/Teammate.php";

$db = new Database();
$conn = $db->connect();
$booking = new Booking($conn);
$teammate = new Teammate($conn);

$decodedData = json_decode(file_get_contents("php://input"));

//Recherche du nombre de teammates "drivers" disponibles dans l'agence concernée 
if (isset($decodedData->idAgency)) {
    $teammate->idAgency = $decodedData->idAgency;
    $teammates = $teammate->searchTeammatesByAgency($teammate);
    $counter = $teammates->rowCount();
    if ($counter > 0) {
        $driversInAgency = 0;
        while($row = $teammates->fetch()) {
            extract($row);
            if (($jobTeammate == 'driver') && ($statusTeammate == 1)) {
                $driversInAgency += 1;
            }
        }
    }
} else {
    $driversInAgency = 1;
}

$quarters = ['00', '15', '30', '45'];
$shifts = array(); 
for ($h = 7; $h <= 20; $h++) {
    foreach ($quarters as $quarter) {
        if (strlen($h) == 1) {
            $timecode = '0'.$h.':'.$quarter;
        } else {
            $timecode = $h.':'.$quarter;
        }
        //On retire les créaneaux non disponibles dans le calendrier
        if (($timecode != '07:00') && ($timecode != '07:15') && ($timecode != '20:30') && ($timecode != '20:30')) {
            $shifts[$timecode] = $driversInAgency;
        }
    }
}

//Création des lignes jours dans le calendrier, remplie chacune avec les shifts
$calendar = array();
for ($i = 0; $i < 60; $i++) {
    $calendar[date('Y-m-d', strtotime('+'.$i.' days'))] = $shifts;
}

$booking->idAgency = $decodedData->idAgency;
$bookings = $booking->searchBookingsForCalendar($booking);
$counter = $bookings->rowCount();
if ($counter > 0) {
    while ($row = $bookings->fetch()) {
        extract($row);
        if (!is_null($dateForth)) {
            //$dateForth prend le format YYYY-MM-DD
            $dateForth =  implode('-', array_reverse(explode('/', $dateForth)));
            //$durationDelayInQuarters correspond au nombre de quart d'heure que prend la presta
            $durationDelayInQuarters = round(($durationForth+20)/15);
            for ($i = 0; $i <= $durationDelayInQuarters; $i++) {
                $calendar[$dateForth][date('H:i', strtotime($hoursForth.' +'.$i*15.' minutes'))] -= 1;
            }
        }
        if (!is_null($dateBack)) {
            $dateBack =  implode('-', array_reverse(explode('/', $dateBack)));
            $durationDelayInQuarters = round(($durationBack+20)/15);
            for ($i = 0; $i <= $durationDelayInQuarters; $i++) {
                $calendar[$dateBack][date('H:i', strtotime($hoursBack.' +'.$i*15.' minutes'))] -= 1;
            }
        }
    }
}

if (isset($calendar) && !empty($calendar)) {
    echo json_encode($calendar);
} else { 
    http_response_code(404); 
}