<?php
namespace FinanceApp\Service;

use FinanceApp\Model\User;
use FinanceApp\Model\Account;
use FinanceApp\Repository\AccountRepository;

class AccountService
{

    protected $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function createAccount(Account $account)
    {
        return $this->accountRepository->createAccount($account);
    }

    public function getAccounts(User $user,$params=null)
    {
        return $this->accountRepository->getAccounts($user,$params);
    }

    public function deleteAccountByID(User $user,$id){
        return $this->accountRepository->deleteAccountByID($user,$id);
    }

    public function getAccountByID(User $user,$id){
        return $this->accountRepository->getAccountByID($user,$id);
    }


}