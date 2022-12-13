<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class QuotesTable extends Table
{
    public function initialize(array $config) : void
    {
        $this->addBehavior('Timestamp');
        $this->belongsTo('QuotesAuthors')->setForeignKey('author_id');
        $this->belongsTo('Tags')->setForeignKey('tag_id');
        $this->addBehavior('CounterCache', [
            'Tags' => ['quotes_count'],
        ]);
    }

}

?>