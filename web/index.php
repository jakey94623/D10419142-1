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
require_once __DIR__ . '/../src/LINEBot.php';
require_once __DIR__ . '/../src/LINEBot/Response.php';
require_once __DIR__ . '/../src/LINEBot/HTTPClient.php';
require_once __DIR__ . '/../src/LINEBot/MessageBuilder.php';
require_once __DIR__ . '/../src/LINEBot/TemplateActionBuilder.php';
require_once __DIR__ . '/../src/LINEBot/Constant/Meta.php';
require_once __DIR__ . '/../src/LINEBot/Constant/MessageType.php';
require_once __DIR__ . '/../src/LINEBot/Constant/ActionType.php';
require_once __DIR__ . '/../src/LINEBot/Constant/TemplateType.php';
require_once __DIR__ . '/../src/LINEBot/HTTPClient/Curl.php';
require_once __DIR__ . '/../src/LINEBot/HTTPClient/CurlHTTPClient.php';
require_once __DIR__ . '/../src/LINEBot/TemplateActionBuilder.php';
require_once __DIR__ . '/../src/LINEBot/TemplateActionBuilder/PostbackTemplateActionBuilder.php';
require_once __DIR__ . '/../src/LINEBot/MessageBuilder/TemplateBuilder.php';
require_once __DIR__ . '/../src/LINEBot/MessageBuilder/LocationMessageBuilder.php';
require_once __DIR__ . '/../src/LINEBot/MessageBuilder/TemplateBuilder/ConfirmTemplateBuilder.php';
require_once __DIR__ . '/../src/LINEBot/MessageBuilder/TextMessageBuilder.php';
require_once __DIR__ . '/../src/LINEBot/MessageBuilder/TemplateMessageBuilder.php';
$channelAccessToken = getenv('LINE_CHANNEL_ACCESSTOKEN');
$channelSecret = getenv('LINE_CHANNEL_SECRET');
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($channelAccessToken);
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                 case 'location':
                    $replyToken=$event['replyToken'];
                    $m_message = $message['text']; 
	            $source=$event['source']; 
		    $idtype = $source['type'];  
		    $userid=$source['userId'];
                    $roomid=$source['roomId']; 
		    $groupid=$source['groupId'];
		    $type=$message['type'];
                    $res = $bot->getProfile($userid); 
		    $profile = $res->getJSONDecodedBody();
		    $displayName = $profile['displayName'];
		    $address=$message['address']; 
		    $title=$message['title'];
                    $longitude=$message['longitude']; 
	            $latitude=$message['latitude']; 
                    date_default_timezone_set('Asia/Taipei');$time=date("Y-m-d H:i:s");
			    
			    
		    if($address!="" && $longitude>=121.5651 && $longitude<=121.5654 && $latitude>=25.0865 && $latitude<=25.0868){
			$mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306");
			$sql = "select worktype from test where location='' and longitude='' and latitude='' and userid='$userid'";
			$result = $mysqli->query($sql);
			while($row = $result->fetch_array(MYSQLI_BOTH)) {
  			$worktype = $row['worktype'] ;
			}	
			if($worktype!=""){
			    $mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306");
			    $sql = "UPDATE test SET location='$address',longitude='$longitude',latitude='$latitude' where name='$displayName' and worktype!=''and userid='$userid';";
			    $result = $mysqli->query($sql);
				$client->replyMessage(array(
        				'replyToken' => $event['replyToken'],
     			   		'messages' => array(
				   	array(
                                          'type' => 'text',
                                          'text' => "定位成功!!"
                                       	),
 				)));
			$sql="SELECT worktype from mysql where userid='$userid'";
			$result = $mysqli->query($sql);
			while($row = $result->fetch_array(MYSQLI_BOTH)) {
  				$worktype = $row['worktype'] ;
 			 }
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($displayName." ".$worktype);
		    	$response = $bot->pushMessage('R8466f385da9bd8eac6fb509622c0a892', $textMessageBuilder);
			}
		    else{
			$mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306");
			$sql="SELECT number from test";
			$result = $mysqli->query($sql);
			while($row = $result->fetch_array(MYSQLI_BOTH)) {
  				$number = $row['number'] ;
 			 }
			$number=$number+1;
			$sql="INSERT INTO test (number,name,userid,worktime,location,longitude,latitude) VALUES ('$number','$displayName','$userid','$time','$address','$longitude','$latitude')";
			$result = $mysqli->query($sql);
			sleep(3);    
			$sql="SELECT name from test where worktype=''";
			$result = $mysqli->query($sql);
			while($row = $result->fetch_array(MYSQLI_BOTH)) {
  				$name = $row['name'] ;
 			 }
			if($name!=""){
				$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請按進出按鈕");
		    		$response = $bot->pushMessage($userid, $textMessageBuilder);
			}
		    } 
		 }
		break;   
		    case 'text':
                	$m_message = $message['text'];
                	$source=$event['source'];
              	      	$type = $source['type']; 
              	      	$id=$source['userId'];
                  	$roomid=$source['roomId'];
             	       	$groupid=$source['groupId'];
			date_default_timezone_set('Asia/Taipei');
			   
			    $mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306"); 
			    
			    if(mysqli_connect_errno()){ $debugmsg='資料庫連線失敗'; //資料庫連線失敗
				}else{
				        mysqli_query($mysqli,"SET NAMES 'utf8'");
					
			    }
	    
			    
			    
			    
                       		 //$mysqli->query("Insert INTO test (msg) values ('$m_message')");//成功會回傳 object 失敗則回傳 null
				
            
			   $in='進';
			    $out='出';
			 
			    
                	if(preg_match("/$in/","$m_message"))
                	{
			$mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306");
			$sql = "SELECT location from test where worktype='' and userid='$userid'";
			$result = $mysqli->query($sql);
			while($row = $result->fetch_array(MYSQLI_BOTH)) {
  			$location = $row['location'] ;
			}
			if($location!=""){
			$client->replyMessage(array(
			'replyToken' => $event['replyToken'],
     			   'messages' => array(
			     array(
                                          'type' => 'text',
                                          'text' => "歡迎你的到來!!" . "\n" . "祝你使用愉快!!"
                                   ),
 	       		)));
			$mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306");
			$sql = "UPDATE test SET worktype='進' where name='$displayName' and worktype=' '";
			$result = $mysqli->query($sql);
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($displayName." "."進");
		    	$response = $bot->pushMessage('R8466f385da9bd8eac6fb509622c0a892', $textMessageBuilder);
			}
			else{
			$mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306");
			$sql="SELECT number from test";
			$result = $mysqli->query($sql);
			while($row = $result->fetch_array(MYSQLI_BOTH)) {
  				$number = $row['number'] ;
 			 }
			$number=$number+1;
			$sql="INSERT INTO test (number,name,userid,worktime,worktype) VALUES ('$number','$displayName','$userid','$time','進')";
			$result = $mysqli->query($sql);
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請定位你的位置");
		    	$response = $bot->pushMessage($userid, $textMessageBuilder);
			}
		    }
			    else if(preg_match("/$out/","$m_message")){
				$mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306");
			$sql = "SELECT location from test where worktype='' and userid='$userid'";
			$result = $mysqli->query($sql);
			while($row = $result->fetch_array(MYSQLI_BOTH)) {
  			$location = $row['location'] ;
			}
			if($location!=""){
			$client->replyMessage(array(
        		'replyToken' => $event['replyToken'],
     			   'messages' => array(
			     array(
                                          'type' => 'text',
                                          'text' => "歡迎你的到來!!" . "\n" . "祝你使用愉快!!"
                                   ),
 	       		)));
			$mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306");
			$sql = "UPDATE test SET worktype='出' where name='$displayName' and worktype=' '";
			$result = $mysqli->query($sql);
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($displayName." "."出");
		    	//$response = $bot->pushMessage('R8466f385da9bd8eac6fb509622c0a892', $textMessageBuilder);
			}
			else{
			$mysqli = new mysqli('e764qqay0xlsc4cz.cbetxkdyhwsb.us-east-1.rds.amazonaws.com', "lptrv8w6oc62hrpr", "iagiyml96j33de6q", "ifz67f5o6szf2gdu","3306");
			$sql="SELECT number from test";
			$result = $mysqli->query($sql);
			while($row = $result->fetch_array(MYSQLI_BOTH)) {
  				$number = $row['number'] ;
 			 }
			$number=$number+1;
			$sql="INSERT INTO test (number,name,userid,worktime,worktype) VALUES ('$number','$displayName','$userid','$time','出')";
			$result = $mysqli->query($sql);
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder("請定位你的位置");
		    	$response = $bot->pushMessage($userid, $textMessageBuilder);
			}	
			}   
			    
			    else {
				$client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
            array(
                'type' => 'template', // 訊息類型 (模板)
                'altText' => 'Example buttons template', // 替代文字
                'template' => array(
                    'type' => 'buttons', // 類型 (按鈕)
                    'thumbnailImageUrl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/13/Takming_University_of_Science_and_Technology_logo.svg/600px-Takming_University_of_Science_and_Technology_logo.svg.png', // 圖片網址 <不一定需要>
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
