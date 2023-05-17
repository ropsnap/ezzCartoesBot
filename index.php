<?php	
    
	$atualdados = json_decode(file_get_contents("bot/resource/conf.json"));

?>
//uuuuuuuhhyyyyyyy

<!DOCTYPE html>
<html>
<head>
	<title>CONFIG BOT</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" >
</head>
<body style="background-color: #941659">
<br>

<div id="alert" class="alert alert-success alert-dismissible fade show col-md-6" style="left: 15px;" role="alert">
  Dados Atualizados !
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<center class="content text-left col-md-auto">
	<div class="card" >
	  <div class="card-header">
	    <b>CONFIGURAÇÕES ATUAIS</b>
	  </div>
	  <div class="card-body">
	    
	    <p class="card-text"><b>ID TELGRAM:</b> <?php echo $atualdados->dono?></p>
	    <p class="card-text"><b>TOKEN BOT:</b> <?php echo file_get_contents('bot/token.txt')?></p>
	    <p class="card-text"><b>mensagem de boas vindas:</b> <?php echo $atualdados->welcome?></p>
	    <p class="card-text"><b>USER TELGRAM:</b> <?php echo $atualdados->userDono?></p>


	  </div>
	</div>
	<br>
	<div class="card">
		<div class="card-header">
			<b>ATUALIZA DADOS</b>
		</div>
		<div class="card-body">
			<label for="id_telegram">ID TELEGRAM</label>
			<input type="text"name="id_telegram" id="id_telegram" class="form-control col-4" value="<?php echo $atualdados->dono?>">

			<label for="username">USERNAME</label>
			<input type="text"name="username" id="username" class="form-control col-4" value="<?php echo $atualdados->userDono?>">

    <label for="boasvindas">MSG DE BOAS VINDAS</label>
			<input type="text"name="username" id="username" class="form-control col-4" value="<?php echo $atualdados->welcome?>">

			<label for="tokenbpt">TOKEN BOT</label>
			<input type="text"name="tokenbpt" id="tokenbpt" class="form-control col-4" value="<?php echo file_get_contents('bot/token.txt')?>">
			<br>
			<button type="button" class="btn btn-primary" id="salva">SALVA</button>
<Br>
<Br>
 <b>Baixa backup dos users: 	<a href="./bot/usuarios.json" download>baixa backup dos clientes</a>
<Br>
<b>Baixa backup do saldo comprado: 	<a href="./bot/salcocomprado.json" download>baixa backup dos clientes</a>
<Br>
<b>Baixa backup dos valor das cc: 	<a href="./bot/resource/conf.json" download>baixa backup dos clientes</a>
<Br>
<b>Baixa backup dos gifts: 	<a href="./bot/gifts.json" download>baixa backup dos gifts gerados</a>
<br>
<b>Baixa backup das cc compradas: 	<a href="./bot/ccsompradas.json" download>baixa backup das comprar de ccs</a>
<Br>
<Br>
<b> BACKUP DAS CCS <b>
<br>
<Br>
<b>classic: 	<a href="./bot/ccs/brasil/classic.json" download>baixa backup</a>
<Br>
<b>standard: 	<a href="./bot/ccs/brasil/standard.json" download>baixa backup</a>
<Br>
<b>black: 	<a href="./bot/ccs/brasil/black.json" download>baixa backup</a>
<Br>
<b>gold: 	<a href="./bot/ccs/brasil/gold.json" download>baixa backup</a>
<Br>
<b>corporate: 	<a href="./bot/ccs/brasil/corporate.json" download>baixa backup</a>
<Br>
<b>business: 	<a href="./bot/ccs/brasil/business.json" download>baixa backup</a>
<Br>
<b>executive: 	<a href="./bot/ccs/brasil/executive.json" download>baixa backup</a>
<Br>
<b>Platinum: 	<a href="./bot/ccs/brasil/platinum.json" download>baixa backup</a>
<Br>
<Br>
		</div>
	</div>
	<br>
	<div class="card">
		<div class="card-header">
			<b>PARA ATIVA O BOT CLICK NO BOTAO ABAIXO !</b>
		</div>
		<div class="card-body">
			
			<a class="btn btn-primary" href = "bot/setwebhook.php">ATIVA</a>

		</div>
	</div>
	<br><br>

</center>



<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://kit.fontawesome.com/4b324138d1.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>

<script type="text/javascript">
	$("#alert").fadeOut(0);
	$("#salva").click(function () {
		
		$.ajax({
			url: "./atr.php/",
			type: "GET",
			data:{
				id_telegram: $("#id_telegram").val(),
				username: $("#username").val(),
				tokenbot: $("#tokenbpt").val(),
			},success:function(data){
				$("#alert").fadeIn(0);
			}

		});
	});	
</script>

</body>

</html>