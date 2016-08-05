<?php
namespace FinanceApp\Service;

use FinanceApp\Model\Transaction;
use FinanceApp\Repository\TransactionRepository;

class TransactionService
{

    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository){
        $this->transactionRepository = $transactionRepository;
    }

    public function getTransactions($account,$page,$per_page){
        if($page==null || $per_page==null){
            $page=1;
            $per_page=100;
        }
        return $this->transactionRepository->getTransactions($account,$page,$per_page);
    }

    public function createTransaction($transaction){
        return $this->transactionRepository->createTransaction($transaction);
    }

    public function updateTransactionByID($transaction){
        return $this->transactionRepository->updateTransactionByID($transaction);
    }

    public function deleteTransactionByID($transactionID,$accountID){
        return $this->transactionRepository->deleteTransactionByID($transactionID,$accountID);
    }

}