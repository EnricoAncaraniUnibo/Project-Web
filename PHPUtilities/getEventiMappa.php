<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("../PHPUtilities/bootstrap.php");
require_once("../PHPUtilities/geocoding/geocode.php");

// carica cache coordinate
$cacheFile = "../PHPUtilities/geocoding/geocoding.json";
$cache = file_exists($cacheFile) ? json_decode(file_get_contents($cacheFile), true) : [];

// query eventi approvati
$sql = "
SELECT Luogo, Indirizzo, Città, Titolo, Data, Orario
FROM EVENTO
WHERE Stato = 'approvato' AND Data >= CURDATE()
ORDER BY Luogo, Data, Orario
";

// usa getResult del tuo helper
$result = $dbh->getResult($sql);

$luoghi = [];

foreach ($result as $row) {

    $indirizzoCompleto = $row["Indirizzo"] . ", " . $row["Città"];

    // se non è in cache, chiama geocode
    if (!isset($cache[$indirizzoCompleto])) {
        $coords = geocodeAddress($indirizzoCompleto);

        if (!$coords) {
            $coords = ["lat" => 0, "lng" => 0]; // fallback
            file_put_contents( "../PHPUtilities/geocoding/geocode_fail.log", $indirizzoCompleto . "\n", FILE_APPEND);
        }

        $cache[$indirizzoCompleto] = $coords;
        file_put_contents($cacheFile, json_encode($cache));
        sleep(1); // per non sovraccaricare Nominatim
    }

    if (!isset($luoghi[$indirizzoCompleto])) {
        $luoghi[$indirizzoCompleto] = [
            "nome" => $row["Luogo"],
            "indirizzo" => $indirizzoCompleto,
            "coordinate" => [
                $cache[$indirizzoCompleto]["lat"],
                $cache[$indirizzoCompleto]["lng"]
            ],
            "eventi" => []
        ];
    }

    $luoghi[$indirizzoCompleto]["eventi"][] = [
        "titolo" => $row["Titolo"],
        "data" => date("d/m/Y", strtotime($row["Data"])),
        "ora" => substr($row["Orario"], 0, 5)
    ];
}

// 🔹 HEADER JSON obbligatorio
header("Content-Type: application/json");

// 🔹 stampare solo JSON, NIENTE var_dump o print_r
echo json_encode(array_values($luoghi));
exit;
