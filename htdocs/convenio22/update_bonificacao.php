<?
error_reporting(E_ALL);
ini_set('display_errors', 1);


$mysqli = mysqli_connect('localhost', 'convenio22', 'I5F0e%Z*5Afu', 'convenio22');

// Executa uma consulta que pega cinco notícias
$sql = "SELECT * FROM bonificacao ";
$query = mysqli_query($mysqli, $sql);

while ($dados = mysqli_fetch_array($query)) {
    
    $valor = $dados['valor_inicial'];
    echo $valor;
    echo ' - ';
    $valor = str_replace(['.'],'', $valor);
    $valor = str_replace([','],'.', $valor);

    // echo number_format($dados['valor_inicial'], 2, ',', ' ');
    echo floatval($valor);
    echo '<hr>';
    echo $update = "update bonificacao set valor_inicial1 = $valor where id = ".$dados['id'];
    mysqli_query($mysqli, $update);
    

}