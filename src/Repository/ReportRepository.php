<?php
namespace FinanceApp\Repository;

use FinanceApp\Model\Report;
use FinanceApp\Model\Transaction;
use Doctrine\DBAL\DBALException;

class ReportRepository extends AbstractRepository
{
    private $valid_month_values = array(
        'jan'=>1,
        'feb'=>2,
        'mar'=>3,
        'apr'=>4,
        'may'=>5,
        'jun'=>6,
        'jul'=>7,
        'aug'=>8,
        'sep'=>9,
        'oct'=>10,
        'nov'=>11,
        'dec'=>12
    );

    public function getReport($user,$params){
        $keys = array_keys($params);
        $query = 'SELECT * FROM Report WHERE User=' . $user->id;
        if(in_array('account', $keys)){
            $query .= ' AND Account='.$this->formatNullableObject($params['account']);
        } else {
            $query .= ' AND Account is NULL';
        }
        
        if(in_array('category', $keys)){
            $query .= ' AND Category='.$this->formatNullableObject($params['category']);
        } else {
            $query .= ' AND Category is NULL';
        }
        
        if(in_array('day', $keys)){
            $query .= ' AND Day=' . $this->formatNullableObject($params['day']);
        } else {
            $query .= ' AND Day is NULL';
        }
        
        if(in_array('month', $keys)){
            $query .= ' AND Month=' . $this->formatNullableObject($this->valid_month_values[$params['month']]);
        } else {
            $query .= ' AND Month is NULL';
        }
        
        if(in_array('year', $keys)){
            $query .= ' AND Year=' . $this->formatNullableObject($params['year']);
        } else {
            $query .= ' AND Year is NULL';
        }

        try{
        $result = $this->dbConnection->fetchAssoc($query);
        }
        catch(Exception $ex){
            return null;
        }
        
        return !is_null($result['Id']) ?
            new Report(
                $result['Id'], 
                $result['Day'], 
                $result['Month'], 
                $result['Year'],
                $user->id,
                $result['Account'],
                $result['Category'],
                $result['Sum'],
                $result['Avg_amount'],
                $result['Start_amount'],
                $result['End_amount']
                ):null;
    }

    public function createReport($user,$params){
        $querryParams ='';
        $report = new Report(null,null,null,null,null,null,null,null,null,null,null);
        $keys = array_keys($params);
        $query = 'SELECT SUM(amount) as sum, AVG(amount) as avg, MIN(id) as min,MAX(id) as max FROM transaction WHERE  id IS NOT NULL ';
        $report->user = $user->id;
        if(in_array('account', $keys)) {
            $query .= 'AND account='. $params['account'].' '; 
            $report->account =$params['account'];
        }
        if(in_array('category', $keys)){
            $query .= 'AND category='.$params['category'].' ';
            $report->category =$params['category'];
        }
        if(in_array('year', $keys)){
            $query .= 'AND YEAR(transaction.date)='.$params['year'].' ';
            $report->year =$params['year'];
        }
        if(in_array('month', $keys)){
            if(in_array($params['month'], array_keys($this->valid_month_values))){
                $query .= 'AND MONTH(transaction.date)='.$this->valid_month_values[$params['month']] .' ';
                $report->month =$this->valid_month_values[$params['month']];
                if(in_array('day', $keys)){
                    $query .= 'AND DAY(transaction.date)='.$params['day'] .' ';
                    $report->day= $params['day'];
                }
            }
        }
        
        $query.=' GROUP BY (transaction.account)';
        try{
            $result = $this->dbConnection->fetchAssoc($query);
            $report->sum =$result['sum'];
            $report->avg_amount =$result['avg'];
            $fetchMMamount = 'SELECT amount FROM transaction WHERE id=? LIMIT 1';
            $report->start_amount  = $this->dbConnection->fetchArray($fetchMMamount,[$result['min']])[0];
            $report->end_amount  = $this->dbConnection->fetchArray($fetchMMamount,[$result['min']])[0];

        }
        catch(DBALException $e) {
            return $e;
        }
        if($result){
           return $this->insertReport($report);
        }
        else
            return null;
        
    }
    
    private function insertReport($report){
        try{
            $query = 'INSERT INTO Report (Year, Month ,Day ,User ,Account ,Category ,Sum ,Avg_amount ,Start_amount ,End_amount) VALUES ('.$this->formatNullableObject($report->year).','.
                $this->formatNullableObject($report->month).','.
                $this->formatNullableObject($report->day).','.
                $this->formatNullableObject($report->user).','.
                $this->formatNullableObject($report->account).','.
                $this->formatNullableObject($report->category).','.
                $this->formatNullableObject($report->sum).','.
                $this->formatNullableObject($report->avg_amount).','.
                $this->formatNullableObject($report->start_amount).','.
                $this->formatNullableObject($report->end_amount).')';
            $this->dbConnection->executeQuery($query);
            $report->id = $this->dbConnection->lastInsertId();
            return $report ;
        } 
        catch(Exception $e){
            return null;
        }
        
        return null;
    }

    private function formatNullableObject($param) {
        return (is_null($param) ? 'NULL' : $param);
    }
    
    private function formatNullableString($param) {
        return (is_null($param) ? 'NULL' : '\''.$param.'\'');
    }

}
