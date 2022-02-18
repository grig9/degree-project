<?php

namespace App;
use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder 
{
  private $pdo;
  private $queryFactory;

  public function __construct(PDO $pdo) 
  {
    $this->pdo = $pdo;
    $this->queryFactory = new QueryFactory('mysql');
  }

  public function getAll($table) 
  {
    $select = $this->queryFactory->newSelect();
    $select->cols(['*']);
    $select->from($table);

    $sth = $this->pdo->prepare($select->getStatement());
    $sth->execute($select->getBindValues());

    return $sth->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getAllPaginator($table, $setpaging, $page) 
  {
    $select = $this->queryFactory->newSelect();
    $select->cols(['*'])
           ->from($table)
           ->setPaging($setpaging)
           ->page($page ?? 1);

    $sth = $this->pdo->prepare($select->getStatement());
    $sth->execute($select->getBindValues());

    return $sth->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getAllCount(String $table)
  {
    $select = $this->queryFactory->newSelect();
    $select->cols(["COUNT(*) AS count"])
           ->from($table);

    $sth = $this->pdo->prepare($select->getStatement());
    $sth->execute($select->getBindValues());
    return (int) $sth->fetchColumn();
  }

  public function getOneById( $table,  $id) 
  {
    $select = $this->queryFactory->newSelect();
    $select->cols(['*'])
           ->from($table)
           ->where('id =  :id')
           ->bindValue('id', $id);

    $sth = $this->pdo->prepare($select->getStatement());

    $sth->execute($select->getBindValues());

    return $sth->fetch(PDO::FETCH_ASSOC);
  }

  public function getOneByEmail($table,  $email) 
  {
    $select = $this->queryFactory->newSelect();
    $select->cols(['*'])
           ->from($table)
           ->where('email = :email')
           ->bindValue('email', $email);

    $sth = $this->pdo->prepare($select->getStatement());
    
    $sth->execute($select->getBindValues());
    
    return $sth->fetch(PDO::FETCH_ASSOC);
  }

  public function insert($table, $data) 
  {
    $insert = $this->queryFactory->newInsert();

    $insert
        ->into($table)                   
        ->cols($data);

    $sth = $this->pdo->prepare($insert->getStatement());

    $sth->execute($insert->getBindValues());
  }

  public function updateById($table, $data, $id) 
  {
    $update = $this->queryFactory->newUpdate();

    $update
        ->table($table)               
        ->cols($data)
        ->where('id = ?', $id);
       

    $sth = $this->pdo->prepare($update->getStatement());

    $sth->execute($update->getBindValues());
  }

  public function deleteById($table, $id) 
  {
    $delete = $this->queryFactory->newDelete();

    $delete
        ->from($table)                  
        ->where('id = :id')          
        ->bindValues(array(             
            'id' => $id,
        ));
    $sth = $this->pdo->prepare($delete->getStatement());

    $sth->execute($delete->getBindValues());
  }
}