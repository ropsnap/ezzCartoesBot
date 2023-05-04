<?php
set_time_limit(600);
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

$confibot = json_decode(file_get_contents('./resource/conf.json') , true);
$userssss = json_decode(file_get_contents("./usuarios.json"),true);

function PegarDados($string, $start, $end) {
	$str = explode($start, $string);
	$str = explode($end, $str[1]);
	return $str[0];
}

function clientes($message){
	$chat_id = $message["chat"]["id"];
	$from_id = $message["from"]["id"];

	$text = strtolower($message['text']);
	preg_match_all('/[a-z-A-Z-0-9]*+/', $text, $args);
	$args = array_values(array_filter($args[0]));
	$cmd = $args[0];

	atualizasaldo($chat_id);

	if ($cmd == 'start'){

		$nome = $message['from']['first_name'];
		$idwel = $message['from']['id'];
		$conf = json_decode(file_get_contents("./resource/conf.json") , true);
		//$saldobot = $userssss['saldo'];
		
        
		if ($conf['welcome'] != ""){
			$txt = $conf["welcome"];
        
			$txt = str_replace("{nome}", $nome, $txt);
			$txt = str_replace("{id}", $idwel, $txt);

		}else{
		    
			$txt = "*💲PREÇOS DAS CC'S DO BOT
💳STANDARD: R$6,00\n💳BUSINESS: R$10,00\n💳PLATINUM: R$10,00\n💳INFINITE: R$15,00\n💳BLACK: R$15,00\n💳ELO: R$7,00\n💳CORPORATE: R$15,00\n💳DISCOVER: R$7,00\n💳HIPERCARD: R$7,00\n💳PREPAID: R$3,00\n💳EXECUTIVE: 20,00\n💳WORLD: R$6,00\n💳INDEFINIDAS: R$8,00\n💳ELECTRON: R$7,00\n💳HIPER: R$7,00\n💳TITANIUM: R$15,00\n💳PERSONAL: R$10,00\n💳CLASSIC: R$5,00\n💳PURCHASING: R$15,00
🚀GRUPO: https://t.me/joinchat/tropadochaves\n\n📌 SEU ID: $idwel\n\n\n\n*";
		}
		

	 $menu =  ['inline_keyboard' => [


  [
  	['text'=> $conf["text_btn_cc_store"] , 'callback_data'=>"loja"]
  ],

  [
  	['text'=> $conf["text_btn_add_funds"] , 'callback_data'=>"comprasaldo"] , 
  	['text'=> $conf["text_btn_informations"] , 'callback_data'=>"menu_infos"]
  ],

  ]];
		bot("sendMessage",array("chat_id"=> $chat_id , "text" => $txt,"reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
	 
	}if (preg_match("/[0-9]{6}/", $message['text'])){

		buscabin($message);
		die;
	}

	if (preg_match("/[0-9-A-Z]{10}/", $message['text'],$cod)){

		usagift($message,$cod[0]);
		die();
	}

	if ($cmd == "country"){
		selectbase($message);
		die;
	}

	// bot("sendMessage" , array("chat_id" => $chat_id , "text" => $cmd));

		if ($cmd == 'recargapix1'){
				$nome = $message['from']['first_name'];
				$usernamepix = $message['from']['username'];
				$idwel = $message['from']['id'];
				//$iduserpix = $message['id'];
				$confibot = $GLOBALS['confibot'];
				
				$valor_pagamento = substr($message['text'],12);

				$saldopix = number_format($valor_pagamento, 1);
				$url = "https://".$_SERVER['SERVER_NAME']."/gerapix/index.php";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						"content-type: application/x-www-form-urlencoded",
						"accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,* /*;q=0.8,application/signed-exchange;v=b3;q=0.9"
					));
					curl_setopt($ch, CURLOPT_POSTFIELDS, 'chave=531cf66c-291b-4eaf-bcb2-726662e1f8b0&valor='.$saldopix.'&beneficiario=ISRAEL+BANKER&cidade=SAO+PAULO&descricao=CC+'.$usernamepix.'+'.$chat_id.'+&identificador=***');
					$r0 = curl_exec($ch);
					$chavepix = PegarDados($r0, 'copiar()">', '</');
							curl_close($ch);

							//bot("sendMessage" , array("chat_id" => $chat_id , "text" => $chavepix));
		if (empty($args[1])){
			die(bot("sendmessage",array("chat_id" => $chat_id , "text" => "user: /recargapix [valor] \no valor dever ser (Ex /recargapix 15, /recargapix 45.9 e assim por diante.)", "parse_mode" => "html")));
		}


			if ($saldopix < 5){
				bot("sendmessage",array("chat_id" => $chat_id , "text" => "<b>Adicione um valor a partir de R$5 !!</b>", "parse_mode" => "html"));
			
			}else if ($saldopix >= 5){
			
				$menupix =  ['inline_keyboard' => [
					[['text'=>"📎 ENVIAR COMPROVANTE 📎", "url" => "https://t.me/Suportechavonelis"] ,['text'=>"🚀 Chaves CC's", "url" => "https://t.me/joinchat/tropadochaves"]]
					,
				]];		
				bot("sendmessage",array("chat_id" => $chat_id , "text" => "☑ PIX COPIA E COLA GERADO COM SUCESSO! APÓS O PAGAMENTO ENVIE SEU COMPROVANTE E SEU ID NA OPÇÃO ABAIXO 'ENVIAR COMPROVANTE' PARA QUE SEU SALDO SEJA ADICIONADO!'\n\n💲️VALOR: $saldopix ️\n\n💎 SEU ID: $chat_id 💎\n\n\n<pre>$chavepix</pre>\n\n\n💴 DÊ UM CLIQUE NO PIX CÓPIA E COLA ACIMA PARA COPIAR E PAGAR NO APP DE SEU BANCO >3\n\n⬇️Confirme pagamento abaixo:⬇️", "reply_markup" =>$menupix ,"reply_to_message_id"=> $message['message_id'], "parse_mode" => "html"));
			}else{
				bot("sendmessage",array("chat_id" => $chat_id , "text" => "Error ao add saldo !!" , "parse_mode" => "html"));
			}
	}
}



function query($msg){
	

	$idquery = $msg['id'];
	$idfrom = $msg['from']['id'];
	$message = $msg['message'];
	$dataquery = $msg['data'];

	$userid = $msg['from']['id'];
	$userid2 = $msg['message']['reply_to_message']['from']['id'];
	$chatid = $msg['message']['chat']['id'];

	if ($userid != $userid2){
		bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Sem permissão!","show_alert"=>false,"cache_time" => 10));
		die();
	}

	if (explode("_", $dataquery)[0] == "volta"){
		$cmd = explode("_", $dataquery)[1];
		$cmd($message);

	}else if (explode("_", $dataquery)[0] == "compracc"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,$msg,explode("_", $dataquery)[1]);

	}else if (explode("_", $dataquery)[0] == "altercc"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,explode("_", $dataquery)[1],explode("_", $dataquery)[2],$msg);

	}else if (explode("_", $dataquery)[0] == "compramix"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,explode("_", $dataquery)[1],explode("_", $dataquery)[2],$msg);

	}else if (explode("_", $dataquery)[0] == "alterValue"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,$msg,explode("_", $dataquery)[1],explode("_", $dataquery)[2],explode("_", $dataquery)[3]);

	}else if (explode("_", $dataquery)[0] == "altermix"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,$msg,explode("_", $dataquery)[1],explode("_", $dataquery)[2],explode("_", $dataquery)[3]);

	}else if (explode("_", $dataquery)[0] == "comprasearch"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,explode("_", $dataquery)[1],explode("_", $dataquery)[2],$msg);

	}else if (explode("_", $dataquery)[0] == "altersaldoe"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,$msg,explode("_", $dataquery)[1],explode("_", $dataquery)[2]);

	}else if (explode("_", $dataquery)[0] == "users"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,$msg,explode("_", $dataquery)[1],explode("_", $dataquery)[2]);

	}else if (explode("_", $dataquery)[0] == "select"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,$msg,explode("_", $dataquery)[1]);

	}else if (explode("_", $dataquery)[0] == "viewcard"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,$msg,explode("_", $dataquery)[1],explode("_", $dataquery)[2]);

	}else if (explode("_", $dataquery)[0] == "altercard"){
		$cmd = explode("_", $dataquery)[0];
		$cmd($message,$msg,explode("_", $dataquery)[1],explode("_", $dataquery)[2],explode("_", $dataquery)[3],explode("_", $dataquery)[4]);

	}else if (explode("_", $dataquery)[0] == "compraccs"){
		$cmd = explode("_", $dataquery)[0];
		
		$cmd($message,$msg,explode("_", $dataquery)[1],explode("_", $dataquery)[2],explode("_", $dataquery)[3]);

	}else if (explode("_", $dataquery)[0] == "envia"){
		$cmd = explode("_", $dataquery)[0];
		
		$cmd($message,$msg,explode("_", $dataquery)[1] , explode("_", $dataquery)[2] , explode("_", $dataquery)[3] );

	}else{
		$dataquery($message);
	}
}




