<?php
namespace FinanceApp\Controller;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use FinanceApp\Model\Account;
use FinanceApp\Model\Report;
use FinanceApp\Model\Category;
use FinanceApp\Service\ReportService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends AbstractController
{
    private $accountService;
    private $reportService;
    private $categoryService;

    public function __construct($userService,$accountService, $reportService,$categoryService)
    {
        parent::__construct($userService);
        $this->accountService = $accountService;
        $this->reportService = $reportService;
        $this->categoryService = $categoryService;
    }

    public function getReport(Request $request){
        $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }
        $params = $request->query->all();
        if(count(array_keys($params))==0){
            return $this->createErrorResponse('Atleast one of the following fields (year,month,day,account,category) must be included');
        }
        if(!isset($params['account'])){
            $result = array();
            $accounts = $this->accountService->getAccounts($user,null);
           for($i=0;$i<count($accounts);$i++)
           {
                $params['account'] = $accounts[$i]->id;
                $report = $this->reportService->getReport($user,$params);
                if($report!==null)
                {
                    array_push($result,$report);
                }
           }
            if(count($result)!=null)
                return new JsonResponse($result);
            else
                return $this->createErrorResponse('You must have atleast one account');
        }
        else{
            $report = $this->reportService->getReport($user,$params);
            if($report!=null)
                return new JsonResponse($report);
            else
                return $this->createErrorResponse('Impossible to make a report, no data available');
        }
    }
    
}
