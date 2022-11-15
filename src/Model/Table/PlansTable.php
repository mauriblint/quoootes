<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\Event;
use ArrayObject;

class PlansTable extends Table
{
    public function initialize(array $config)  : void
    {
        $this->addBehavior('Timestamp');
        // $this->hasMany('Features')
        //     ->setBindingKey('slug')
        //     ->setForeignKey('plan_slug');
    }
}

?>