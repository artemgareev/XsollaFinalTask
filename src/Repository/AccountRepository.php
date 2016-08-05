<?php
namespace FinanceApp\Repository;

use FinanceApp\Model\User;
use FinanceApp\Model\Account;

class AccountRepository extends AbstractRepository
{

    public function getAccounts(User $user,$params){
        $querryParams ='';
        if($params!=null){
        $valid_values = array('currency','name');
            foreach ($params as $key => $value) {
                if(in_array($key, $valid_values)){
                    $querryParams=$querryParams.' AND '.$key.'= "'.$value.'"';
                }
            }
        }
        $querry = 'SELECT id,currency,balance,user,name FROM account WHERE user=?';
        $accounts = $this->dbConnection->fetchAll($querry.$querryParams,[$user->id]);
        return !is_null($accounts[0]) ? array_map(function($accounts){
            return new Account($accounts["id"], $accounts["user"], $accounts["currency"], $accounts["balance"], $accounts["name"]);
        }, $accounts) : array();

    }

    public function createAccount(Account $account){
        $querry = 'SELECT * FROM account WHERE user=? AND name =?';
        $dbResponce = $this->dbConnection->fetchArray($querry,[$account->user,$account->name]);
        if($dbResponce[0]===null)
        {
            $querry2= 'INSERT INTO account(currency, balance, user, name) VALUES (?, ?, ?, ?)';
            $this->dbConnection->executeQuery($querry2,[$account->currency, $account->balance, $account->user, $account->name]);
            $account->id = $this->dbConnection->lastInsertId();
            
            return $account;
        } else {
            return null;}
    }

    public function deleteAccountByID(User $user,$id){
        $querry = 'DELETE FROM account WHERE id = ? and user=?';
        $account = $this->dbConnection->executeQuery($querry, [$id,$user->id]);
        return $account->rowCount() === 0 ? false : true;
    }

    public function getAccountByID(User $user,$id){
        $querry = 'SELECT id,currency,balance,user,name FROM account WHERE id=? and user=?';
        $accountRow = $this->dbConnection->fetchArray($querry,[$id,$user->id]);
        return $accountRow[0] !== null ?
            new Account($accountRow[0], $accountRow[1], $accountRow[2], $accountRow[3], $accountRow[4]) :
            null;
    }
}
