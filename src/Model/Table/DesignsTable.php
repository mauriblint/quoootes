<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Event\Event;
use ArrayObject;

class DesignsTable extends Table
{
    public function initialize(array $config) : void
    {
        $this->addBehavior('Timestamp');
        $this->belongsTo('Tags');
        // $this->belongsTo('Frases');
        // $this->belongsToMany ('Tags', [
        //     'joinType' => 'INNER'
        // ]);
        // $this->addBehavior('CounterCache', [
        //     'Frases' => ['quotes_count'],
        // ]);
    }

}

?>