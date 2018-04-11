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
require_once __DIR__ . '/../src/LINEBot/Response.php';



$channelAccessToken = getenv('LINE_CHANNEL_ACCESSTOKEN');
$channelSecret = getenv('LINE_CHANNEL_SECRET');




$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                	$m_message = $message['text'];
                	$source=$event['source'];
              	      	$type = $source['type']; 
              	      	$id=$source['userId'];
                  	$roomid=$source['roomId'];
             	       	$groupid=$source['groupId'];
			date_default_timezone_set('Asia/Taipei');
			    
			    $debugmsg='123456';
			   
			   
			
			    $mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306"); 
			    
			    if(mysqli_connect_errno()){ $debugmsg='資料庫連線失敗'; //資料庫連線失敗
				}else{
				        mysqli_query($mysqli,"SET NAMES 'utf8'");
					
			    }
	    
			    
			    

			    
                       		 $mysqli->query("Insert INTO test (msg) values ('$m_message')");//成功會回傳 object 失敗則回傳 null
				
            
			   $in='進';
			    $out='出';
			 
			    
                	if(preg_match("/$in/","$m_message"))
                	{
				$result = $mysqli->query("SELECT Q FROM test");//成功會回傳 object 失敗則回傳 null
				if($result==null){ /* 資料庫語法錯誤 */
					//$this->console_log .='資料庫語法錯誤';
					//$this->console_log .='語法錯誤>'.(string)$mysqli->errno.(string)$mysqli->error.'<';
				}else{
					//$this->console_log .='更改筆數>'.(int)$mysqli->affected_rows.'<';
					//$this->console_log .='插入流水號>'.(int)$mysqli->insert_id.'<';
					//$this->console_log .='欄位數量>'.(int)$result->field_count.'<';
					//$this->console_log .='資料筆數>'.(int)$result->num_rows.'<';
					
					if((int)$result->num_rows==0){//沒有值會錯誤
						
					}else{
						while ($row = $result->fetch_assoc() )
						{
							$Q=$row['Q'];
						}	
							
						
						$result->close();
					}
					
				}
				
                		$client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                //'text' => $m_message ."\n" . $roomid."\n". date('Y-m-d h:i:sa') . "\n" . $id . "\n" . $groupid
				 'text' => $Q
				 
                            )	
                        )
                    	));			
                	}else if(preg_match("/$out/","$m_message")){
				$result = $mysqli->query("SELECT msg FROM test");//成功會回傳 object 失敗則回傳 null
				if($result==null){ /* 資料庫語法錯誤 */
					//$this->console_log .='資料庫語法錯誤';
					//$this->console_log .='語法錯誤>'.(string)$mysqli->errno.(string)$mysqli->error.'<';
				}else{
					//$this->console_log .='更改筆數>'.(int)$mysqli->affected_rows.'<';
					//$this->console_log .='插入流水號>'.(int)$mysqli->insert_id.'<';
					//$this->console_log .='欄位數量>'.(int)$result->field_count.'<';
					//$this->console_log .='資料筆數>'.(int)$result->num_rows.'<';
					
					if((int)$result->num_rows==0){//沒有值會錯誤
						
					}else{
						while ($row = $result->fetch_assoc() )
						{
						$msg=$row['msg'];
						$client->replyMessage(array(
						'replyToken' => $event['replyToken'],
						'messages' => array(
                           
						array(
					        'type' => 'text',
						//'text' => $m_message ."\n" . $roomid."\n". date('Y-m-d h:i:sa') . "\n" . $id . "\n" . $groupid
				                'text' => $msg
							
						}
						
						$result->close();
					}
					
				}
				
                		$client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                //'text' => $m_message ."\n" . $roomid."\n". date('Y-m-d h:i:sa') . "\n" . $id . "\n" . $groupid
				 'text' => $msg
				 
                            )	
                        )
                    	))  ;		
			}   else {
				$client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
            array(
                'type' => 'template', // 訊息類型 (模板)
                'altText' => 'Example buttons template', // 替代文字
                'template' => array(
                    'type' => 'buttons', // 類型 (按鈕)
                    'thumbnailImageUrl' => 'https://api.reh.tw/line/bot/example/assets/images/example.jpg', // 圖片網址 <不一定需要>
                    'title' => '操作選單', // 標題 <不一定需要>
                    'text' => '請選擇下列選單', // 文字
                    'actions' => array(
                        array(
                            'type' => 'message', // 類型 (回傳)
                            'label' => '進', // 標籤 1
                            'text' => '進' // 資料
                        ),
                        array(
                            'type' => 'message', // 類型 (訊息)
                            'label' => '出', // 標籤 2
                            'text' => '出' // 用戶發送文字
                        ),
                        array(
                            'type' => 'uri', // 類型 (連結)
                            'label' => '查詢紀錄', // 標籤 3
                            'uri' => 'http://d10419103.comeze.com/show.php' // 連結網址
                        )
                    )
                )
            )
        )
                    	));	
			}
                    break;
                    
                    
                    case 'location' :
			$m_message = $message['address'];
                	if($m_message!="")
                	{
                		$client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $m_message
                            ),
                        ),
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
?>
