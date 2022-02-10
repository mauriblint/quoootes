<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TemplatesTable extends Table
{
    public function initialize(array $config) : void
    {
        $this->addBehavior('Timestamp');
        // $this->belongsTo('Frases');
        // $this->belongsTo('Tags');
        // $this->belongsToMany ('Tags', [
        //     'joinType' => 'INNER'
        // ]);
        // $this->addBehavior('CounterCache', [
        //     'Frases' => ['quotes_count'],
        // ]);
    }

}

?>