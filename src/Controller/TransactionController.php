<?php
namespace FinanceApp\Controller;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use FinanceApp\Model\Transaction;
use FinanceApp\Model\Category;
use FinanceApp\Service\TransactionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends AbstractController
{
    private $accountService;
    private $transactionService;
    private $categoryService;

    public function __construct($userService,$accountService, $transactionService,$categoryService)
    {
        parent::__construct($userService);
        $this->accountService = $accountService;
        $this->transactionService = $transactionService;
        $this->categoryService = $categoryService;
    }

    public function createTransaction(Request $request){
        $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }
        $categoryName = $request->get('category');
        $transAmount =  $request->get('amount');
        if($categoryName!==null && $transAmount!=null){
            $cur_date = date("Y-m-d H:i:s");
            $caregory = $this->categoryService->getCategoryByName($categoryName);
            if($caregory!==null){
                $transaction = new Transaction(
                    null, 
                    $request->get('id'), 
                    $caregory->id, 
                    $transAmount, 
                    $cur_date
                    );
                 return new JsonResponse($this->transactionService->createTransaction($transaction),Response::HTTP_CREATED);
            }
            else{
                 return $this->createErrorResponse('Category with name - '.$request->get('category').' doesnt exist');
            }
        }
        return $this->createErrorResponse('Request must contain category and amount params');
    }

     public function updateTransactionByID(Request $request){
        $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }
        $category = $this->categoryService->getCategoryByName($request->get('category'));
        $transID = $request->get('transID');
        $accID = $request->get('id');
        $sum =  $request->get('sum');
        $transDate =$request->get('date');
        if($category!==null){
            $transaction = new Transaction(
                $transID,
                $accID,
                $category->id,
                $sum,
                $transDate
                );
             return new JsonResponse($this->transactionService->updateTransactionByID($transaction));
        }
        else{
             return $this->createErrorResponse('Category with name - '.$request->get('category').' not exist');
        }
    }

    public function getTransactions(Request $request)
    {
        $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }

        try{
            $transactions = $this->transactionService->getTransactions(
                $request->get('id'),
                $request->get('page'),
                $request->get('per_page')
                );
        }
        catch (DBALException $e) {
            return $this->createErrorResponse('transactions not found');
        }

        return new JsonResponse($transactions);
    }

    public function deleteTransactionByID(Request $request)
    {
        $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }

        try{
            $transaction = $this->transactionService->deleteTransactionByID(
                $request->get('transID'),
                $request->get('id')
                );
        }
        catch (DBALException $e) {
            return $this->createErrorResponse('transactions not found');
        }
        if($transaction!==null)
            return new JsonResponse($transaction);
        else
            return $this->createErrorResponse('transaction with id = '.$request->get('transID').' doesnt exist');
    }
   
}
