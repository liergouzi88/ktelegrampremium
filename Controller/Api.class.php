<?php
    class Api extends Base {
         public function __construct () {
             $this->telegram = new TelegramModel;
             $this->MemcacheModel=new MemcacheModel();
           parent::__construct ();
        }
        
        
        function tz () {
           
            
            
     
         //'uid','umoney','tcname','start','username','orderid'
         
         $username=$_REQUEST['username'];
         if($_REQUEST['uid'] !=pduid){
             exit("fuck");
         }
         
         
         
            $user_huiyuanodder= $this->db->select('user_huiyuanodder',"*",['username' => $username,'start' => 1]);
           
            if(!empty($user_huiyuanodder[0]['id'])){
               
                 if(count($user_huiyuanodder)==1){
                 $this->db->update('user_huiyuanodder',['start' => 2],['id' => $user_huiyuanodder[0]['id']]); 
               $tzid=$user_huiyuanodder[0]['userid'];
               $yue=$user_huiyuanodder[0]['tancan'];
               $username=$user_huiyuanodder[0]['username'];
                $msg="您提交的【{$username}】会员充值 ".$yue."月 已处理成功！ 如果没到 请联系 客服";
               $this->telegram ->sendMessage($tzid, $msg);
            }else{
                //通知 
                $this->telegram ->sendMessage(MASTER, $username."处理成功了 但是没有找到顶订单"); 
                
            }
            }
            
           
            
            
            
        }
        
        public function Queryamountchange()
          {
         $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://api.trongrid.io/v1/accounts/".trxadd."/transactions/trc20?only_to=true&limit=10&contract_address=TR7NHqjeKQxGTCi8q8ZY4pL8otSzgjLj6t",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => [
            "Accept: application/json"
          ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);


        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $hui = json_decode($response, true);
        }
        
        
          
        foreach ($hui["data"] as $key => $value) {
          
           
            $hash = $value['transaction_id'];
            $timestamp = $value['block_timestamp'] / 1000 + 200;
            
            if ($timestamp > time()) {
                //  处理过的 写入缓存  过期即可  不处理  
                $chuliguo =$this->MemcacheModel->get("chuliguodehaxi" . $hash);
                if (!$chuliguo) {
                    $owner_address = $value['from'];
                  
                    $to_address = $value['to'];
                    $amount = $value['value'] / 1000000;

                    if ($amount > 1) {
                        //查询 用户地址  对应的用户  
                       
                       $user= $this->db->select('user_money',"*",['trxadd' => $owner_address]);
                       
                        
                        if (isset($user[0]["id"])) {
                           
                           $xinm=$user[0]['money']+$amount;
                           
                           $this->db->update('user_money',['money' =>$xinm],['trxadd' => $owner_address]); 
                           //通知用户
                           
                           
                            $this->MemcacheModel->set("chuliguodehaxi" . $hash, 1, 300);
                             $this->telegram ->sendMessage($user[0]['userid'], $username."您充值的【".$amount."】已经才处理成功了"); 
                        }

                        //  var_dump($owner_address,$to_address,$amount,$timestamp,$hash,$hot); 
                    }
                } else {
                    echo "已处理";
                }
            }


            // code...




        }
    }
         function pay () {
             
             
             
          $this->Queryamountchange();
         }

       

        
    }
