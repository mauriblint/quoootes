<?php
namespace App\Controller;

use App\Lib\BackgroundService;
use App\Lib\UploadService;
use App\Lib\DesignService;
use App\Lib\QuoteService;
use App\Lib\TemplatesService;

set_time_limit(900);
require_once(ROOT . DS . 'src' . DS . "Utility" . DS . "simple_html_dom.php");

class ScraperController extends AppController
{

    public function quotable()
    {
        // Last Scrape:
        // 2038 Quotes in 14 Pages

        $query = array(
            'limit' => 150,
            'sortBy' => 'dateAdded',
            'order' => 'asc',
            'page' => $this->request->getQuery('page', 1),
        );

        $data = json_decode(file_get_contents('https://api.quotable.io/quotes?' . http_build_query($query)), true);

        foreach($data['results'] as $quote) {
            $is_famous = false;
            $tag = $quote['tags'][0];
            $hashtags = implode(' ', $quote['tags']);

            if (strpos($hashtags, 'famous-quotes') !== false) {
                $is_famous = true;
            }

            if ($quote['tags'][0] === 'famous-quotes') {
                $tag = (count($quote['tags'])  > 1 ) ? $quote['tags'][1] : $quote['tags'][0];
            }

            $quote_data = array(
                'content' => $quote['content'],
                'char_count' => $quote['length'],
                'vendor' => 'quotable.io',
                'tag' => $tag,
                'is_famous' => $is_famous,
                'hashtags' => $hashtags,
                'author' => (isset($quote['author']) && !empty($quote['author'])) ? $quote['author'] : null,
            );

            $result = QuoteService::create($quote_data);

            if (isset($result['id'])) {
                echo 'Quote #' . $result['id'] . ' created: ' . $result['content'] . '<br>';
            }
        }

        unset($data['results']);
        var_dump($data);
        die;
    }

    public function apininjas()
    {
        // Last Scrape:
        // 2038 Quotes in 14 Pages

        // $query = array(
        //     'limit' => 150,
        //     'sortBy' => 'dateAdded',
        //     'order' => 'asc',
        //     'page' => $this->request->getQuery('page', 1),
        // );


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.api-ninjas.com/v1/quotes?limit=10',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'X-Api-Key: alMucefp+fuInHRkgMSigw==Qw0jezKaBEgeOLjB'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response, true);

        foreach($data as $quote) {
            $quote_data = array(
                'content' => $quote['quote'],
                // 'char_count' => strlen($quote['quote']),
                'vendor' => 'api-ninjas.com',
                'tag' => $quote['category'],
                'author' => (isset($quote['author']) && !empty($quote['author'])) ? $quote['author'] : null,
            );

            $result = QuoteService::create($quote_data);

            if (isset($result['id'])) {
                echo 'Quote #' . $result['id'] . ' created: ' . $result['content'] . '<br>';
            }
        }

        // var_dump($data);
        die;
    }

    // URL Example:
    // https://dev.quoootes.com/scraper/brainyquote?url=https://www.brainyquote.com/topics/motivational-quotes_[PAGE]&tag=motivational&page=5
    public function brainyquote () {
        $url = $this->getRequest()->getQuery('url');
        $page = $this->getRequest()->getQuery('page');
        $tag = $this->getRequest()->getQuery('tag');

        $__url = str_replace('[PAGE]', $page, $url);

        $next_url = "https://";
        $next_url .= $_SERVER['HTTP_HOST'];
        $next_url .= $_SERVER['REQUEST_URI'];
        $next_url = str_replace('page=' . $page, 'page=' . ($page+1), $next_url);

        $results = $this->_parseBrainQuote($__url);

        $total_quotes_added = 0;
        echo "<a href='$next_url'>$next_url</a> <br><br>";

        foreach($results as $quote) {
            $quote_data = array(
                'content' => $quote['text'],
                'vendor' => 'brainyquote.com',
                'external_url' => $quote['external_url'],
                'tag' => $tag,
                'author' => (isset($quote['author']) && !empty($quote['author'])) ? $quote['author'] : null,
            );

            $result = QuoteService::create($quote_data);

            if (isset($result['id'])) {
                echo 'Quote #' . $result['id'] . ' created: ' . $result['content'] . '<br>';
                $total_quotes_added++;
            }
        }

        if ($total_quotes_added > 0) {
            echo "<meta http-equiv=\"refresh\" content=\"5; url='" . $next_url . "'\" />";
        }
        die;
    }


    private function _parseBrainQuote($url) {
        $html = file_get_html($url);
        $results = [];
        $count = 0;

        foreach($html->find('.grid-item') as $element) {
            $data = array();
            $quote = $element->find('.b-qt', 0);
            if (!isset($quote->plaintext)) {
                continue;
            }
            $data['text'] = $quote->plaintext;
            $data['author'] = $element->find('.bq-aut', 0)->plaintext;
            $data['external_url'] = 'brainyquote.com' . $element->find('a', 0)->href;

            $results[] = $data;
        }

        return $results;


        // $quote = $html->find('.moreText strong', 1)->plaintext;
        // $cat = str_replace('Frases de ', '', $html->find('.catItem span a', 0)->plaintext);
        // $cat = str_replace('Frases del ', '', $cat);
        // $cat = str_replace('Frases ', '', $cat);
        // $cat_slug = $this->slugify($cat);
        // $autor_name = @trim($html->find('.autorItem', 0)->plaintext);
        // $autor_slug = $this->slugify($autor_name);
        // $vendor_id = explode('-', $url);
        // $vendor_id = trim(array_pop($vendor_id));
    }

}
