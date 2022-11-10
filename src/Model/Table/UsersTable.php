<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\Event;
use ArrayObject;

class UsersTable extends Table
{
    public function initialize(array $config) : void
    {
        $this->addBehavior('Timestamp');
    }

}

?>