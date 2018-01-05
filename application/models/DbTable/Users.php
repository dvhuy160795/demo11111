<?php

class Application_Model_DbTable_Users extends Zend_Db_Table_Abstract
{

    protected $_name = 'users';
    
    public function checkUnique($username)
    {
      $select = $this->select()->from('users',array('username'))->where('username=?',$username);
      $stmt = $select->query();
      $result = $stmt->fetchAll();
      if($result){
        return true;
      }
      return false;
    }
}

