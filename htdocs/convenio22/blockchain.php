<?php  
	# Exemplo de funcionamento blockchain (simplificado)

	$blockchain = array(); # Criando a variavel que seria a corrente

	# Criando os blocos de informações (simulando)
	$bloco_1 = [
		"transacoes" => [
			[
				"Remetente" => "Day_0",
				"Destinatario" => "Manotzy",
				"Mensagem" => "20 BTC"
			],
			[
				"Remetente" => "Manotzy",
				"Destinatario" => "Rudy",
				"Mensagem" => "5 BTC"
			],
			[
				"Remetente" => "Roy Musthang",
				"Destinatario" => "Rudy",
				"Mensagem" => "0.2 BTC"
			]			
		]
	];

	$bloco_2 = [
		"transacoes" => [
			[
				"Remetente" => "Rudy",
				"Destinatario" => "Day_0",
				"Mensagem" => "1 BTC"
			],
			[
				"Remetente" => "SAD CLOWN",
				"Destinatario" => "Manotzy",
				"Mensagem" => "5 BTC"
			]			
		]
	];	


	$bloco_3 = [
		"transacoes" => [
			[
				"Remetente" => "santana",
				"Destinatario" => "SAD CLOWN",
				"Mensagem" => "1 BTC"
			]		
		]
	];		

	$bloco_4 = [
		"transacoes" => [
			[
				"Remetente" => "D4RK",
				"Destinatario" => "lenny",
				"Mensagem" => "50 BTc"
			], 
			[
				"Remetente" => "MythPentester",
				"Destinatario" => "Manotzy",
				"Mensagem" => "100 BTc"
			],
			[
				"Remetente" => "SAD CLOWN",
				"Destinatario" => "Day_0",
				"Mensagem" => "0.5 BTc"
			],
			[
				"Remetente" => "santana",
				"Destinatario" => "Day_0",
				"Mensagem" => "0.001 BTC"
			]
		]
	];

	$bloco_5 = [
		"transacoes" => [
			[
				"Remetente" => "Manotzy",
				"Destinatario" => "Rudy",
				"Mensagem" => "0.0005 BTC"
			],
			[
				"Remetente" => "Rudy",
				"Destinatario" => "Manotzy",
				"Mensagem" => "0.0001 BTC"
			]
		]
	];

	# Os dados não precisam ser necessariamente esses, podemos passar/usar qualquer informação

	# Funçaõ que insere o bloco na corrente de blocos
	Function addblock($bloco_novo){

		# Chama a variavel global
		global $blockchain;		

		# Verifica se a blockchain esta vazia
		if ($blockchain == array()) {
			# Caso afirmativo, cria o primeiro bloco
			$bloco_novo["hash"] = hash("sha256", json_encode($bloco_novo));
		}else{
			# Senão, pega o ultimo bloco
			$ultimo_bloco = end($blockchain);

			# Add a hash anterior
			$bloco_novo["hash"] = $ultimo_bloco["hash"];

			# Altera o hash do bloco
			$bloco_novo["hash"] = hash("sha256", json_encode($bloco_novo));
		}
		array_push($blockchain, $bloco_novo);
	}

	# Chamando a função
	addblock($bloco_1);
	addblock($bloco_2);
	addblock($bloco_3);
	addblock($bloco_4);
	addblock($bloco_5);

	# Exibir
	echo "<h1>Resultado da blockchain</h1>";
	foreach ($blockchain as $key => $bloco) {
		$posicao = $key + 1;
		echo "Bloco: #".$posicao." - ".$bloco['hash']."<br>";
		foreach ($bloco['transacoes'] as $transacoes) {
			echo " - Tx: ".$transacoes['Remetente']." -> ". $transacoes['Destinatario'] . " - " . $transacoes['Mensagem'] . "<br>";
		}
		echo "<br><br>";
	}
	
	exit;
?>
