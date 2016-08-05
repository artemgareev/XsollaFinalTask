<?php
namespace FinanceApp\Service;

use FinanceApp\Model\User;
use FinanceApp\Model\Report;
use FinanceApp\Repository\ReportRepository;

class ReportService
{

    protected $reportRepository;
    private $valid_values = array('year','month', 'account', 'category');

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function getReport($user,$params){
        $keys = array_keys($params);    
        $params_count=0;
        foreach($keys as $value){
            if(in_array($value, $this->valid_values))
                $params_count++;
        }
        if($params_count==0){
            return null;
        }
        $result = $this->reportRepository->getReport($user,$params);
        if($result==null)
            return $this->reportRepository->createReport($user,$params);
        else
            return $result;
    }

}