<?php
namespace FinanceApp\Controller;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use FinanceApp\Model\Account;
use FinanceApp\Service\AccountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends AbstractController
{
    private $accountService;

    public function __construct($userService, $accountService)
    {
        parent::__construct($userService);
        $this->accountService = $accountService;
    }

    public function createAccount(Request $request)
    {
         $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }

        $name = $request->get('name');
        $currency = $request->get('currency');

        if($name !== null && $currency !== null){
            $account = new Account(null,$currency,0,$user->id,$name);
            $account = $this->accountService->createAccount($account);
            if($account!==null){
                return new JsonResponse($account,Response::HTTP_CREATED);
            }
            else{
                return $this->createErrorResponse('Account with this name is already exist');
            }
        }
        else {
            return $this->createErrorResponse('Request must contain name and currency params');
        }
    }

    public function getAccounts(Request $request)
    {
        $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }
        $params = $request->query->all();
        return new JsonResponse($this->accountService->getAccounts($user,$params));
    }

    public function deleteAccountByID(Request $request)
    {
        $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }
    	$account = $this->accountService->deleteAccountByID($user,$request->get('id'));
    	if($account)
        	return new JsonResponse($account);
        else
        	return $this->createErrorResponse('Account with this id doesnt exist');
    }

    public function getAccountByID(Request $request){
        $user = $this->getUserByAuthorization($request);
        if ($user === false) {
            return $this->createUnathorizedResponse();
        }

        $account = $this->accountService->getAccountByID($user,$request->get('id'));
    	if($account!=null)
        	return new JsonResponse([
            'name' => $account->name,
            'balance' => $account->balance,
            'currency' => $account->currency,
        ]);
        	return new JsonResponse(array());
    }

}
