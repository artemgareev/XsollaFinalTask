<?php

use FinanceApp\Controller\UserController;
use FinanceApp\Repository\UserRepository;
use FinanceApp\Service\UserService;

use FinanceApp\Controller\CategoryController;
use FinanceApp\Repository\CategoryRepository;
use FinanceApp\Service\CategoryService;

use FinanceApp\Controller\AccountController;
use FinanceApp\Repository\AccountRepository;
use FinanceApp\Service\AccountService;

use FinanceApp\Controller\TransactionController;
use FinanceApp\Repository\TransactionRepository;
use FinanceApp\Service\TransactionService;

use FinanceApp\Controller\ReportController;
use FinanceApp\Repository\ReportRepository;
use FinanceApp\Service\ReportService;

$app['users.controller'] = function ($app) {
    return new UserController($app['users.service']);
};

$app['users.service'] = function ($app) {
    return new UserService($app['users.repository']);
};

$app['users.repository'] = function ($app) {
    return new UserRepository($app['db']);
};

$app['category.controller'] = function ($app) {
    return new CategoryController($app['category.service']);
};

$app['category.service'] = function ($app) {
    return new CategoryService($app['category.repository']);
};

$app['category.repository'] = function ($app) {
    return new CategoryRepository($app['db']);
};

$app['account.controller'] = function ($app) {
    return new AccountController($app['users.service'],$app['account.service']);
};

$app['account.service'] = function ($app) {
    return new AccountService($app['account.repository']);
};

$app['account.repository'] = function ($app) {
    return new AccountRepository($app['db']);
};

$app['transaction.controller'] = function ($app) {
    return new TransactionController($app['users.service'],$app['account.service'],$app['transaction.service'],$app['category.service']);
};

$app['transaction.service'] = function ($app) {
    return new TransactionService($app['transaction.repository']);
};

$app['transaction.repository'] = function ($app) {
    return new TransactionRepository($app['db']);
};

$app['report.controller'] = function ($app) {
    return new ReportController($app['users.service'],$app['account.service'],$app['report.service'],$app['category.service']);
};

$app['report.service'] = function ($app) {
    return new ReportService($app['report.repository']);
};

$app['report.repository'] = function ($app) {
    return new ReportRepository($app['db']);
};


