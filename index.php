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

use Sheep\Session\Session;

$content = '';
$class = '';
new Session;
if (Session::getValue('logged')) {
    $template = file_get_contents('App/Templates/gerenciamento.html');
    $class = 'CursoList';
}
if(!isset($_GET['class'])){
    $template = file_get_contents('App/Templates/Principal.html');
    $class='View';
}

if(isset($_GET['class']) AND ($_GET['class']=='View')||(($_GET['class']=='About'))){
    $template = file_get_contents('App/Templates/Principal.html');
    $class=$_GET['class'];
}
else {
    
	if (isset($_GET['class']) AND ($_GET['class']!='CardList') ){
			$inject=$_GET['class'];
			$template = file_get_contents('App/Templates/login.html');
			$class = 'LoginForm';
	}
    
}

if (isset($_GET['class']) AND Session::getValue('logged'))
{
    $template = file_get_contents('App/Templates/gerenciamento.html');
    $class = $_GET['class'];
}

if (class_exists($class))
{
    try
    {
        $pagina = new $class;
        ob_start();
        $pagina->show();
        $content = ob_get_contents();
        ob_end_clean();
    }
    catch (Exception $e)
    {
        $content = $e->getMessage() . '<br>' .$e->getTraceAsString();
    }
}
$output = str_replace('{content}', $content, $template);
$output = str_replace('{class}',   $class, $output);
echo $output;
