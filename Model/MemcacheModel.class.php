<?php

class MemcacheModel extends FLModel {
    private $m = null;
    function __construct() {
        $this->m = new Memcached();
       $this->m->addServer('localhost', 11211);
        parent::__construct ();
        
    }
    function set($key, $val, $expired = 0) {
        if ($this->m) {
            $this->m->set($key, $val, $expired);
        }
        
        return false;
    }
     function get($key) {
        if ($this->m) {
            return $this->m->get($key);
        }
        return false;
    }
     function del($key) {
        if ($this->m) {
            $this->m->delete($key);
        }
        return false;
    }
    //botid 获取 $token
   
    
    
    
    
    
} ?>