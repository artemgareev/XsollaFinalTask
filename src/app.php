<?php

use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
require __DIR__ . '/../src/dependencybinding.php';

$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'host' => '192.168.100.123',
        'dbname' => 'finance',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ]
]);
$app->register(new ServiceControllerServiceProvider());

$app->before(function(Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$route = $app['controllers_factory'];

$route->post('/users', 'users.controller:register'); // {"email":"test4@test4.ru","password":"1234","name":"test"}
$route->get('/users/me', 'users.controller:getUser');
$route->put('/users/me', 'users.controller:updateUser');

$route->get('/categories', 'category.controller:getCategories');
$route->get('/categories/{id}', 'category.controller:getCategoryByID');

$route->get('/users/me/accounts', 'account.controller:getAccounts');
$route->post('/users/me/accounts', 'account.controller:createAccount');//{"name":"RURwallet","currency":"RUR"}
$route->delete('/users/me/accounts/{id}', 'account.controller:deleteAccountByID');
$route->get('/users/me/accounts/{id}', 'account.controller:getAccountByID');

$route->get('/users/me/accounts/{id}/transactions', 'transaction.controller:getTransactions');
$route->post('/users/me/accounts/{id}/transactions', 'transaction.controller:createTransaction');//{"category":"integer","amount":"1000"}
$route->put('/users/me/accounts/{id}/transactions/{transID} ', 'transaction.controller:updateTransactionByID');//{"category":"nisi","sum": "500","date": "2016-08-05 15:39:23"}
$route->delete('/users/me/accounts/{id}/transactions/{transID} ', 'transaction.controller:deleteTransactionByID');

$route->get('/users/me/reports', 'report.controller:getReport');

$app->mount('/api', $route);