/*alter user*/


function users($message , $query , $type , $position){


	
	$chat_id = $message["chat"]["id"];
	$idquery = $query['id'];


	$users = json_decode(file_get_contents("./usuarios.json"),true);

	
	$chunk = array_chunk($users, 10);

	$tt = sizeof($chunk);


	if ($type == "prox"){
		if ($chunk[ $position + 1]){
			
			$postio4n = $position +1;
		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Acabou!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}else{
		if ($chunk[ $position - 1]){
			
			$postio4n = $position  - 1;

		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Acabou!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}

	$userss = $chunk[$postio4n];

	$indexs = array_chunk(array_keys($users), 10)[$postio4n];

	$t = sizeof($chunk);

	$d = $postio4n +1;

	$txt .= "<b>✨ LISTA DE USUARIOS DO BOT\n🍃mostrando: $d de $t</b>\n";
	foreach ($userss as $iduser => $value44) {

		$idcarteira = $indexs[$iduser];

		$nome = ($value44['nome'])? $value44['nome'] : "Sem Nome";

		$nome = str_replace(["</>" ], "", $nome);
		$saldo = ($value44['saldo']) ? $value44['saldo'] : 0;

		$dadta = (date("d/m/Y H:s:i" , $value44['cadastro']))? date("d/m/Y H:s:i" , $value44['cadastro']) : "Sem Data";

		$txt .= "\n🧰<b>Id da carteira:</b> {$idcarteira}\n";
		$txt .= "💎<b>Nome: </b>{$nome}\n";
		$txt .= "💰<b>Saldo: </b> {$saldo}\n";
		$txt .= "📅<b>Data Cadastro: </b> {$dadta}\n";

	}

	$menu =  ['inline_keyboard' => [

	[
		['text'=>"➡ Próxima Bin",'callback_data'=>"users_ant_{$postio4n}"] , ['text'=>"➡ Próxima Bin",'callback_data'=>"users_prox_{$postio4n}"]
	] ,[
		['text'=>"← Voltar",'callback_data'=>"menu"]
	]

	,]];

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => "html","reply_markup" =>$menu));




}


/*

envia msg para os users 

*/

function envia($message , $query , $opt , $postion ){
	$chat_id = $message["chat"]["id"];
	$dados = json_decode(file_get_contents("./usuarios.json") , true);
	$idquery = $query['id'];
	$msg = file_get_contents("./msgs.txt");

	$t = sizeof(array_chunk(array_keys($dados), 50));

	$json = array_chunk(array_keys($dados), 50)[$postion];
	if (!array_chunk(array_keys($dados), 50)[$postion]){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Todos os usuarios já receberam a mensagem!!!","show_alert"=> false,"cache_time" => 10)));
	}

	$tenviados = 0;
	$tnenviados = 0;
	$usersdell = [];

	$nenv = $postion +1;

	foreach ($json as $value) {

		$sendmessage = bot("sendMessage" , array("chat_id" => $value , "text" => $msg , "parse_mode" => "Markdown" ));

		if (!$sendmessage){
			
			if ($opt == "sim" || $opt == 'sim'){
				delluser($value);
				$usersdell[] = $value;
			}
			$tnenviados++;
		}else{
			$tenviados++;
		}

	}

	$usersap = implode(",", $usersdell);

	$txt .= "<b>✨ Enviando .. !</b>\n\n";
	$txt .= "<b>📩 Msg: {$msg}</b>\n\n";
	$txt .= "<b>🔝 Enviado {$nenv} de {$t} !</b>\n";
	$txt .= "<b>✅ Enviados: {$tenviados}!</b>\n";
	$txt .= "<b>❌ Nao Enviados: {$tnenviados} !</b>\n";
	$txt .= "<b>🗑 Users Apagados: {$usersap}!</b>\n";

	$postio4n = $position++;

	$menu =  ['inline_keyboard' => [
		[ 
			['text'=>"Continuar",'callback_data'=>"envia_{$opt}_{$postio4n}"]
		]
	,]];

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'html',"reply_markup" =>$menu));
}



/*

	perfil usuario !

*/


function menu_infos($message){
	$chat_id = $message["chat"]["id"];

	$historicocc = json_decode(file_get_contents("./ccsompradas.json") , true);
	$dados = json_decode(file_get_contents("./usuarios.json") , true);
	$historicosaldo = json_decode(file_get_contents("./salcocomprado.json") , true);
	
	$conf = json_decode(file_get_contents("./resource/conf.json") , true);

	$cliente = $dados[$chat_id];
 $menu =  ['inline_keyboard' => [[],]];







 $botoes[] = ['text'=>"← Voltar",'callback_data'=>"volta_menu"];






 $menu['inline_keyboard'] = array_chunk($botoes, 2);




















 $txt .= "Nome → {$cliente["nome"]} \n";
 $txt .= ($cliente['adm'] == "true") ? "Você é admin? Sim ✅ \n" : "Admin: Não 🚫 \n";
 $txt .= "Seu ID → $chat_id \n";
 $txt .= "Seu saldo → {$cliente["saldo"]} \n";







 bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown',"reply_markup" =>$menu));
}



/*
	ver saldo comprado!
*/


function saldocomprado($message){
	$saldocomprado = json_decode(file_get_contents("./salcocomprado.json") , true);

	$chat_id = $message["chat"]["id"];
	$b = [];
	$menu =  ['inline_keyboard' => [[],]];
	$b[] = ['text'=>"⬅️",'callback_data'=>"altersaldoe_ant_0"];
	$b[] = ['text'=>"➡️",'callback_data'=>"altersaldoe_prox_0"];
	$b[] = ['text'=>"← Voltar",'callback_data'=>"menu_infos"];
	$menu['inline_keyboard'] = array_chunk($b, 2);

	$txt = "✨ *Compras de saldo realizadas*\n\n";
	
	if (sizeof($saldocomprado[$chat_id]) <= 0){
		$txt .= "*Ops! Você ainda não tem nenhuma comprada realizada!*\n\n";
		die(bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => 'Markdown',"reply_markup" =>$menu)));
	}

	$dados = $saldocomprado[$chat_id];

	$split = array_chunk($dados, 2);

	$t = sizeof($split);

	$one = $split[0];

	$txt .= "*🔎 Mostrando 1 de {$t}*\n\n";

	foreach ($one as $value) {
		$txt .= "*Codigo:* {$value[codigo]}\n";
		$txt .= "*Valor:* {$value[valor]} (saldo)\n";
		$txt .= "*Expira:* ".date("d/m/Y H:i:s" , $value['datelimite'])."\n";
		$txt .= "*Comprado em:* ".date("d/m/Y H:i:s" , $value['date'])."\n\n";
	}
	$confibot = $GLOBALS['confibot'];
	$txt .= "_Problemas\devolução relatar ao_ *{$confibot['userDono']}*\n";

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => 'Markdown',"reply_markup" =>$menu));


}

/*
	altera saldo comprado
*/

