<?php


date_default_timezone_set('America/Fortaleza');



function salve($msg){
	$id = $msg['from']['id'];
	$chatid = $msg['chat']['id'];


	
	
	$users = file_get_contents('./usuarios.json');
	$users = json_decode($users, true);

	// bot("sendMessage",array("chat_id" => $chatid , 'text' => $users));

	if (!$users[$id]){
		$nome = $msg['from']['first_name'].' '.$msg['from']['last_name'];
		$username = $msg['from']['username'];
		if (empty($username)){$username = "undefined";}
		$time = time();
		$users[$id] = array(
		'nome' =>$nome,
		'saldo'=> 0,
		'cadastro' =>$time,
		"last_msg" =>$time,
		"username" =>$username,
		"adm" => 'false'
		);
		$dsalva = json_encode($users,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
		$salva = file_put_contents('./usuarios.json', $dsalva);
	}
}



function removesaldo($user,$re){
	$users = file_get_contents('./usuarios.json');
	$users = json_decode($users, true);

	if (!$users[$user]){
		return false;
	}

	$saldoAtual = $users[$user]['saldo'];

	$re = ( int ) $saldoAtual - (int) $re;

	$users[$user]['saldo'] = $re;

	$dsalva = json_encode($users,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./usuarios.json', $dsalva);

	if ($salva){
		return true;
	}else{
		return false;
	}
}


function deletecc($chat_id , $idcc , $level){
	$users = file_get_contents('./usuarios.json');
	$users = json_decode($users, true);

	$country = $users[$chat_id]['country'];

	$ccs = json_decode(file_get_contents("./ccs/{$country}/{$level}.json") , true);

	$return = $ccs[$idcc];

	unset($ccs[$idcc]);
	
	$salva = file_put_contents("./ccs/{$country}/{$level}.json", json_encode($ccs , JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT));

	return $return;
}

// $nivel = trim($nivel);

function getCc($nivel){
	$nivel = strtolower($nivel);
	$cc = file_get_contents('./ccs.json');
	$ccs = json_decode($cc, true);

	$rand = array_rand($ccs[$nivel]);
	$return =  $ccs[$nivel][$rand];
	
	unset($ccs[$nivel][$rand]);


	$dsalva = json_encode($ccs,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./ccs.json', $dsalva);


	return $return;
	
}

function salvacompra($cc,$user,$type){
	$ccs = file_get_contents('./ccsompradas.json');
	$ccs = json_decode($ccs, true);

	$ccs[$user][$type][] = ["cc" => $cc , 'date' => date("j-m-Y H:i:s")];

	$dsalva = json_encode($ccs,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./ccsompradas.json', $dsalva);

}


function getmix($nivel){
	$cc = file_get_contents('./mix.json');
	$ccs = json_decode($cc, true);


	$return = $ccs[$nivel];
	$v = array_rand($return);
	$rand = $return[$v];
	
	unset($ccs[$nivel][$v]);


	$dsalva = json_encode($ccs,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./mix.json', $dsalva);


	return $rand;
}


function getCcSearch($id,$level){

	$nivel = strtolower($level);
	$cc = file_get_contents('./ccs.json');
	$ccs = json_decode($cc, true);

	if (!$ccs[$level][$id]){
		return false;
	}
	$return = $ccs[$level][$id];

	unset($ccs[$level][$id]);
	
	$dsalva = json_encode($ccs,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./ccs.json', $dsalva);


	return $return;


}


function atualiza($chat_id,$bin){

	$searchs = json_decode(file_get_contents("./search.json") , true);
	$ccs = json_decode(file_get_contents("./ccs.json") , true);
	
	$result = [];
	foreach ($ccs as $key => $value) {

		foreach ($value as $cc) {
			$binseach = substr($cc['cc'], 0,6);

			if ((string) $binseach == (string) $bin){
				$result[] = $cc;
			}
		}
	}

	$searchs[$chat_id] = $result;
	$dsalva = json_encode($searchs,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./search.json', $dsalva);

}

function atualizasaldo ($chat_id){

	$dados = json_decode(file_get_contents("./usuarios.json") , true);


	if (!$dados[$chat_id]) {
		return;
	}

	$date = strtotime("now");
	$time;

	if ($dados[$chat_id]['dataLimite']) {
		$time =  $dados[$chat_id]['dataLimite'];
	}

	$time =  $dados[$chat_id]['dataLimite'];

	if ($date > $time){
		$dados[$chat_id]['saldo'] = 0;
		$dados[$chat_id]['dataLimite'] = 0;
	}

	$dsalva = json_encode($dados,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./usuarios.json', $dsalva);
}


function buscaprox ($msg,$busca,$keys){
	$args = explode(" ", $msg['text']);
	$cmd = $args[1];
	$str = $msg['text'];
	
	$vl = [];
	foreach ($keys as $value) {
		$pos  = strpos($str, $value);
		$vl[$value] = $pos;
	}
	arsort($vl);

	$positions = array_keys($vl);
	$ks = array_search($busca, $positions);

	if ($ks == 0){
		$prox = $ks;
	}else{
		$prox = $ks -1;
	}

	$pos1 = $positions[$prox];

	if ($pos1 == $busca){
		$ttt = explode($busca, $str)[1];
	}else{
		$ttt = explode($busca, $str)[1];
		$ttt = explode($pos1, $ttt)[0];
	}

	return $ttt;

}


function selectbase($message){

	$chat_id = $message["chat"]["id"];
	

	$menu =  ['inline_keyboard' => [

		[['text'=>"CC'S BR 🇧🇷",'callback_data'=>"select_br"]],
		[['text'=>"CC'S GRINGAS 🇲🇾",'callback_data'=>"select_gringa"]],
		[['text'=>"🔙 Voltar",'callback_data'=>"volta_loja"]]

	,]];


	$edit = bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => "*Selecione a origem:*","reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
	if (!$edit){
		bot("sendMessage",array( "chat_id"=> $chat_id , "text" => "*Selecione a origem:*","reply_markup" =>$menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
	}
}

function select($message ,$query, $opt){

	$chat_id = $message["chat"]["id"];
	$idquery = $query['id'];

	bot("answerCallbackQuery",array("callback_query_id" => $idquery , "text" => "cc's {$opt} selecionado!!!","show_alert"=> false,"cache_time" => 10));

	$users = json_decode(file_get_contents("./usuarios.json") , true);

	$file = ($opt == "br") ? "brasil" : "gringa";

	$users[$chat_id]['country'] = $file;

	$t = file_put_contents("./usuarios.json", json_encode($users , JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT));

	$menu =  ['inline_keyboard' => [

		[['text'=>"Continuar a compra",'callback_data'=>"ccun"]]

	,]];

	if ($t){
		bot("editMessageText",array( "message_id" => $message['message_id'] , "chat_id"=> $chat_id , "text" => "*Pronto você escolheu: cc's $opt\nPara alterar use: /country*" ,"reply_markup" => $menu,"reply_to_message_id"=> $message['message_id'],"parse_mode" => 'Markdown'));
	}
	
}

function delluser($id){
	$historicocc = json_decode(file_get_contents("./ccsompradas.json") , true);
	$dados = json_decode(file_get_contents("./usuarios.json") , true);
	$historicosaldo = json_decode(file_get_contents("./salcocomprado.json") , true);

	unset($dados[$id]);
	unset($historicosaldo[$id]);
	unset($historicocc[$id]);
	
	$dsalva = json_encode($dados,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva = file_put_contents('./usuarios.json', $dsalva);

	$dsalva2 = json_encode($historicocc,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva2 = file_put_contents('./ccsompradas.json', $dsalva2);

	$dsalva3 = json_encode($historicosaldo,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT );
	$salva3 = file_put_contents('./salcocomprado.json', $dsalva3);
}