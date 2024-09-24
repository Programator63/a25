<?php

require_once '../Application/CalculateService.php';

use App\Application\Calculate;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $days              = (int)empty(!$_POST['days']) ? $_POST['days'] : 0;
    $product_id        = (int)empty(!$_POST['product']) ? $_POST['product'] : 0;
    $selected_services = empty(!$_POST['services']) ? $_POST['services'] : [];

    $instance = new Calculate();
    $res = $instance->calculate1($days, $product_id, $selected_services);

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($res);
}