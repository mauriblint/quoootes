<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class QuotesAuthorsTable extends Table
{
    public function initialize(array $config) : void
    {
        $this->addBehavior('Timestamp');
        $this->hasMany('Quotes');
    }

}

?>