function altersaldoe($message,$query,$type , $position){

	$dados = json_decode(file_get_contents("./salcocomprado.json") , true);
	$chat_id = $message["chat"]["id"];
	$idquery = $query['id'];

	$txt = "✨ *Compras de saldo realizadas*\n\n";

	$chunk = array_chunk($dados[$chat_id], 2);

	if ($type == "prox"){
		if ($chunk[ $position + 1]){
			
			$postio4n = $position +1;
		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há proxima compra!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}else{
		if ($chunk[ $position - 1]){
			
			$postio4n = $position  - 1;

		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há compra anterior!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}

	$dadoscc = $chunk[$postio4n];

	$t = sizeof($chunk);

	$d = $postio4n +1;

	$txt .= "*🔎 Mostrando {$d} de {$t}*\n\n";

	foreach ($dadoscc as $value) {
		$txt .= "*Codigo:* {$value[codigo]}\n";
		$txt .= "*Valor:* {$value[valor]} (saldo)\n";
		$txt .= "*Expira:* ".date("d/m/Y H:i:s" , $value['datelimite'])."\n";
		$txt .= "*Comprado em:* ".date("d/m/Y H:i:s" , $value['date'])."\n\n";
	}
	
	$confibot = $GLOBALS['confibot'];
	$txt .= "_Problemas\devolução relatar ao_ *{$confibot['userDono']}*\n";

	$b = [];
	$menu =  ['inline_keyboard' => [[],]];
	$b[] = ['text'=>"⬅️",'callback_data'=>"altersaldoe_ant_{$postio4n}"];
	$b[] = ['text'=>"➡️",'callback_data'=>"altersaldoe_prox_{$postio4n}"];
	$b[] = ['text'=>"← Voltar",'callback_data'=>"menu_infos"];
	$menu['inline_keyboard'] = array_chunk($b, 2);

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => 'Markdown',"reply_markup" =>$menu));
}


/*

	ver mixs ksks
*/

function mixscomprados($message){

	$historicocc = json_decode(file_get_contents("./ccsompradas.json") , true);

	$chat_id = $message["chat"]["id"];
	$b = [];
	$menu =  ['inline_keyboard' => [[],]];
	$b[] = ['text'=>"⬅️",'callback_data'=>"altermix_ant_0_ccsompradas"];
	$b[] = ['text'=>"➡️",'callback_data'=>"altermix_prox_0_ccsompradas"];
	$b[] = ['text'=>"← Voltar",'callback_data'=>"menu_infos"];
	$menu['inline_keyboard'] = array_chunk($b, 2);

	$txt = "✨*Seus Mix comprados*\n\n";
	
	if (sizeof($historicocc[$chat_id]['mixs']) <= 0){
		$txt .= "*Ops! Você ainda não tem nenhum mix comprado!*\n\n";
		die(bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => 'Markdown',"reply_markup" =>$menu)));
	}
	$dados = $historicocc[$chat_id]['mixs'][0];

	$t = sizeof($historicocc[$chat_id]['mixs']);

	$txt .= "*🔎 Mostrando 1 de {$t}*\n\n";
	// $txt .= "*$dados*";
	$txt .= "*{$dados[cc]}*\n\n";

	$txt .= "Mix comprado em: {$dados[date]}\n\n";

	$confibot = $GLOBALS['confibot'];
	$txt .= "_Problemas\devolução relatar ao_ *{$confibot['userDono']}*\n";
	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => 'Markdown',"reply_markup" =>$menu));


	
}

/*
	alter mix 
*/

function altermix($message, $query , $type ,$position , $db ){



	$dados = json_decode(file_get_contents("./{$db}.json") , true);

	$chat_id = $message["chat"]["id"];
	$txt = "✨* Suas ccs compradas*\n\n";
	$idquery = $query['id'];

	$txt = "✨* Seus Mix comprados*\n\n";


	if ($type == "prox"){
		if ($dados[$chat_id]['mixs'][ $position + 1]){
			
			$postio4n = $position +1;
		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há proxima cc!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}else{
		if ($dados[$chat_id]['mixs'][ $position - 1]){
			
			$postio4n = $position  - 1;

		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há cc anterior!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}

	$dadoscc = $dados[$chat_id]['mixs'][ $postio4n];

	$t = sizeof($dados[$chat_id]['mixs']);

	$d = $postio4n +1;

	$txt .= "*🔎Mostrando {$d} de {$t}*\n\n";
	$txt .= "*".trim($dadoscc[cc])."*\n\n";
	$txt .= "Mix comprado em: {$dadoscc[date]}\n\n";
	
	$confibot = $GLOBALS['confibot'];
	$txt .= "_Problemas\devolução relatar ao_ *{$confibot['userDono']}*\n";

	$b = [];
	$menu =  ['inline_keyboard' => [[],]];
	$b[] = ['text'=>"⬅️",'callback_data'=>"altermix_ant_{$postio4n}_ccsompradas"];
	$b[] = ['text'=>"➡️",'callback_data'=>"aaltermix_prox_{$postio4n}_ccsompradas"];
	$b[] = ['text'=>"← Voltar",'callback_data'=>"menu_infos"];
	$menu['inline_keyboard'] = array_chunk($b, 2);

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => 'Markdown',"reply_markup" =>$menu));
}

/*
	ver ccs compradas 
*/

function ccscompradas($message){

	$historicocc = json_decode(file_get_contents("./ccsompradas.json") , true);

	$chat_id = $message["chat"]["id"];
	$b = [];
	$menu =  ['inline_keyboard' => [[],]];
	$b[] = ['text'=>"⬅️",'callback_data'=>"alterValue_ant_0_ccsompradas"];
	$b[] = ['text'=>"➡️",'callback_data'=>"alterValue_prox_0_ccsompradas"];
	$b[] = ['text'=>"← Voltar",'callback_data'=>"menu_infos"];
	$menu['inline_keyboard'] = array_chunk($b, 2);

	$txt = "✨* Suas ccs compradas\n\n";
	
	if (sizeof($historicocc[$chat_id]['ccs']) <= 0){
		$txt .= "*Ops! Você ainda não tem nenhuma cc comprada!*\n\n";
		die(bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => 'Markdown',"reply_markup" =>$menu)));
	}
	$dados = $historicocc[$chat_id]['ccs'][0]['cc'];

	$t = sizeof($historicocc[$chat_id]['ccs']);

	$txt .= "*🔎Mostrando 1 de {$t}*\n\n";
	$txt .= "*💳CC:* {$dados[cc]}\n";
	$txt .= "*💳Bandeira:* {$dados[bandeira]}\n";
	$txt .= "*💳Tipo:* {$dados[tipo]}\n";
	$txt .= "*💳Level:* {$dados[nivel]}\n";
	$txt .= "*💳Banco:* {$dados[banco]}\n";
	$txt .= "*💳Pais:* {$dados[pais]}\n\n";

	$dia = $historicocc[$chat_id]['ccs'][0]['date'];
	
	$txt .= "*CC Comprada em:* {$dia}\n\n";

	$confibot = $GLOBALS['confibot'];
	$txt .= "_Problemas\devolução relatar ao_ *{$confibot['userDono']}*\n";

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => 'Markdown',"reply_markup" =>$menu));


	
}


