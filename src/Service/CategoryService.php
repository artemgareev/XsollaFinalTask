<?php
namespace FinanceApp\Service;

use FinanceApp\Model\Category;
use FinanceApp\Repository\CategoryRepository;

class CategoryService
{

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories()
    {
        return $this->categoryRepository->getCategories();
    }

    public function getCategoryByID($id)
    {
        return $this->categoryRepository->getCategoryByID($id);
    }

    public function getCategoryByName($name)
    {
        return $this->categoryRepository->getCategoryByName($name);
    }

}