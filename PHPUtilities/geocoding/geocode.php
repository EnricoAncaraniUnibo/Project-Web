<?php
function geocodeAddress(string $address): ?array {

    $url = "https://nominatim.openstreetmap.org/search?" . http_build_query([
        "q" => $address,
        "format" => "json",
        "limit" => 1
    ]);

    $opts = [
        "http" => [
            "header" => "User-Agent: gestionale-eventi/1.0\r\n"
        ]
    ];

    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);

    if (!$response) return null;

    $data = json_decode($response, true);
    if (empty($data)) return null;

    return [
        "lat" => (float)$data[0]["lat"],
        "lng" => (float)$data[0]["lon"]
    ];
}
