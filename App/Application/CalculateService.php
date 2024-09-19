<?php

namespace App\Application;

require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Infrastructure/sdbh.php';

use sdbh\sdbh;

class Calculate extends sdbh
{

    /**
     * @param int $days
     * @param int $product_id
     * @param array $selected_services
     * @return void
     */
    public function calculate1(int $days = 0, int $product_id = 0, array $selected_services = [])
    {
        $product = $this->make_query("SELECT * FROM a25_products WHERE ID = $product_id");
        if ($product) {
            $product = $product[0];
            $price   = $product['PRICE'];
            $tarif   = $product['TARIFF'];
        } else {
            echo "Ошибка, товар не найден!";
            return;
        }

        $tarifs = unserialize($tarif);

        if (is_array($tarifs)) {
            $product_price = $price;
            $maxDaysCount  = 0;
            foreach ($tarifs as $day_count => $tarif_price) {
                if ($days >= $day_count && $maxDaysCount <= $day_count) {
                    $maxDaysCount  = $day_count;
                    $product_price = $tarif_price;
                    $tarif         = $tarif_price;
                }
            }
            $total_price = $product_price * $days;
        } else {
            $total_price = $price * $days;
            $tarif       = $price;
        }


        $services_price = 0;
        $services_total  = 0;
        foreach ($selected_services as $service) {
            $services_total += $service;
            $services_price += (float)$service * $days;
        }

        $total_price += $services_price;


        $summ = [
            'total' => $total_price,
            'services' => $services_total,
            'tarif' => $tarif,
            'days' => $days,
        ];

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($summ);
    }

    public function get_products()
    {
        return $this->make_query("SELECT * FROM a25_products");
    }

    public function get_services()
    {
        return $this->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value'];
    }
}