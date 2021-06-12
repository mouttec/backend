<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
include_once "../../config/Database.php";
include_once "../../models/bookingCalendar.php";
$db = new Database();
$conn = $db->connect();
$calendar = new BookingCalendar($conn);
if (isset($_GET['idBookingCalendar'])) {
	$calendar->idBookingCalendar = $_GET['idBookingCalendar'];
    $result = $calendar->searchCalendarById($calendar);
} else {
    $calendars = $calendar->listCalendar();
    $counter = $calendars->rowCount();
    if ($counter > 0) {
    	$calendars_array = array();
    	while ($row = $calendars->fetch()) {
    		extract($row);
    		$calendar_item = [
    			"idBookingCalendar" => $idBookingCalendar,
	            "datebookingCalendar" => $datebookingCalendar,
	            "h1bookingCalendar" => $h1bookingCalendar,
	            "h2bookingCalendar" => $h2bookingCalendar,
	            "h3bookingCalendar" => $h3bookingCalendar,
	            "h4bookingCalendar" => $h4bookingCalendar,
				"h5bookingCalendar" => $h5bookingCalendar,
                "h6bookingCalendar" => $h6bookingCalendar,
                "h7bookingCalendar" => $h7bookingCalendar,
                "h8bookingCalendar" => $h8bookingCalendar,
                "h9bookingCalendar" => $h9bookingCalendar,
                "h10bookingCalendar" => $h10bookingCalendar,
                "h11bookingCalendar" => $h11bookingCalendar
    		];
            array_push($calendars_array, $calendar_item);
    	}
    	$result = $calendars_array;
    }
}
if (isset($result) && !empty($result)) {
 	echo json_encode($result);
} else { 
    echo json_encode($idPartner);
	http_response_code(404); 
}