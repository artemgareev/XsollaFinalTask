<?php
namespace FinanceApp\Model;

class Report
{

    public $id;

    public $year;

    public $month;

    public $day;

    public $user;

    public $account;

    public $category;

    public $sum;

    public $avg_amount;

    public $start_amount;

    public $end_amount;

    public function __construct(
        $id, 
        $year, 
        $month, 
        $day, 
        $user,
        $account,
        $category,
        $sum,
        $avg_amount,
        $start_amount,
        $end_amount
        )
    {
        $this->id = $id;
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->user = $user;
        $this->account = $account;
        $this->category = $category;
        $this->sum = $sum;
        $this->avg_amount = $avg_amount;
        $this->start_amount = $start_amount;
        $this->end_amount = $end_amount;
    }

}
