<?php
// WPBFD-version-check.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");


$latest_version = '1.3';
$download_url = 'https://yourweb/WPBFDoptimizations.zip';
$release_notes = 'https://www.yourweb.com/4307.html';


$plugin_info = [
    'version' => $latest_version,
    'download_url' => $download_url,
    'release_notes' => $release_notes,
];


echo json_encode($plugin_info);
