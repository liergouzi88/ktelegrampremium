<?php
/*
-----BEGIN INFO-----
@PluginName 会员机器人
@Author 会员机器人
@Version 1.0
@Description 代开会员处理机器人
@PluginURL 
@AuthorURL 
@UIarea 640px,640px
-----END INFO-----
*/

class kaivip extends Base {
    private $data = array ();
    private $MemcacheModel=null;
    public function __construct ($data) {
        $this->data = $data;
        
        $this->MemcacheModel=new MemcacheModel();
        parent::__construct ();
    }
    public function init ($func, $from, $chat, $date) {
        if($chat['type']=="private"){
       if(!$this->db->has ('user_money', ['userid' => $from['id']])){
             $this->db->insert('user_money',['userid' => $from['id'],'money' =>0,'ljmoney' =>0,'trxadd' =>0]);   
            }
        }
        
        
       
    }
    
    public function command ($command, $param, $message_id, $from, $chat, $date) {
        //
        if($command=="/start"){
            if($chat['type']=="private"){
              $this->telegram->sendPhoto($chat['id'],"https://img1.imgtp.com/2023/04/25/KvnPEf7O.jpg"); // $this->telegram->sendMessage ($chat['id'], '24小时 会员代开');
              
              
              $button = json_encode(
          array(
            'keyboard' => array(
              array(
                array(
                  'text' => '购买会员',
                ),array(
                  'text' => '购买须知',
                ),array(
                  'text' => '订单管理',
                )
              ),
              array(
                array(
                  'text' => '充值',
                ),array(
                  'text' => '余额',
                ),array(
                  'text' => '联系客服',
                )
              ),
             
            ),
            'one_time_keyboard' => true,
            'resize_keyboard' => true,
            'input_field_placeholder' => "请在下发点击您需要的按钮",
            'selective' => true,
          )
        );

        $this->telegram->KeyboardButton($chat['id'], "24小时 会员代开", $button);
              
            }
            
        }
        
        
        
        //https://img1.imgtp.com/2023/04/25/KvnPEf7O.jpg
    }
    
