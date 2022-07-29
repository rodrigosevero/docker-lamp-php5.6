<?php
session_start();
$link = mysqli_connect("localhost", "convenio21", "dTNMISs4oPOX6JfU", "convenio21");

if (!$link) {
    echo "Error: Falha ao conectar-se com o banco de dados MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}


if ($_POST){

$email = isset($_POST["username"]) ? addslashes(trim($_POST["username"])) : FALSE;
// Recupera a senha, a criptografando em MD5
$senha = isset($_POST["password"]) ? $_POST["password"] : FALSE;


$sql = "SELECT * FROM usuario WHERE del = 0 and cpf ='".$email."' and senha = '".md5($senha)."'";
$res = mysqli_query($link, $sql);
$total = mysqli_num_rows($res);

// Caso o usuário tenha digitado um login válido o número de linhas será 1..
if($total>0)
{
    // Obtém os dados do usuário, para poder verificar a senha e passar os demais dados para a sessão
		$dados = mysqli_fetch_array($res);


	    // Agora verifica a senha
        // TUDO OK! Agora, passa os dados para a sessão e redireciona o usuário
		$nome = explode(" ",$dados['nome']);$firstname = $nome[0];unset($nome[0]);$lastname= implode(" ", $nome);
		$_SESSION['usuario_id']		= $dados['id'];
		$_SESSION['nome']	= $dados['nome'];
		$_SESSION['cpf']	= $dados['cpf'];
		$_SESSION['superadmin']	= $dados['superadmin'];
		$_SESSION['email']	= $dados['email'];
		$_SESSION['permissao']	= $dados['permissao'];
		$_SESSION['vinculo']	= $dados['vinculo'];
		$_SESSION['area_id']	= $dados['area_id'];
		$_SESSION['flag']	= 1;


		header('Location: https://setec.ufmt.br/tce/ava-tce/logar.php?username='.base64_encode($_SESSION['cpf']).'&firstname='.base64_encode($firstname).'&lastname='.base64_encode($lastname).'&email='.base64_encode($_SESSION['email']).'');


		exit;





    } else {


			$sql1 = "SELECT * FROM participantes WHERE del = 0 and cpf ='".$email."' and senha = '".md5($senha)."'";
			$res1 = mysqli_query($link, $sql1);
			$total1 = mysqli_num_rows($res1);

			// Caso o usuário tenha digitado um login válido o número de linhas será 1..
			if($total1>0)
			{

		$dados1 = mysqli_fetch_array($res1);
		//print_r($dados1);die;
		$nome = explode(" ",$dados1['nome']);$firstname = $nome[0];unset($nome[0]);$lastname= implode(" ", $nome);
		$_SESSION['usuario_id']		= $dados1['id'];
		$_SESSION['nome']	= $dados1['nome'];
		$_SESSION['cpf']	= $dados1['cpf'];
		$_SESSION['superadmin']	= $dados1['superadmin'];
		$_SESSION['email']	= $dados1['email'];
		$_SESSION['permissao']	= $dados1['permissao'];
		$_SESSION['vinculo']	= $dados1['vinculo'];
		$_SESSION['area_id']	= $dados1['area_id'];
		$_SESSION['flag']	= 1;


		header('Location: https://setec.ufmt.br/tce/ava-tce/logar.php?username='.base64_encode($_SESSION['cpf']).'&firstname='.base64_encode($firstname).'&lastname='.base64_encode($lastname).'&email='.base64_encode($_SESSION['email']).'');


		exit;


			}






	}

	echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('N\u00e3o foi possivel fazer o login, verifique seu usu\u00E1rio e senha. Obrigado')
    window.location.href='https://setec.ufmt.br/tce/ava-tce/login/index.php';
    </SCRIPT>");


   }


?>
