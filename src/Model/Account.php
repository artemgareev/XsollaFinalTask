<?php
namespace FinanceApp\Model;

class Account
{

    public $id;

    public $currency;

    public $balance;

    public $user;

    public $name;

    public function __construct($id, $currency, $balance, $user, $name)
    {
        $this->id = $id;
        $this->currency = $currency;
        $this->balance = $balance;
        $this->user = $user;
        $this->name = $name;
    }

}
