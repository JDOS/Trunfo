<?php
namespace Sheep\Traits;

use Sheep\Database\Transaction;
use Sheep\Widgets\Dialog\Message;

trait EditTrait
{
    /**
     * Carrega registro para edição
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['id']))
            {
                $id = $param['id']; // obtém a chave
                Transaction::open( $this->connection ); // inicia transação com o BD
                
                $class = $this->activeRecord;
                $object = $class::find($id); // instancia o Active Record
                $this->form->setData($object); // lança os dados no formulário
                Transaction::close(); // finaliza a transação
            }
        }
        catch (Exception $e)
        {
            new Message('error', '<b>Erro</b>' . $e->getMessage());
            Transaction::rollback();
        }
    }
}
