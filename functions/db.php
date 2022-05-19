<?php 


/**
 * PDO SELECT işlemi
 */
function select($sorgu=null,$deger=null,$coklu=true){
    global $db;
    $select = $db->prepare($sorgu);
    $select->execute($deger);
    if($select->rowCount()>0){
        return $select_son = $coklu == true ? $select->fetchAll(PDO::FETCH_ASSOC) : $select->fetch(PDO::FETCH_ASSOC) ;
    }
  }
  
  
  /**
   * PDO INSERT işlemi
   */
  function insert($sorgu=null,$deger=null){
    global $db;
    $insert = $db->prepare($sorgu);
    $insert_son = $insert->execute($deger);
  
    return $db->lastInsertId();
  
  }
  
  /**
   * PDO UPDATE işlemi
   */
  function update($sorgu=null,$deger=null){
    global $db;
    $update = $db->prepare($sorgu);
    $update_son = $update->execute($deger);
  
    return $update_son ? $update_son : $db->errorInfo() ;
  
  }
  
  
  /**
   * PDO DELETE işlemi
   */
  function _delete($sorgu=null,$deger=null){
    global $db;
    $delete = $db->prepare($sorgu);
    $delete_son = $delete->execute($deger);
  
    return $delete_son;
  
  }