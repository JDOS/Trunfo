<?php
namespace Sheep\Traits;

use Sheep\Database\Transaction;
use Sheep\Database\Repository;
use Sheep\Database\Criteria;
use Sheep\Widgets\Dialog\Message;

trait ReloadTrait
{
    /**
     * Carrega a DataGrid com os objetos
     */
    function onReload()
    {
        try
        {
            Transaction::open( $this->connection );
            $repository = new Repository( $this->activeRecord );
            // cria um critério de seleção de dados
            $criteria = new Criteria;
            $criteria->setProperty('order', 'id');
            
            if (isset($this->filter))
            {
                $criteria->add($this->filter);
            }
            
            // carreta os objetos que satisfazem o critério
            $objects = $repository->load($criteria);
            $this->datagrid->clear();
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    // adiciona o objeto na DataGrid
                    $this->datagrid->addItem($object);
                }
            }
            Transaction::close();
        }
        catch (Exception $e)
        {
            new Message($e->getMessage());
        }
    }
}
