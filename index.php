<?php

if(file_exists('./install/install.lock')){
    /** 定义 */
    define ('APP_PATH', __DIR__);
    define ('APP_URL', rtrim (dirname ($_SERVER['SCRIPT_NAME']), DIRECTORY_SEPARATOR));
    
    /** 初始化框架 */
    require_once 'System/Init.php'; 
}else{
    
    header ('Location: /install',true,302);
}


   
?>