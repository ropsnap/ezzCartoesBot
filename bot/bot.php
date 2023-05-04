<?php


 set_time_limit(10000000);


include("./resource/fun.php");
include("./resource/adm/home.php");
include("./resource/cliente/home.php");
		
function messages($message){
	$token = file_get_contents('./token.txt');
	$confibot3 = file_get_contents('./resource/conf.json');
	$confibot = json_decode($confibot3, true);
	try{
			// salve novos usuários !


		salve($message);
		$chat_id = $message["chat"]["id"];
		$from_id = $message["from"]["id"];

		$text = strtolower($message['text']);
		preg_match_all('/[a-z-A-Z-0-9]*+/', $text, $args);
		$args = array_values(array_filter($args[0]));
		$cmd = $args[0];


		// carrega usuários 
		$users = json_decode(file_get_contents("./usuarios.json"),true);
		$confi = json_decode(file_get_contents("./resource/conf.json"),true);

		if (!$users[$chat_id]){
				return false;
		}

		$key = ["admin","menu" , "getsemlevel", "addcc" , 'addmix' , 'price' , 'mixprice' , 'getnivel' ,'getuser' ,"editnivel" , 'addsaldo' , 'resaldo' , 'getsaldo' , 'gGift' , "ggift", "users" , "setwelcome" , "addadmin" , "delladm" , 'showadms' , 'send'];
		//bot("sendMessage",array("chat_id"=> $chat_id, "text" => $users));
		if (in_array($cmd, $key) and $users[$chat_id]["adm"] == "true" || $confi['dono'] == $from_id){
			adm($message);
			die;
		}else{
			if ($confibot['manutencao'] == "false" ){
				clientes($message);
			}else{
				bot("sendMessage",array("chat_id"=> $chat_id , "text" => $confi.text_maintain_mode,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
			}
		}



    
        
	}catch (Throwable $t){
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$t.'');
	}catch (Exception $e){
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$e.'');
	}

}

/*
	confis
*/


function bot($method, $parameters) {

	$token = file_get_contents('./token.txt');
	$confibot = file_get_contents('./confi.json');
	$confibot = json_decode($confibot, true);
	try{
	  $options = array(
			 'http' => array(
			 'method'  => 'POST',
			 'content' => json_encode($parameters),
			 'header'=>  "Content-Type: application/json\r\n" .
	            "Accept: application/json\r\n"
			 )
			);

		$context  = stream_context_create( $options );
		return file_get_contents('https://api.telegram.org/bot'.$token.'/'.$method, false, $context );
	}catch (Throwable $t){
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$t.'');
	}catch (Exception $e){
		
		file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?chat_id='.$confibot['dono'].'&text='.$e.'');
	}
}

function senDocs($method,$params){

	$name = $params['file'];
	$token = file_get_contents('./token.txt');
	$bot_url  = "https://api.telegram.org/bot$token/";
	$url = $bot_url . $method ;
	$t = $params['type'];
	$params[$t]  = new CURLFile(realpath($name)) ;
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type:multipart/form-data"
	));
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
	return curl_exec($ch);
}


$update_response = file_get_contents("php://input");
$update = json_decode($update_response, true);


if (isset($update["message"])) {
	messages($update["message"]);
}


if (isset($update["callback_query"])) {
    query($update["callback_query"]);
}
