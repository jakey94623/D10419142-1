<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');
 


$channelAccessToken = getenv('LINE_CHANNEL_ACCESSTOKEN');
$channelSecret = getenv('LINE_CHANNEL_SECRET');

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
       $hostname = 'localhost';
       $username = 'test';
       $password = '54105410';
       $db_name = "linebot";
try{
    $db=new PDO("mysql:host=".$hostname.";
                dbname=".$db_name, $username, $password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                //PDO::MYSQL_ATTR_INIT_COMMAND 設定編碼
                
    //echo '連線成功';
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); //錯誤訊息提醒
    
    //Query SQL
    $sql="Select * from act where num=3";
    $result=$db->query($sql);    
    while($row=$result->fetch(PDO::FETCH_OBJ)){    
        //PDO::FETCH_OBJ 指定取出資料的型態
        echo $row->num."\n";  
        echo $row->cn_title."\n";   
    }
 
}
     catch(PDOException $e){
    //error message
    echo $e->getMessage(); 
}        
              
              
              
              
              
              
              
              
              
              
              
                	$m_message = $message['text'];
                    if($m_message == "A"){
                         $client->replyMessage(array(
                             'replyToken' => $event['replyToken'],
                             'messages' => array(
                             array(
                                   'type' => 'text',
                                   'text' => $sql
                               )
                            )
                        	));
                    }
                    break;
                
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};