    public function message ($message, $message_id, $from, $chat, $date) {
         if($chat['type']=="private"){
             if($message=="购买须知"){
                 
                  $this->telegram->sendMessage ($from['id'], '购买须知
1:【仲裁】收到会员充值成功，仔细检查，有未到账或者到账会员时间不对的，申请仲裁
2:【账号id】id错误或者id在未到账之前更改，不予退回！输入时最好带上@，直接复制id即可
3:【kk会员】kk会员没有提现功能，充值前请注意
4:【确认收货】未确认收货24小时自动确认收货
5:【会员】已有会员的账号无法进行二次充值！');
return true;
             }
             if($message=="订单管理"){
                 
                  	$buttonr = json_encode(
							array(
								'inline_keyboard' => array(
									array(
										array(
											'text' => '进行中',
											'callback_data' => 'oererxkkaihesoftk 1'
										),
										array(
											'text' => '成功订单',
											'callback_data' => 'oererxkkaihesoftk 2'
										)
									)


								)
							)
						);
						$this->telegram->sendMessage($chat['id'], $message, $message_id, $buttonr, 'Markdown');
						return true;
             }
             if($message=="联系客服"){
                 
                  $this->telegram->sendMessage ($from['id'], '@zf789');
return true;
             }
             
             
             if($message=="余额"){
               $user=  $this->db->select ('user_money',"*",['userid' => $from['id']]);
                 $smg="【userid】".$from['id']."
【账户余额】{$user[0]['money']}U
【累计充值】{$user[0]['ljmoney']}U";
                  $this->telegram->sendMessage ($from['id'],$smg);
return true;
             }
             
             
              if($message=="充值"){
               $user=  $this->db->select ('user_money',"*",['userid' => $from['id']]);
               if(empty($user[0]['trxadd'])){
                   $this->telegram->sendMessage ($from['id'],"请先绑定您的trc20 钱包地址 ,不会请找客服"); 
                   return true;
               }
               
               
                 $smg="请使用[{$user[0]['trxadd']}] 转账

充值地址USDT-TRC20（点击自动复制）：
<code>".TRXADD."</code>

注意事项：
 👉请使用您绑定的地址转账，其他地址将不会到账
 👉请使用您绑定的地址转账，其他地址将不会到账
 👉充值一般1分钟内到账，充值成功后会有到账通知";
                  $this->telegram->sendMessage ($from['id'],$smg);
return true;
             }
              if($message=="绑定钱包"){
                 $smg="请输入 绑定钱包+您的trc20地址 
例如您的地址为 Txxxxxxxxx
请您发送 绑定钱包Txxxxxxxxx";
                  $this->telegram->sendMessage ($from['id'],$smg);
                    return true;
             }
             
             if (substr($message, 0, 12) == "绑定钱包") {
                 	$callbackExplode = explode('绑定钱包', $message);
                 	$add=$callbackExplode[1];
                if (preg_match('/^T(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/', $add)) {
                   //查询地址是否存在
                    $user=  $this->db->select ('user_money',"*",['trxadd' => $add]);
                    if(!empty($user[0])){
                       $this->telegram->sendMessage ($from['id'],"地址已被其他人员绑定  请联系客服！");  
                    }else{
                        $this->db->update('user_money',['trxadd' =>$add],['userid' => $from['id']]); 
                         $user=  $this->db->select ('user_money',"*",['userid' => $from['id']]);
                       $this->telegram->sendMessage ($from['id'],"地址处理成功了 您的地址[ ".$user[0]['trxadd']."]"); 
                    }
                   
                   
                   
                } else {
                   $this->telegram->sendMessage ($from['id'],"地址不对哟！");
                }

                 
                 
             }
             
             
             
             //联系客服
             if($message=="购买会员"){
                 	$button = json_encode(
						array(
							'inline_keyboard' => array(
								array(
									array(
										'text' => '3 个月 售价 10U',
										'callback_data' => 'goumaisangeyuehunyu 3 8 0' 
									)
									
								),array(
									array(
										'text' => '6 个月 售价 13U',
										'callback_data' => 'goumaisangeyuehunyu 6 11 1' 
									)
									
								),array(
									array(
										'text' => '12 个月 售价 21U',
										'callback_data' => 'goumaisangeyuehunyu 12 19 2' 
									)
									
								)
								
								
							)
						)
					);
					
                $this->telegram->sendMessage($chat['id'], "请选择需要的套餐", "", $button, 'Markdown', true);
                  //$this->telegram->sendMessage ($from['id'],$smg);
                    return true;
             }
             
            
            if (preg_match('/^@[a-zA-Z0-9]+$/', $message)) {
                echo "第一个字符是@并且输入内容为英文和数字";
                //写入 消费记录  userid 提交内容  扣费金额 
               $goumaitaocan=  $this->MemcacheModel->get("goumaitaocan".$from['id']);
                if(!empty($goumaitaocan)){
                     $arr = explode("_", $goumaitaocan);
                     $yue=$arr[0];
                     $money=$arr[1];
                     $tcid=$arr[2];
                    //调用接口 查询是否存在会员 
                     
                    $ym = json_decode(file_get_contents("http://api.zy5d.com/api/getInfo/?id={$message}"),true);
                     //var_dump($ym ["response"]["User"],);
                     if(!empty($ym ["response"]["User"])){
                        $useinf= $ym ["response"]["User"];
                        //var_dump($useinf);
                        if(isset($useinf["premium"])){
                           if($useinf["premium"]==true){
                               $this->telegram->sendMessage ($from['id'], "不支持有会员的用户下单 请到期后在下单"); 
                             	return true;  
                           } 
                        } 
                         
                          $user=  $this->db->select ('user_money',"*",['userid' => $from['id']]);
                     $xinmoney=$user[0]['money']-$money;
                     if($xinmoney>0){
                         $this->db->update('user_money',['money' =>$xinmoney],['userid' => $from['id']]);
                        $this->db->insert('user_huiyuanodder',['userid' => $from['id'],'money' =>$money,'username' =>$message,'certime' =>time(),'start' =>1,'tancan' =>$yue]);
                   $this->telegram->sendMessage ($from['id'], "充值订单已提交，请耐心等待,本次消费{$money}U"); 
                   // 他提交到 api 机器人处理订购单
                   
                   //http://154.31.25.20/api/session/bottonon/?data[peer]=@kkhuiyuanbot&data[id]=365&data[tancan]=2&data[username]=@zf789
                   
                   file_get_contents("http://154.31.25.20/api/session/bottonon/?data[peer]=@kkhuiyuanbot&data[id]=365&data[tancan]=".$tcid."&data[username]=".$message);
                   
                    $this->MemcacheModel->del("goumaitaocan".$from['id']); 
                     }else{
                          $this->telegram->sendMessage ($from['id'], "余额不足 提交失败"); 
                       $this->MemcacheModel->del("goumaitaocan".$from['id']);  
                     }
                         
                         
                     }else{
                          $this->telegram->sendMessage ($from['id'], "请不要 提供 群或者是频道的用户名 或者是不正确的用户名"); 
                          	return true;  
                     }
                     
                     
                    
                    
                     
                     
                      
                }
               
           

                
                
                   
                
            } else {    
                //echo "不符合要求";
            }
             
             
         }
        
       // $this->telegram->sendMessage ($from['id'],  $this->MemcacheModel->get("goumia"));
    }
    
    public function sticker ($sticker, $message_id, $from, $chat, $date) {

    }
    public function photo ($photo, $caption, $message_id, $from, $chat, $date) {

    }
    
    public function callback_query ($callback_data, $callback_id, $callback_from, $message_id, $from, $chat, $date) {
        
         if($chat['type']=="private"){
           $callbackExplode = explode(' ', $callback_data);
	    	$tou = $callbackExplode['0']; 
	    	if($tou=="goumaisangeyuehunyu"){
	    	   $yue=  $callbackExplode['1']; 
	    	   $money=  $callbackExplode['2']; 
	    	   $tcid=  $callbackExplode['3']; 
	    	   $msg="注意：账号一定要保证正确！不要输入手机号以及用户名，复制id【带@的那个】发送即可，会员到账之前不要更改id，否则损失自行承担
请输入要充值的账号,多个账号之间用空格隔开，比如
 xxx xxx";
  $this->MemcacheModel->set("goumaitaocan".$callback_from['id'],$yue."_".$money."_".$tcid,600);
  $this->telegram->sendMessage($chat['id'], $msg);
	    	}
	    	
	    	
	    	if($tou=="oererxkkaihesoftk"){
	    	   	$start = $callbackExplode['1']; 
	    	   
	    	   $user_huiyuanodder= $this->db->select('user_huiyuanodder',"*",['userid' => $callback_from['id'],'start' => $start]);
	    	   if(empty($user_huiyuanodder[0])){
	    	      $msg="暂时没有 相关订单哟！";
	    	      $buttonr = json_encode(
							array(
								'inline_keyboard' => array(
									array(
										array(
											'text' => '进行中',
											'callback_data' => 'oererxkkaihesoftk 1'
										),
										array(
											'text' => '成功订单',
											'callback_data' => 'oererxkkaihesoftk 2'
										)
									)


								)
							)
						);
	    	      	$this->telegram->editMessage($chat['id'], $message_id, $msg, $buttonr, 'Markdown', true);
	    	      	return true;
	    	   }else{
	    	       //开始 处理  
	    	      
	    	       $msg="显示最近的5条信息"."\r\n"; $buttonr = json_encode(
							array(
								'inline_keyboard' => array(
									array(
										array(
											'text' => '进行中',
											'callback_data' => 'oererxkkaihesoftk 1'
										),
										array(
											'text' => '成功订单',
											'callback_data' => 'oererxkkaihesoftk 2'
										)
									)


								)
							)
						);
	    	       foreach ($user_huiyuanodder as $key => $value) {
	    	           // code...
	    	           $msg.="[{$key}]  ".$value['username']."  ".$value['money']."\r\n";
	    	          
	    	      	
	    	       }
	    	      
	    	       $this->telegram->editMessage($chat['id'], $message_id, $msg, $buttonr);
	    	       	return true;
	    	   }
	    	   
	    	   
	    	}
	    	
	    //oererxkkaihesoftk
	    	
	    	
         }
        
       
        
        
        

    }
    
    public function inline_query ($query, $offset, $inline_id, $from) {

    }
    
    public function new_member ($new_member, $message_id, $from, $chat, $date) {

    }
        
    public function left_member ($left_member, $message_id, $from, $chat, $date) {

    }
    
    public function new_chat_title ($new_chat_title, $message_id, $from, $chat, $date) {

    }
    
    public function reply_to_message ($reply_msg, $reply_id, $reply_date, $orig_msg, $orig_id, $orig_date, $from, $chat, $is_bot) {

    }

    public function install () {

    }
    
    public function uninstall () {

    }
    
    public function enable () {

    }
    
    public function disable () {

    }

    public function settings () {
        include_once "settings.html";
    }

    //保存前端设置
    public function saveSettings () {
        //强行演示:)
        exit (json_encode (array ("code" => 0, "msg" => "喵:" . http_build_query($_POST))));

        foreach($_POST as $k => $v){
            if(!in_array($k,["pcn","method"])){
                $this->option->iou($k,$v);
            }
        }
        exit (json_encode (array ("code" => 0, "msg" => "保存成功")));
    }

}
