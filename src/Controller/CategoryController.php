<?php
namespace FinanceApp\Controller;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use FinanceApp\Service\CategoryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getCategories()
    {
        return new JsonResponse($this->categoryService->getCategories());
    }

    public function getCategoryByID(Request $request)
    {
    	$categoryID = $request->get('id');
    	if($categoryID!==null) {
    		$responce = $this->categoryService->getCategoryByID($categoryID);
    		if($responce!==null){
        		return new JsonResponse($this->categoryService->getCategoryByID($categoryID));
        	}
        	else {
        		return $this->createErrorResponse('Category with id:'.$categoryID.' not found!');
        	}
    	}
        else
        	return $this->createErrorResponse('Category ID must be specified');
    }

}
