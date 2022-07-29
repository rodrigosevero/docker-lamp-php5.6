<?php
	header("Content-Type: application/vnd.msword");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("content-disposition: attachment;filename=blogson.doc");
	echo "
		<html>
		<meta charset='utf-8'>
		<h1>BLOGSON - O Blog do Prof. Anderson</h1>
		
		<p>
			Este é um <strong>parágrafo<strong>.
		<p>
		
		<p>
			Este é outro parágrafo com a foto do Sergio <br/>
			<img src='https://www.blogson.com.br/wp-content/uploads/2018/08/Sergio-Monte-Verde-204x300.jpg'>
		<p>
		
		<p style='color:blue'>Bye Bye</p>
		
		</html>
 ";     
?> 