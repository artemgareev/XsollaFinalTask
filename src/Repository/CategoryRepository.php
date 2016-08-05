<?php
namespace FinanceApp\Repository;

use FinanceApp\Model\Category;

class CategoryRepository extends AbstractRepository
{

    public function getCategories()
    {
        $querry = 'SELECT * FROM category';
        $categories = $this->dbConnection->fetchAll($querry);

        return !is_null($categories[0]) ? array_map(function($categories){
        	return new Category($categories['id'],$categories['name']);
        }, $categories) :array();
    }

    public function getCategoryByID($id)
    {
        $querry = 'SELECT * FROM category WHERE id=? LIMIT 1';
        $categoryRow = $this->dbConnection->fetchArray($querry,[$id]);

        return $categoryRow[0] !==null ? new Category($categoryRow[0],$categoryRow[1]) : null;
    }

    public function getCategoryByName($name)
    {
        $querry = 'SELECT * FROM category WHERE name=? LIMIT 1';
        $categoryRow = $this->dbConnection->fetchArray($querry,[$name]);

        return $categoryRow[0] !==null ? new Category($categoryRow[0],$categoryRow[1]) : null;
    }

}
