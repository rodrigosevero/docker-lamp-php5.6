<?php
require_once('../blockchain.php');


/*
Set up a simple chain and mine two blocks.
*/

$testCoin = new BlockChain();

echo "mining block 1...\n";
$testCoin->push(new Block(1, strtotime("now"), "amount: 4"));
echo '<br>';
echo "mining block 2...\n";
$testCoin->push(new Block(2, strtotime("now"), "amount: 10"));
echo '<hr>';
$json = json_encode($testCoin, JSON_PRETTY_PRINT);
echo '<pre>';
print_r($json);
echo '</pre>';

