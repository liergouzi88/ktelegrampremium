<?php
    class Settings extends FLController {
        function run () {
            $this->view->render ();
        }
        function init () {
            session_start ();
            if ($_SESSION['logined'] != true) {
                header ('Location: ' . APP_URL . '/index.php/login');
                exit ();
            }
        }
        function ajaxSave () {
            /** 检查 */
            if ($_POST['master'] == '' && ($_POST['fastLogin'] == 'true' || $_POST['debug'] == 'true')) {
                exit (json_encode (array ('code' => -1, 'msg' => '请设置主人 ID')));
            }
            
            /** 初始化 */
            $systemModel = new SystemModel;
            
            /** 保存配置文件 */
            $config = "<?php
    define ('BOTNAME', '" . addslashes ($_POST['botName']) . "');
    define ('TOKEN', '" . addslashes ($_POST['botToken']) . "');
    define ('MASTER', '" . addslashes ($_POST['master']) . "');
     define ('shoujia', '" . addslashes ($_POST['shoujia']) . "');//套餐售价 3个 不能少
     define ('pduid', '" . addslashes ($_POST['pduid']) . "');//telegram 搜索 @MTETBOT 快捷登录获取
      define ('pdkey', '" . addslashes ($_POST['pdkey']) . "');//telegram 搜索 @MTETBOT 快捷登录获取
       define ('trxadd', '" . addslashes ($_POST['trxadd']) . "');
      define ('kefuhao', '" . addslashes ($_POST['kefuhao']) . "');
    define ('FASTLOGIN', " . addslashes ($_POST['fastLogin']) . ");
    define ('DEBUG', " . addslashes ($_POST['debug']) . ");
";
            file_put_contents (CONFIG_PATH . '/BotConfig.php', $config);
            
            /** 管理员密码 */
            if ($_POST['password'] != '') {
                $systemModel->password ($_POST['password']);
            }
            
            /** 返回 */
            exit (json_encode (array ('code' => 0)));
        }
        function setWebhook () {
            /** 检查 */
            if (empty ($_POST['botToken'])) {
                exit (json_encode (array ('code' => -9999, 'msg' => '参数为空')));
            }
            
            /** 初始化 */
            $telegramModel = new TelegramModel($_POST['botToken']);
            $newurl = 'https://' . $_SERVER['SERVER_NAME'] . APP_URL . '/index.php/Callback';
            
            /** 设置回调 */
            $ret = $telegramModel->setWebhook ($newurl);
            
            /** 返回 */
            if ($ret['ok'] == true) {
                exit (json_encode (array ('code' => 0)));
            } else {
                exit (json_encode (array ('code' => -1, 'msg' => $ret['description'])));
            }
        }
        function getUsername () {
            /** 检查 */
            if (empty ($_POST['botToken'])) {
                exit (json_encode (array ('code' => -9999, 'msg' => '参数为空')));
            }
            
            /** 初始化 */
            $telegramModel = new TelegramModel($_POST['botToken']);
            
            /** 设置回调 */
            $ret = $telegramModel->getMe ();
            
            /** 返回 */
            if ($ret['ok'] == true) {
                exit (json_encode (array ('code' => 0, 'username' => $ret['result']['username'])));
            } else {
                exit (json_encode (array ('code' => -1, 'msg' => $ret['description'])));
            }
        }
    }
