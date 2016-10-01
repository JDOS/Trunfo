<?php
date_default_timezone_set('America/Sao_Paulo');

// Lib loader
require_once 'Lib/Sheep/Core/ClassLoader.php';
$al= new Sheep\Core\ClassLoader;
$al->addNamespace('Sheep', 'Lib/Sheep');
$al->register();

// App loader
require_once 'Lib/Sheep/Core/AppLoader.php';
$al= new Sheep\Core\AppLoader;
$al->addDirectory('App/Control');
$al->addDirectory('App/Model');
$al->register();

$template = file_get_contents('App/Templates/principal.html');
$content = '';
$class   = 'Home';

$info = 10;
$log = 7;
$adm = 4;

if ($_GET)
{
    $class = $_GET['class'];
    if (class_exists($class))
    {
        try
        {
            $pagina = new $class;
            ob_start();//inicia controle de output
            $pagina->show();//exibe pagina
            $content = ob_get_contents();//le conteudo gerado
            ob_end_clean();//finaliza controle de output
        }
        catch (Exception $e)
        {
            $content = $e->getMessage() . '<br>' .$e->getTraceAsString();
        }
    }
	else {
		$content='testsesetas';
	}
}


//injeta conteudo gerado dentro do template
$output = str_replace('{content}', $content, $template);
$output = str_replace('{class}',   $class, $output);
$output = str_replace('{info}',   $info, $output);
$output = str_replace('{log}',   $log, $output);
$output = str_replace('{adm}',   $adm, $output);

echo $output;
//$echo '$template';