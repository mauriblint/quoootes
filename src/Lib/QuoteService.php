<?php
namespace App\Lib;

use Cake\ORM\TableRegistry;

class QuoteService {

    public static function create ($data) {

        $QuotesTable = TableRegistry::getTableLocator()->get('Quotes');

        $hash = md5($data['content']);

        $exits = $QuotesTable->find()->where(['hash' => $hash])->first();

        if ($exits) {
            return false;
        }

        if (isset($data['author'])) {
            $Author = self::getAuthorByName($data['author']);
        }

        if (isset($data['tag'])) {
            $Tag = self::getTagByName($data['tag']);
        }

        $content = trim( html_entity_decode($data['content'], ENT_QUOTES | ENT_XML1, 'UTF-8'));
        $Quote = $QuotesTable->newEmptyEntity();
        $Quote->content = $content;
        $Quote->tag_id = (isset($Tag)) ? $Tag['id'] : null;
        $Quote->author_id = (isset($Author)) ? $Author['id'] : null;
        $Quote->vendor = (isset($data['vendor'])) ? $data['vendor'] : null;
        $Quote->external_url = (isset($data['external_url'])) ? $data['external_url'] : null;
        $Quote->hashtags = (isset($data['hashtags'])) ? $data['hashtags'] : null;
        $Quote->is_famous = (isset($data['is_famous'])) ? $data['is_famous'] : 0;
        $Quote->char_count = strlen($content);
        $Quote->hash = $hash;
        $QuotesTable->save($Quote);

        return $Quote;
    }

    public static function search ($q=null, $tag_id=null, $page=1, $count=40) {

        // if (!$q) {
        //     return self::getRandomList();
        // }

        $query = TableRegistry::getTableLocator()->get('Quotes')
            ->find()
            ->contain(['Tags', 'QuotesAuthors'])
            ->select(['id', 'content', 'tag_id', 'author_id', 'QuotesAuthors.name', 'Tags.name']);

        if ($q) {
            $query->where(['Quotes.content LIKE' => '%'.$q.'%']);
        }

        if ($tag_id) {
            $query->where(['Quotes.tag_id' => $tag_id]);
        }

        $query->order(['RAND()'])
            ->page($page)
            ->limit($count)
            ->enableHydration(false)
            ->toArray();

        return $query;
    }

    public static function getRandomList ($page=1, $count=40) {

        $query = TableRegistry::getTableLocator()->get('Quotes')
            ->find('all')
            ->select(['id', 'content', 'tag_id', 'author_id', 'QuotesAuthors.name', 'Tags.name'])
            ->order(['RAND()'])
            ->contain(['Tags', 'QuotesAuthors'])
            ->enableHydration(false)
            ->limit($count)
            ->toArray();

        return $query;
    }

    public static function getTagByName ($name) {
        $TagsTable = TableRegistry::getTableLocator()->get('Tags');

        $slug = self::slugify($name);

        $Tag = $TagsTable->find()
            ->where(['slug' => $slug])
            ->first();

        if (!$Tag) {
            $Tag = $TagsTable->newEmptyEntity();
            $Tag->name = ucfirst($name);
            $Tag->name_seo = ucfirst($name) . ' Quotes';
            $Tag->slug = $slug;
            $TagsTable->save($Tag);
        }

        return $Tag;
    }

    public static function getAuthorByName ($name) {
        $QuotesAuthorsTable = TableRegistry::getTableLocator()->get('QuotesAuthors');

        $slug = self::slugify($name);

        $Author = $QuotesAuthorsTable->find()
            ->where(['slug' => $slug])
            ->first();

        if (!$Author) {
            $Author = $QuotesAuthorsTable->newEmptyEntity();
            $Author->name = $name;
            $Author->slug = $slug;
            $QuotesAuthorsTable->save($Author);
        }

        return $Author;
    }

    public static function slugify ($str, $replace=array(), $delimiter='-', $limit=120)
    {
        if (strlen($str) > $limit)
        {
            $str = wordwrap($str, $limit);
            $str = substr($str, 0, strpos($str, "\n"));
        }

        $words = array('in', 'at', 'on', 'the', 'this','of', 'is');
        $pattern = '/\b(?:' . join('|', $words) . ')\b/i';
        $str = preg_replace($pattern, '', $str);


        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        $clean = trim($clean, '-');

        return $clean;
    }

    public static function fixTextFormat () {
        $QuotesTable = TableRegistry::getTableLocator()->get('Quotes');
        $query = $QuotesTable
            ->find('all')
            ->order(['Quotes.id' => 'ASC'])
            ->limit(10000)
            ->page(6);

        foreach ($query as $Quote) {
            $Quote->content = trim( html_entity_decode($Quote->content, ENT_QUOTES | ENT_XML1, 'UTF-8'));
            $Quote->char_count = strlen($Quote->content);
            $QuotesTable->save($Quote);

            echo $Quote->id . ' updated </br>';
        }

        die;
    }

}
?>
