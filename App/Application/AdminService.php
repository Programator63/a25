<?php

namespace App\Application;
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Domain/Users/UserEntity.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Infrastructure/sdbh.php';

use sdbh\sdbh;
use App\Domain\Users\UserEntity;


class AdminService
{

    private $sdbh;

    /** @var UserEntity */
    public $user;


    public function __construct()
    {
        $this->user = new UserEntity();
        $this->sdbh = new sdbh();
    }

    public function addNewProduct(string $name, string $price, $tariff = NULL)
    {
        if (!$this->user->isAdmin) return false;
        $rows = [
            "NAME" => $name,
            "PRICE" => $price,
            "TARIFF" => $tariff
        ];
        return $this->sdbh->insert_row("a25_products", $rows);
    }
}