/*
	altera cc do perfil
*/
function alterValue($message, $query , $type ,$position , $db ){



	$dados = json_decode(file_get_contents("./{$db}.json") , true);

	$chat_id = $message["chat"]["id"];
	$txt = "✨* Suas ccs compradas*\n\n";
	$idquery = $query['id'];

	$txt = "✨* Suas ccs compradas*\n\n";


	if ($type == "prox"){
		if ($dados[$chat_id]['ccs'][ $position + 1]){
			
			$postio4n = $position +1;
		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há proxima cc!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}else{
		if ($dados[$chat_id]['ccs'][ $position - 1]){
			
			$postio4n = $position  - 1;

		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há cc anterior!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}

	$dadoscc = $dados[$chat_id]['ccs'][ $postio4n]['cc'];

	$dia = $dados[$chat_id]['ccs'][ $postio4n]['date'];
	$t = sizeof($dados[$chat_id]['ccs']);

	$d = $postio4n +1;

	$txt .= "*🔎Mostrando {$d} de {$t}*\n\n";
	$txt .= "*💳CC:* {$dadoscc[cc]}\n";
	$txt .= "*💳Bandeira:* {$dadoscc[bandeira]}\n";
	$txt .= "*💳Tipo:* {$dadoscc[tipo]}\n";
	$txt .= "*💳Level:* {$dadoscc[nivel]}\n";
	$txt .= "*💳Banco:* {$dadoscc[banco]}\n";
	$txt .= "*💳Pais:* {$dadoscc[pais]}\n\n";
	$dia = $dados[$chat_id]['ccs'][ $postio4n]['date'];
	$txt .= "*CC Comprada em:* {$dia}\n\n";

	$confibot = $GLOBALS['confibot'];
	$txt .= "_Problemas\devolução relatar ao_ *{$confibot['userDono']}*\n";
	

	$b = [];
	$menu =  ['inline_keyboard' => [[],]];
	$b[] = ['text'=>"⬅️",'callback_data'=>"alterValue_ant_{$postio4n}_ccsompradas"];
	$b[] = ['text'=>"➡️",'callback_data'=>"alterValue_prox_{$postio4n}_ccsompradas"];
	$b[] = ['text'=>"← Voltar",'callback_data'=>"menu_infos"];
	$menu['inline_keyboard'] = array_chunk($b, 2);

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_to_message_id"=> $message['message_id'],  "parse_mode" => 'Markdown',"reply_markup" =>$menu));
}

/*

 resgata gift / codigo

*/

function usagift($message, $cod){
	
	$chat_id = $message["chat"]["id"];

	$gifts = json_decode(file_get_contents("./gifts.json") , true);
	$users = json_decode(file_get_contents("./usuarios.json") , true);
	$saldocomprado = json_decode(file_get_contents("./salcocomprado.json") , true);

	$menu =  ['inline_keyboard' => [[['text'=>"← Voltar",'callback_data'=>"volta_loja"]],]];

	if (!$gifts[$cod]){
		die(bot("sendMessage" , array("chat_id" => $chat_id , "text" => "*Ops! Este codigo não foi encontrado! Por favor, tente novamente.*" , "reply_to_message_id" => $message['message_id'],"parse_mode" => "Markdown")));
	}
	
	$dg = $gifts[$cod];
	$valor = $dg['valor'];

	if ($dg['used'] == "true"){
		die(bot("sendMessage" , array("chat_id" => $chat_id , "text" => "*Desculpe, mas este codigo já foi utilizado!*" , "reply_to_message_id" => $message['message_id'],"parse_mode" => "Markdown")));
	}

	// $date = strtotime("now");
	$date = strtotime("+1 week");
	$date1 = strtotime("now");

	$users[$chat_id]['saldo'] = $users[$chat_id]['saldo'] + $valor;
	$users[$chat_id]['dataLimite'] = $date;

	$saldocomprado[$chat_id][] = array("valor" => $valor , "datelimite" => $date , "date" => $date1 , "codigo" => $cod );

	$dsalva = json_encode($users,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./usuarios.json', $dsalva);

	if ($salva){
		$gifts[$cod]['used'] = "true";
		$gifts[$cod]['cliente'] = $chat_id;

		$dsalva = json_encode($gifts,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./gifts.json', $dsalva);
		// atualiza o historico de compradas 
		$dsalva2 = json_encode($saldocomprado,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./salcocomprado.json', $dsalva2);

		bot("sendMessage" , array("chat_id" => $chat_id , "text" => "*💰Opa, o saldo foi adicionado a sua conta 💎\nVocê pode ver está compra no seu perfil!*" , "reply_to_message_id" => $message['message_id'],"parse_mode" => "Markdown"));
	}else{
		die(bot("sendMessage" , array("chat_id" => $chat_id , "text" => "*Desculpe, ocorreu um erro interno!*" , "reply_to_message_id" => $message['message_id'],"parse_mode" => "Markdown")));
	}

}


/*
	compra saldo
*/

function comprasaldo($message){
	$chat_id = $message["chat"]["id"];
	$confibot = $GLOBALS['confibot'];
	$nome = $message['reply_to_message']['from']['first_name'];

	$txt = "💴 *Recarregar Saldo* 💴\n\n‼️*Primeiramente, dê o comando /recargapix, seguido do valor desejado, exemplos:*

*/recargapix 5  - Gera uma recarga de R$5*
*/recargapix 10 - Gera uma recarga de R$10
*Escolha o valor que vc quiser!

⚠️ *Será gerado um código pix (Qr Code), no qual você irá clicar e ele será copiado para sua area de transferência, após cópiar, entre entre no aplicativo do seu banco de preferência, na parte de transferência ou pagamento por pix, procure pela sessão Pix Copia e Cola, ele ira solitar que você cole algo, após colar o código gerado pelo bot, efetue o pagamento.*

🏦 *Envie seu Comprovante de Pagamento + seu ID no botão ENVIAR COMPROVANTE para que sua recarga de saldo no bot seja creditada.*

⛔ *OBS: Não fazemos trocas das CC's pois o material é testado automáticamente no nosso Checker de Full do Bot no ato da entrega das CC's para o usuário!*\n\n";


	/*$txt = "💰 *Comprar Saldo* 💰\n\n ⚡️Para Adicionar Saldo à sua conta você deve enviar o valor que deseja adicionar para o Pix abaixo e enviar o comprovante de pagamento para um de nossos vendedores.\n\n";
	$txt .= "🚀 *ENVIE SEU COMPROVANTE PARA @Suportechavonelis* \n\n";
	$txt .= "⚠️ *PIX E-MAIL: 0ada976d-9478-4a2b-b18b-a6806c509fdc* \n\n";
	$txt .= "✅ Após a confirmação do seu pagamento o seu  será adicionado! \n\n";
	$txt .= "💶 *Aceitamos somente*\n*💲 Pix*\n\n";
	$txt .= "💶 *Lembrando que é o comando*\n*⚠️ /recargapix*\n\n";	
	$txt .= "⚠️ *Por motivos de segurança seu saldo tem validade de 7 dias*! ";*/

	$menu =  ['inline_keyboard' => [
		[['text'=>"← Voltar",'callback_data'=>"volta_menu"]]
	,]];

	bot("editMessageText",array( 
		"message_id" => $message['message_id'] , 
		"chat_id"=> $chat_id , 
		"text" => $confibot["pix_message"],
		"reply_to_message_id"=> $message['message_id'],  
		"parse_mode" => 'Markdown',
		"reply_markup" =>$menu 
	));
}




/*
	Mostra o pix
*/
function mostrarpix($message){
	$chat_id = $message["chat"]["id"];
	$confibot = $GLOBALS['confibot'];
	$nome = $message['reply_to_message']['from']['first_name'];

	$txt = "💰 *Comprar Saldo* 💰\n\n ⚡️Para Adicionar Saldo à sua conta você deve enviar o valor que deseja adicionar para o Pix abaixo e enviar o comprovante de pagamento para um de nossos vendedores.\n\n";
	$txt .= "✅ Após a confirmação do seu pagamento o seu saldo será adicionado!
	 \n\n";
	$txt .= "💶 *Aceitamos somente*\n*💠 Pix*\n\n";
	$txt .= "💶 *Lembrando que é o comando*\n*⚠️ /recargapix*\n\n";	
	$txt .= "⚠️ *Por motivos de segurança seu saldo tem validade de 7 dias*! ";

	
	$menu =  ['inline_keyboard' => [
		[['text'=>"← Voltar",'callback_data'=>"comprasaldo"]]
	,]];


	bot("editMessageText",array( 
		"message_id" => $message['message_id'] , 
		"chat_id"=> $chat_id , 
		"text" => $confibot.pix_message,
		"reply_to_message_id"=> $message['message_id'],  
		"parse_mode" => 'Markdown',
		"reply_markup" =>$menu 
	));
}


/*
	search exemplo do search
*/

function search ($message){
	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$menu =  ['inline_keyboard' => [

		[['text'=>"← Voltar",'callback_data'=>"volta_loja"]]
		,
	]];

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => "💳 Mande-me a bin","reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown' , 'force_reply' => true , "selective" => true));
}


/*
	busca a bin no json
*/
function buscabin($message){

	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$pre = preg_match("/[0-9]{6}/", $message['text'],$bin);

	$menu =  ['inline_keyboard' => [

		[]

	,]];
	

	$clientes = json_decode(file_get_contents("./usuarios.json") , true);
	$searchs = json_decode(file_get_contents("./search.json") , true);
	$price = json_decode(file_get_contents("./resource/conf.json") , true)['price'];
	$bin = $bin[0];

	$msgbot = bot("sendMessage",array( "chat_id"=> $chat_id , "text" => "_*Aguarde estou buscando a bin... $bin*_","reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));


	$message_id = json_decode($msgbot , true)['result']['message_id'];

	$ccs = [];
	$country = $clientes[$chat_id]['country'];
	$dir = './ccs/'.$country.'/';

	$itens = scandir($dir);
	
	if ($itens !== false) { 
		foreach ($itens as $item) { 
			$ccs[] =  explode(".", $item)[0];
		}
	}

	$levels = array_values(array_filter($ccs));
	
	$result = [];

	foreach ($levels as $key => $value) {
		$ccs = json_decode(file_get_contents("./ccs/{$country}/{$value}.json") , true);

		foreach ($ccs as $key => $value) {
			if (substr($value['cc'], 0,6) == $bin){
				$value['idcc'] = $key;
				$result[] = $value;
			}
		}
	}

	// bot("editMessageText",array( "message_id" => $message_id  , "chat_id"=> $chat_id , "text" => $result));

	// exit();

	if (empty($result)){
		$confibot = $GLOBALS['confibot'];

		die(bot("editMessageText",array( "message_id" => $message_id , "chat_id"=> $chat_id , "text" => "*Não foi encontrado nenhum resultado para a bin $bin, entre em contato com o nosso vendedor e pergunte se há alguma disponivel em seu estoque*, _vendedor:_ *{$confibot['userDono']}*","reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown')));

	}

	$botoes = [];


	$dadoscc = $result[0];
	$idcc = $dados['idcc'];
	$level = $dados['nivel'];
	$preco  = ($price[$level]) ? $price[$level] : $price['Default'];

	$saldo = $clientes[$chat_id]['saldo'];

	
	$botoes[] = ['text'=>"⬅️",'callback_data'=>"altercc_ant_0"];
	
	$botoes[] = ['text'=>"➡️",'callback_data'=>"altercc_prox_0"];
	
  	$txt .= "📡 *BIN: *_".$bin.'xxxxxxxxx'."_\n";
	$bin = substr($dadoscc['cc'], 0,6);
	$txt .= "🏳* BANDEIRA:* _$dadoscc[bandeira]_\n";
	$txt .= "🌡* NIVEL:* _$dadoscc[nivel]_\n";
	$txt .= "🎲 *Tipo:* _$dadoscc[tipo]_\n";


	$searchs[$chat_id] = $result;
	$dsalva = json_encode($searchs,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./search.json', $dsalva);

	$menu['inline_keyboard'] = array_chunk($botoes, 3);

	$menu['inline_keyboard'][] = [['text'=>"← Voltar",'callback_data'=>"volta_loja"]];

	$total = sizeof($result);
		
	bot("editMessageText",array( "message_id" => $message_id  , "chat_id"=> $chat_id , "text" => "*🔎Foi encontrada*  _{$total}_ *ccs com esta bin *_{$bin}_ *no banco de dados!*\n\n$txt","reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown',"reply_markup" =>$menu));


}


/*
	
	altera cc do search!
	
*/

function altercc($message,$type , $postion , $query){

	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$idquery = $query['id'];

	$ccs = json_decode(file_get_contents("./search.json") , true);

	$ccs = $ccs[$chat_id];

	if ($type == "prox"){

		if ($ccs[ $postion + 1 ]){
			$dados = $ccs[ $postion + 1];
			$postio4n = $postion+1;
			
		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há próxima cc!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}else{

		if ($ccs[$postion -1 ]){
			$dados = $ccs[ $postion - 1 ];
			$postio4n = $postion -1;
		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há cc anterior!!!","show_alert"=> false,"cache_time" => 10)));
		}
	}

	

	$dadoscc = $ccs[$postio4n];
	$menu =  ['inline_keyboard' => [[],]];
	$price = json_decode(file_get_contents("./resource/conf.json") , true)['price'];
	$clientes = json_decode(file_get_contents("./usuarios.json") , true);
	$botoes = [];
	$idcc = $dadoscc['idcc'];
	$level = $dadoscc['nivel'];

	$saldo = $clientes[$chat_id]['saldo'];

	$preco  = ($price[$level]) ? $price[$level] : $price['Default'];

	$botoes[] = ['text'=>"⬅️",'callback_data'=>"altercc_ant_{$postio4n}"];

	$botoes[] = ['text'=>"➡️",'callback_data'=>"altercc_prox_{$postio4n}"];
	

	$bin = substr($dadoscc['cc'], 0,6);
	$txt .= "📡 *BIN: *_".$bin.'xxxxxxxxx'."_\n";
	$bin = substr($dadoscc['cc'], 0,6);
	$txt .= "🏳* BANDEIRA:* _$dadoscc[bandeira]_\n";
	$txt .= "🌡* NIVEL:* _$dadoscc[nivel]_\n";
	$txt .= "🎲 *Tipo:* _$dadoscc[tipo]_\n";



	$menu['inline_keyboard'] = array_chunk($botoes, 3);
	$menu['inline_keyboard'][] = [['text'=>"← Voltar",'callback_data'=>"volta_loja"]];
	$total = sizeof($ccs);
	
	$bin = substr(explode("|", $dados['cc'])[0], 0,6);

	bot("editMessageText",array( "message_id" => $message['message_id']  , "chat_id"=> $chat_id , "text" => "*Mostrando resultado ".($postio4n + 1)." de {$total} da bin {$bin}*\n\n$txt","reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown',"reply_markup" =>$menu));

	// atualiza($chat_id,$bin);

}

/*
	compra cc do search
*/


function comprasearch ($message , $id , $level , $query){

	$confibot = $GLOBALS['confibot'];

	$level = strtolower($level);

	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$idquery = $query['id'];

	$clientes = json_decode(file_get_contents("./usuarios.json") , true);
	$seach = json_decode(file_get_contents("./search.json") , true);
	$conf = json_decode(file_get_contents("./resource/conf.json") , true);

	$menu =  ['inline_keyboard' => [[],]];
	$menu['inline_keyboard'][] = [['text'=>"← Voltar",'callback_data'=>"volta_loja"]];

	if (!$clientes[$chat_id]){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Usuario sem registro, envie /start para fazer o seu registro!!","show_alert"=> true,"cache_time" => 10)));
	}

	$price = json_decode(file_get_contents("./resource/conf.json") , true)['price'];

	$valor  = ($price[$level]) ? $price[$level] : $price['Default'];


	$user = $clientes[$chat_id];
	if ($user['saldo'] == 0){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Você não tem saldo suficiente para realizar está compra!\nRecarregue com o *{$confibot['userDono']}*!","show_alert"=> true,"cache_time" => 10)));
	} 

	if (empty($level)){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "A cc não foi encontrada!","show_alert"=> false,"cache_time" => 10)));
	}

	if ($valor > $user['saldo']){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Você não tem saldo suficiente para realizar está compra!\nCompre saldo com o *{$confibot['userDono']}*!","show_alert"=> true,"cache_time" => 10)));
	} 


	$dadoscc = deletecc($chat_id , $id,$level);

	if (empty($dadoscc)){
		bot("sendMessage" , array("chat_id" => $conf['dono'] , "text" => "A base está sem esta cc's $level !!"));
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Desculpe, mas estou sem cc's $level.\n!","show_alert"=> true,"cache_time" => 10)));
		
	}

	if (removesaldo($chat_id , $valor)){
		

		bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Foi descontado $valor do seu saldo!! ","show_alert"=> false,"cache_time" => 10));

		salvacompra($cc,$chat_id,"ccs");

		$saldo = $clientes[$chat_id]['saldo'] - $valor;

		$result = json_decode(bot("getMe" , array("vel" => "")) , true);
		$userbot = $result['result']['username'];

		$txt .= "✨*Detalhes da cc*\n";
		$txt .= "💳*Cartão: *_".$dadoscc['cc']."_\n";
		$txt .= "📆*mes / ano: *_" . $dadoscc['mes'] .'/'.$dadoscc['ano'] ."_\n";
		$txt .= "🔐*Código de Segurança: *_{$dadoscc[cvv]}_\n";
		$txt .= "??️bandeira:* _$dadoscc[bandeira]_\n";
		$txt .= "💠*nivel:* _$dadoscc[nivel]_\n";
		$txt .= "⚜️*tipo:* _$dadoscc[tipo]_\n";
		$txt .= "🏛*banco:* _$dadoscc[banco]_\n";
		$txt .= "🌍*pais:* _$dadoscc[pais]_\n";
		$txt .= "💲 *Seu saldo:* _{$saldo}_\n";

		$menu =  ['inline_keyboard' => [

			[["text" => "🔄 Comprar novamente" , "url" => "http://telegram.me/$userbot?start=iae"]]

		,]];

		bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));

		// $bin = substr(explode("|", $cc['cc'])[0], 0,6);

		// atualiza($chat_id,$bin);

		exit();
	}else{
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Ocorreu um erro interno. Por favor, tente novamente!","show_alert"=> false,"cache_time" => 10)));
	}
}
/*
	realiza a venda de um mix !
*/

//$nivel = trim($nivel);

function compramix($message,$nivel , $valor,$query){

	$confibot = $GLOBALS['confibot'];

	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$idquery = $query['id'];
	$clientes = json_decode(file_get_contents("./usuarios.json") , true);
	$conf = json_decode(file_get_contents("./resource/conf.json") , true);

	$menu =  ['inline_keyboard' => [

		[]

	,]];



	$menu['inline_keyboard'][] = [['text'=>"← Voltar",'callback_data'=>"volta_loja"]];

	if (!$clientes[$chat_id]){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Usuario sem registro, envie /start para fazer o seu registro!!","show_alert"=> true,"cache_time" => 10)));
	}
	$user = $clientes[$chat_id];

	if ($user['saldo'] == 0){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Você não tem saldo suficiente para realizar está compra!\nCompre saldo com o *{$confibot['userDono']}*!","show_alert"=> true,"cache_time" => 10)));
	} 

	if ($valor > $user['saldo']){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Você não tem saldo suficiente para realizar está compra!\nCompre saldo com o *{$confibot['userDono']}*!","show_alert"=> true,"cache_time" => 10)));
	} 


	$cc = getmix($nivel);

	if (empty($cc)){
		bot("sendMessage" , array("chat_id" => $conf['dono'] , "text" => "A base esta sem mix $nivel !!"));
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Desculpe, mas não consegui pegar está cc.\nProvavelmente estou sem estoque!","show_alert"=> true,"cache_time" => 10)));
		
	}

	if (removesaldo($chat_id , $valor)){

		bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Foi descontado $valor do seu saldo!! ","show_alert"=> false,"cache_time" => 10));

		$lista = explode("\n", $cc);
		foreach ($lista as  $cc) {
			// bot("sendMessage",array( "chat_id"=> $chat_id , "text" => $cc));
			$bin = substr(trim($cc), 0,6);

			$binchk = file_get_contents('https://bincheck.io/bin/'.$bin);

			$ban =  strtoupper(getstr($binchk,'<td style="text-align: left;">','</td>',1));
			$type = strtoupper(getstr($binchk,'style="text-align: left;">','</td>',3));
			$banco = strtoupper(getstr($binchk,'style="text-align: left;">','</td>',5));
			$pais = strtoupper(getstr($binchk,'style="text-align: left;">','</td>',8)); 
			$nivel = str_replace("\t", '', strtoupper(getstr($binchk,'style="text-align: left;">','</td>',4)));

			bot("sendMessage",array( "chat_id"=> $chat_id , "text" => $cc . " - ".trim($ban)." ".trim($type)." ".trim($nivel)." ".trim($banco)." ".trim($pais).""));
		}

	
		salvacompra($cc,$chat_id,"mixs");
		bot("sendMessage",array( "chat_id"=> $chat_id , "text" => "*Compra realizada com sucesso*\n_Obs: problemas relatar ao_ *{$confibot['userDono']}!!*\n_Você pode ver está compra no seu perfil!_","reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown',"reply_markup" =>$menu));

		exit();
	}else{
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Ocorreu um error interno , Tente novamente!","show_alert"=> false,"cache_time" => 10)));
	}

	
}



/*
	realiza a venda de ccs!!
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/

function compraccs($message , $query , $level , $idcc , $band){

	$confibot = $GLOBALS['confibot'];
	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$idquery = $query['id'];

	$clientes = json_decode(file_get_contents("./usuarios.json") , true);
	$conf = json_decode(file_get_contents("./resource/conf.json") , true);

	if (!$clientes[$chat_id]){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Usuario sem registro, envie /start para fazer o seu registro!!","show_alert"=> true,"cache_time" => 10)));
	}

	$user = $clientes[$chat_id];
	$country = $user['country'];
	$saldo = $clientes[$chat_id]['saldo'];
	$ccs = json_decode(file_get_contents("./ccs/{$country}/{$level}.json") , true);
	$valor = ($conf['price'][$level] ? $conf['price'][$level] : $conf['price']['default']);

	if ($user['saldo'] == 0){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Você não tem saldo suficiente para realizar esta compra!\nCompre saldo com o *{$confibot['userDono']}*!","show_alert"=> true,"cache_time" => 10)));
	} 

	if ($valor > $user['saldo']){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Você não tem saldo suficiente para realizar esta compra!\nCompre saldo com o *{$confibot['userDono']}*!","show_alert"=> true,"cache_time" => 10)));
	} 
	
	foreach ($ccs as $key => $dadoscc) {
		if ($key == $idcc){
			
			break;
		}
	}

	

	if (removesaldo($chat_id , $valor)){
		
		deletecc($chat_id , $idcc , $level);
		

        bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => "**🕗 PROCESSO DE ENTREGA INICIADO...\n\n\nAGUARDE O CHECKER TESTAR A INFOCC!**", "parse_mode" => 'Markdown'));
		sleep(5);
		//bot("sendMessage",array( "chat_id"=> $chat_id , "text" => $loginchk));
		#================================#
		if (strpos($idquery, 'PROCESSO DE ENTREGA') !== true){

			bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "AGUARDE...\n\n\nCHECKER INICIADO!\nESTAMOS TESTANDO O CARTÃO 💳\n.","show_alert"=> true,"cache_time" => 10));
		}


		$clientes = json_decode(file_get_contents("./usuarios.json") , true);
		$saldo = $clientes[$chat_id]['saldo'];

		$result = json_decode(bot("getMe" , array("vel" => "")) , true);
		$userbot = $result['result']['username'];
		#================================#
				$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://azkabancenter.online/login/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'');
		curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 'Usuario=chaves13697&Senha=salve2017@');
		$Login = curl_exec($ch);
		//bot("sendMessage",array( "chat_id"=> $chat_id , "text" => $Login));

		curl_setopt($ch, CURLOPT_URL, 'https://azkabancenter.online/painel/');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		$Inicio = curl_exec($ch);
		//bot("sendMessage",array( "chat_id"=> $chat_id , "text" => $Inicio));

		//////ESCOLHA O GATEWAY/////
		$Gateway = 'full4-iugu';
		//$Gateway = 'full5-cielo';
		////////////////////////////

		curl_setopt($ch, CURLOPT_URL, 'https://azkabancenter.online/painel/arquivos/php/chkccs/'.$Gateway.'/'.$Gateway.'.php?lista='.$dadoscc['cc'].'|'.$dadoscc['mes'].'|'.$dadoscc['ano'].'|'.$dadoscc['cvv'].'');
		$rchecker = curl_exec($ch);
		sleep(15);
		//bot("sendMessage",array( "chat_id"=> $chat_id , "text" => $rchecker));
		
		if(strpos($rchecker, 'Aprovada ✓') !== false){
		salvacompra($dadoscc , $chat_id , "ccs");
		$txt .= "💴 *Compra Realizada com sucesso!*\n";
		$txt .= "💳 *NOME: *_".$dadoscc['Nome']."_\n";
		$txt .= "📆 *CPF: *_" . $dadoscc['CPF'] ."_\n";
		$txt .= "💳 *Cartão: *_".$dadoscc['cc']."_\n";
		$txt .= "📆 *Validade: *_" . $dadoscc['mes'] .'/'.$dadoscc['ano'] ."_\n";
		$txt .= "🔐 *Código de Segurança: *_{$dadoscc['cvv']}_\n";
		$txt .= "🏳️ *Bandeira:* _$dadoscc[bandeira]_\n";
		$txt .= "💠 *Nivel:* _$dadoscc[nivel]_\n";
		$txt .= "⚜️ *Tipo:* _$dadoscc[tipo]_\n";
		$txt .= "🏛 *Banco:* _$dadoscc[banco]_\n";
		$txt .= "🌍 *País:* _$dadoscc[pais]_\n";
		$txt .= "💲 *Seu saldo:* _{$saldo}_\n";

		$menu =  ['inline_keyboard' => [

			[["text" => "🔄 Comprar novamente" , "url" => "http://telegram.me/$userbot?start=$chat_id"]]

		,]];
		
		bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
		sleep(2);
		bot("sendmessage",array("chat_id" => $chat_id , "text" => "⛔️AVISO⛔️\nNOSSO BOT ENVIA DADOS COMO NOME E CPF PORÉM RECOMENDO USAR OS SEUS PROPRIOS DADOS!" ));
		bot("sendMessage", array("chat_id" => "-1468597187" , "text" => "<b>💲1 CC comprada!. Obrigado, usuario: $chat_id\n\n</b>" , "parse_mode" => "html"));   	
		die;
		}
		else{
			if ( devolvesaldo($chat_id , $valor)){
		$saldorec = $saldo += $valor;
		$txt .= "❌ *CC NÃO PASSOU NO CHECKER!* ❌\n";
		$txt .= "⚠️ *COMPRE OUTRA CC!* ⚠️\n";
		$txt .= "💹 *Seu saldo:* _{$saldorec}_\n";

		$menu =  ['inline_keyboard' => [

			[["text" => "🔄 Comprar novamente" , "url" => "http://telegram.me/$userbot?start="]]

		,]];

		bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
		sleep(2);
			}
		}
	}else{
		bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Foi descontado $valor do seu saldo!! ","show_alert"=> false,"cache_time" => 10));
		die;
	}

}

function altercard($message , $query , $type , $position , $level , $band){

	$confibot = $GLOBALS['confibot'];


	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$idquery = $query['id'];

	$clientes = json_decode(file_get_contents("./usuarios.json") , true);
	$conf = json_decode(file_get_contents("./resource/conf.json") , true);


	
	if (!$clientes[$chat_id]){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Usuario sem registro, envie /start para que você possar ser registrado!!","show_alert"=> true,"cache_time" => 10)));
	}

	$user = $clientes[$chat_id];
	$country = $user['country'];
	$saldo = $clientes[$chat_id]['saldo'];
	$ccs = json_decode(file_get_contents("./ccs/{$country}/{$level}.json") , true);

	$cclista = []; 

	$buttons = [];

	foreach ($ccs as $key => $value) {
		if ($value['bandeira'] == $band){
			$value['idcc'] = $key;
			$cclista[] = $value;
		}
	}

	if ($type == "prox"){
		
		if ($cclista[ $position +1]){
			$postio4n = $position +1;
		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há próxima cc!","show_alert"=> false,"cache_time" => 10)));
		}

	}else{

		if ($cclista[ $position -1]){
			$postio4n = $position -1;
		}else{
			die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Não há cc anterio!","show_alert"=> false,"cache_time" => 10)));
		}
	}

	$valor = ($conf['price'][$level] ? $conf['price'][$level] : $conf['price']['default']);

	$dadoscc = $cclista[$postio4n];
	$t = $postio4n +1;
	$txt = "🔎*Mostrando {$t} de ".sizeof($cclista)."*\n\n";
	$bin = substr($dadoscc['cc'], 0,6);
	$txt .= "📡 *BIN: *_".$bin.'xxxxxxxxx'."_\n";
	$bin = substr($dadoscc['cc'], 0,6);
	$txt .= "🏳* BANDEIRA:* _$dadoscc[bandeira]_\n";
	$txt .= "🌡* NIVEL:* _$dadoscc[nivel]_\n";
	$txt .= "🎲 *Tipo:* _$dadoscc[tipo]_\n";

	$menu =  ['inline_keyboard' => [

		[["text" => "💵 Comprar CC 💵" , "callback_data" => "compraccs_{$level}_{$dadoscc[idcc]}_{$band}"]],
		[["text" => "⬅ Anterior Bin" , "callback_data" => "altercard_ant_{$postio4n}_{$level}_{$band}"] , ["text" => "➡ Próxima Bin" , "callback_data" => "altercard_prox_{$postio4n}_{$level}_{$band}"]],
		[['text'=>"← Voltar",'callback_data'=>"ccun"]]

	,]];


	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));


}

function viewcard($message , $query , $band , $level){

	$confibot = $GLOBALS['confibot'];


	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$idquery = $query['id'];

	$clientes = json_decode(file_get_contents("./usuarios.json") , true);
	$conf = json_decode(file_get_contents("./resource/conf.json") , true);

	
	if (!$clientes[$chat_id]){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Usuario sem registro, envie /start para fazer o seu registro!!","show_alert"=> true,"cache_time" => 10)));
	}

	$user = $clientes[$chat_id];
	$country = $user['country'];
	$saldo = $clientes[$chat_id]['saldo'];

	$ccs = json_decode(file_get_contents("./ccs/{$country}/{$level}.json") , true);

	$cclista = []; 

	$buttons = [];

	foreach ($ccs as $key => $value) {
		if ($value['bandeira'] == $band){
			$value['idcc'] = $key;
			$cclista[] = $value;
		}
	}


	$valor = ($conf['price'][$level] ? $conf['price'][$level] : $conf['price']['default']);

	$dadoscc = $cclista[0];
	$txt = "🔎*Mostrando 1 de ".sizeof($cclista)."*\n\n";
	$bin = substr($dadoscc['cc'], 0,6);
	$txt .= "📡 *BIN: *_".$bin.'xxxxxxxxx'."_\n";
	$bin = substr($dadoscc['cc'], 0,6);
	$txt .= "🏳* BANDEIRA:* _$dadoscc[bandeira]_\n";
	$txt .= "🌡* NIVEL:* _$dadoscc[nivel]_\n";
	$txt .= "🎲 *Tipo:* _$dadoscc[tipo]_\n";

	$menu =  ['inline_keyboard' => [

		[["text" => "💵 Comprar CC 💵" , "callback_data" => "compraccs_{$level}_{$dadoscc[idcc]}_{$band}"]],
		[["text" => "⬅ Anterior Bin" , "callback_data" => "altercard_ant_0_{$level}_{$band}"] , ["text" => "➡ Próxima Bin" , "callback_data" => "altercard_prox_0_{$level}_{$band}"]],
		[['text'=>"← Voltar",'callback_data'=>"ccun"]]

	,]];


	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
}

function compracc($message,$query,$level){

	$confibot = $GLOBALS['confibot'];


	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$idquery = $query['id'];

	$clientes = json_decode(file_get_contents("./usuarios.json") , true);
	$conf = json_decode(file_get_contents("./resource/conf.json") , true);

	$menu =  ['inline_keyboard' => [

		[]

	,]];

	$menu['inline_keyboard'][] = [['text'=>"← Voltar",'callback_data'=>"volta_loja"]];

	if (!$clientes[$chat_id]){
		die(bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "Usuario sem registro, envie /start para fazer o seu registro!!","show_alert"=> true,"cache_time" => 10)));
	}

	$user = $clientes[$chat_id];
	$country = $user['country'];
	
	$ccs = json_decode(file_get_contents("./ccs/{$country}/{$level}.json") , true);

	$band = [];
	$buttons = [];

	foreach ($ccs as $key => $value) {
		if (!in_array($value['bandeira'], $band)){
			$band[] = $value['bandeira'];
			$buttons[] = ["text" => $value['bandeira'] , "callback_data" => 'viewcard_'.$value['bandeira'].'_'.$level];
		}
	}

	
	$menu['inline_keyboard'] = array_chunk($buttons , 2);

	$menu['inline_keyboard'][] = [['text'=>"← Voltar",'callback_data'=>"ccun"]];

	$txt = "\n*✅ nivel:* _{$level}_\n*💳 Escolha a bandeira preferida:*";

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_markup" =>$menu,"reply_to_message_id"=> $message[''],"parse_mode" => 'Markdown'));

		
}


/*

	function loja 
	exibir menu loja virtual

*/
function loja($message){






 $chat_id = $message["chat"]["id"];






 $nome = $message['reply_to_message']['from']['first_name'];



  $conf = json_decode(file_get_contents("./resource/conf.json") , true);


    $clientes = json_decode(file_get_contents("./usuarios.json") , true);






  $saldo = $clientes[$chat_id]['saldo'];




 $menu =  ['inline_keyboard' => [













  [['text'=>"💳 UNITARIA",'callback_data'=>"ccun"]],






  [['text'=>"🎲 MIX",'callback_data'=>"ccmix"] , ['text'=>"🔐 PESQUISA  BIN",'callback_data'=>"search"]],






  [['text'=>"← Voltar",'callback_data'=>"volta_menu"]]













  ,






 ]];
















$ts1 = $conf["text_store_1"];
$ts2 = $conf["text_store_2"];
$ts3 = $conf["text_store_3"];

 bot("editMessageText", array( 
 	"message_id" => $message['message_id'], 
 	"chat_id"=> $chat_id, 
 	"text" => "*Olá $nome \n\n $ts1 💳 \n\n $ts2 \n\n Nome → $nome \n Saldo → $saldo \n ID → $chat_id* \n\n $ts3",
 	"reply_markup" =>$menu,
 	"reply_to_message_id"=> $message['message_id'],
 	"parse_mode" => 'Markdown'
 ));






 






}


/*

	function menu
	exibir menu inicial

*/

function menu($message){
	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];
	$idwel = $message['reply_to_message']['from']['id'];
	$conf = json_decode(file_get_contents("./resource/conf.json") , true);

	if ($conf['welcome'] != ""){
		$txt = $conf["welcome"];

		$txt = str_replace("{nome}", $nome, $txt);
		$txt = str_replace("{id}", $idwel, $txt);

	}else{
		$txt = "*💲PREÇOS DAS CC'S DO BOT
💳STANDARD: R$6,00\n💳BUSINESS: R$10,00\n💳PLATINUM: R$10,00\n💳INFINITE: R$15,00\n💳BLACK: R$15,00\n💳ELO: R$7,00\n💳CORPORATE: R$15,00\n💳DISCOVER: R$7,00\n💳HIPERCARD: R$7,00\n💳PREPAID: R$3,00\n💳EXECUTIVE: 20,00\n💳WORLD: R$6,00\n💳INDEFINIDAS: R$8,00\n💳ELECTRON: R$7,00\n💳HIPER: R$7,00\n💳TITANIUM: R$15,00\n💳PERSONAL: 10,00\n💳CLASSIC: R$5,00\n💳PURCHASING: R$15,00
🚀GRUPO: https://t.me/joinchat/15Qa_lYCTn9kOGNh\n\n📌 SEU ID: $idwel\n\n*";
	}


 $menu =  ['inline_keyboard' => [

  [
  	['text'=> $conf["text_btn_cc_store"] , 'callback_data'=>"loja"]
  ],

  [
  	['text'=> $conf["text_btn_add_funds"] , 'callback_data'=>"comprasaldo"] , 
  	['text'=> $conf["text_btn_informations"] , 'callback_data'=>"menu_infos"]
  ],

  ]];
		bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => $txt,"reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
	
}


/*

	function ccn 
	exibir menu ccs 

*/


function ccun($message){
	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];


	$menu =  ['inline_keyboard' => [[], ]];

	$openprice = json_decode(file_get_contents("./resource/conf.json") , true);

	$users = json_decode(file_get_contents("./usuarios.json") , true);

	if (!$users[$chat_id]['country']){
		selectbase($message);
		die;
	}
	// selectbase($message);


	$ccs = [];
	$country = $users[$chat_id]['country'];
	$dir = './ccs/'.$country.'/';

	$itens = scandir($dir);
	
	if ($itens !== false) { 
		foreach ($itens as $item) { 
			$ccs[] =  explode(".", $item)[0];
		}
	}

	$levels = array_values(array_filter($ccs));


	$butoes = [];

	if (count($levels) == 0){
		$confibot = $GLOBALS['confibot'];
		$butoes[] = ['text'=>"← Voltar",'callback_data'=>"volta_loja"];
	    $butoes[] = ['text'=>"🎌 Alterar País da CC",'callback_data'=>"selectbase"];

	    $menu['inline_keyboard'] = array_chunk($butoes , 2);
		bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => "🚫 O ESTOQUE DA BASE DE CC'S MIX ACABOU! JAJÁ ESTARÁ REABASTECIDA NOVAMENTE.","reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
		die();
	}


	foreach ($levels as $value) {
		
		$butoes[] = ['text'=> "$value",'callback_data'=>"compracc_{$value}"];
		
	}
	$butoes[] = ['text'=>"← Voltar",'callback_data'=>"volta_loja"];
	$butoes[] = ['text'=>"🎌 Alterar País da CC",'callback_data'=>"selectbase"];

	$menu['inline_keyboard'] = array_chunk($butoes , 2);

	$confibot = $GLOBALS['confibot'];

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => "💳 Comprar CC Unitária.\n\n⚠️ Alertas\n\n- Todas infos são testadas antes do envio\n- Todas info já vem com nome e cpf","reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
	
}



/*

	function ccn 
	exibir menu mixs

*/


function ccmix($message){

	$confibot = $GLOBALS['confibot'];


	$chat_id = $message["chat"]["id"];
	$nome = $message['reply_to_message']['from']['first_name'];


	$menu =  ['inline_keyboard' => [[], ]];

	$openccs = json_decode(file_get_contents("./resource/conf.json") , true);
	$mix = json_decode(file_get_contents("./mix.json") , true);


	if (count(array_filter($mix)) == 0){
		$menu['inline_keyboard'][] = [['text'=>"← Voltar",'callback_data'=>"volta_loja"]];
		bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => "🚫 O ESTOQUE DA BASE DE CC'S MIX ACABOU! JAJÁ ESTARÁ REABASTECIDA NOVAMENTE.","reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
		die();
	}


	$array = [];
	$tabela = '';

	foreach ($mix as $key => $value) {

		if ($openccs['pricemix'][$key]){
			$valor = $openccs['pricemix'][$key];
		}else{
			$valor = $openccs['pricemix']['default'];
		}

		$tabela .= "\n".'💳 Mix '.strtoupper($key).' --- '.$valor." (saldo)\n";
		$total = sizeof($mix[$key]);
		$array[] = ['text'=>"Mix $key - disponiveis ($total)",'callback_data'=>"compramix_{$key}_$valor"];
	}
	$add = array_chunk($array, 2);
	$menu['inline_keyboard'] = $add;
	$menu['inline_keyboard'][] = [['text'=>"← Voltar",'callback_data'=>"volta_loja"]];

	bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => "Está area é resevada para os mix, caso não tenha o mix que você estaja procurando, entre em contato com o nosso vendedor: *{$confibot['userDono']}*.\n$tabela","reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
	
}
