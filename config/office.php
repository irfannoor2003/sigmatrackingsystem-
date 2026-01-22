<?php
// config/office.php
return [
    'lat'    => (float) env('OFFICE_LAT'),
    'lng'    => (float) env('OFFICE_LNG'),
    'radius' => (int) env('OFFICE_RADIUS', 500),
    'qr_token' => env('OFFICE_QR_TOKEN', 'SIGMA-OFFICE-QR-2026'),
];
