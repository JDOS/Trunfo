<?php
use Sheep\Control\Page;
use Sheep\Control\Action;
use Sheep\Widgets\Form\Form;
use Sheep\Widgets\Form\Entry;
use Sheep\Widgets\Form\Password;
use Sheep\Widgets\Form\Button;
use Sheep\Widgets\Wrapper\DatagridWrapper;
use Sheep\Widgets\Wrapper\FormWrapper;
use Sheep\Widgets\Container\Panel;

use Sheep\Session\Session;

/**
 * Formulário de Login
 */
class LoginForm extends Page
{
    private $form; // formulário
    
    /**
     * Construtor da página
     */
    public function __construct()
    {
        parent::__construct();

        // instancia um formulário
        $this->form = new FormWrapper(new Form('form_login'));
        
        $login      = new Entry('login');
        $password   = new Password('password');
        
        //$login->placeholder    = 'admin';
        //$password->placeholder = 'admin';
        
        $this->form->addField('Login',    $login,    200);
        $this->form->addField('Senha',    $password, 200);
        $this->form->addAction('Login', new Action(array($this, 'onLogin')));
        
        $panel = new Panel('Login');
        $panel->add($this->form);
        
        // adiciona o formulário na página
        parent::add($panel);
    }
    
    /**
     * Login
     */
    public function onLogin($param)
    {
        $data = $this->form->getData();
        if ($data->login == 'admin' AND $data->password == '!@info34')
        {
            Session::setValue('logged', TRUE);
            echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
        }
    }
    
    /**
     * Logout
     */
    public function onLogout($param)
    {
        Session::setValue('logged', FALSE);
        echo "<script language='JavaScript'> window.location = 'index.php'; </script>";
    }
}
