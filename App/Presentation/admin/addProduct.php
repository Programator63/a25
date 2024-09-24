<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Application/AdminService.php';

use App\Application\AdminService;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $AdminServices = new AdminService();

    $name  = $_POST['name'];
    $price = $_POST['price'];


    $tarifs = array();
    foreach ($_POST as $key => $value) {
        if (preg_match('/^tarif(\d+)(Price|Name)$/', $key, $matches)) {
            $index = $matches[1];
            $field = $matches[2];

            $tarifs[$index][$field] = $value;
        }
    }

    if(count($tarifs) > 0){
        $countTariff = count($tarifs);
        $tariffStr = 'a:' . $countTariff . ':{';
        foreach ($tarifs as $tarif) {
            $tariffStr .= 'i:' . $tarif['Name'] . ';i:' . $tarif['Price'] . ';';
        }
        $tariffStr .= '}';

        var_dump($tariffStr);
    } else{
        $countTariff = NULL;
    }



    $res = $AdminServices->addNewProduct($name, $price, $tariffStr);
    header('Content-Type: application/json; charset=utf-8');
    if($res >= 0){
        echo json_encode(['success' => true]);
    }else{
        echo json_encode(['success' => false]);
    }
}