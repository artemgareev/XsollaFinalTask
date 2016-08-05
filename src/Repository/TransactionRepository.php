<?php
namespace FinanceApp\Repository;

use FinanceApp\Model\Account;
use FinanceApp\Model\Transaction;

class TransactionRepository extends AbstractRepository
{

    public function getTransactions($account,$page,$per_page){
        $query = 'SELECT transaction.id,category.name,transaction.amount,transaction.date FROM transaction INNER JOIN
            category ON category.id=transaction.category WHERE transaction.account = ?';
        $pagination = ' LIMIT '.(($page-1)*$per_page).','.$per_page;
        $transactions = $this->dbConnection->fetchAll($query.$pagination,[$account]);   
        return !is_null($transactions[0]) ? array_map(function($transactions){
            return array(
                'id'=>$transactions["id"], 
                'sum'=>$transactions["amount"], 
                'category'=>$transactions["name"], 
                'date'=>$transactions["date"]
                );
        }, $transactions) : array();
    }

    public function createTransaction($transaction){
        $this->dbConnection->beginTransaction();
        try {
            $this->dbConnection->executeQuery(
                'INSERT INTO transaction (account, category, amount, date) VALUES (?, ?, ?, ?)',
                [$transaction->account, $transaction->category, $transaction->amount, $transaction->date]
            );

            $transaction->id = $this->dbConnection->lastInsertId();

            $this->dbConnection->executeQuery(
                'UPDATE account SET balance = balance + ? WHERE id = ?',
                [$transaction->amount, $transaction->account]
            );
            
            $this->dbConnection->commit();                  
        }
        catch (Exception $e) {
            $this->dbConnection->rollBack();
            return null;
        }

        return $transaction;
    }

    public function updateTransactionByID($transaction){
        $querry = 'SELECT amount FROM transaction WHERE id=? AND account=? LIMIT 1';
        $oldAmount = $this->dbConnection->fetchArray($querry,[$transaction->id,$transaction->account]);
        if($oldAmount[0]!==null){
             $this->dbConnection->beginTransaction();
             try {
                 $this->dbConnection->executeQuery(
                     'UPDATE transaction SET category=?, amount=?, date=? WHERE id=?',
                     [$transaction->category, $transaction->amount, $transaction->date, $transaction->id]
                 );
                 $newBalance = $transaction->amount-$oldAmount[0];
                 $this->dbConnection->executeQuery(
                     'UPDATE account SET balance = balance + ? WHERE id = ?',
                     [$newBalance,$transaction->account]
                 );
                
                 $this->dbConnection->commit();
             }
             catch (Exception $e) {
                 $this->dbConnection->rollBack();
                 return null;
             }
             return $transaction;
        }
        return null;
    }

    public function deleteTransactionByID($transactionID,$accountID){
        $querry = 'SELECT id,account,category,amount,date FROM transaction WHERE id=? AND account=? LIMIT 1';
        $transaction = $this->dbConnection->fetchAssoc($querry,[$transactionID,$accountID]);
        if($transaction["id"]!==null){
             $this->dbConnection->beginTransaction();
             try {
                 $this->dbConnection->executeQuery(
                     'DELETE FROM transaction WHERE id=?',
                     [$transaction["id"]]
                 );

                 $this->dbConnection->executeQuery(
                     'UPDATE account SET balance = balance - ? WHERE id = ?',
                     [$transaction["amount"],$transaction["account"]]
                 );
                
                 $this->dbConnection->commit();
             }
             catch (Exception $e) {
                 $this->dbConnection->rollBack();
                 return null;
             }
             return $transaction;
        }
        return null;
    }
    
}
