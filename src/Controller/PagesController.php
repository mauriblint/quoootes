<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Lib\DesignService;
use App\Lib\TemplatesService;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    public function homepage()
    {
    	// $this->set('recentImages', DesignService::getRecentImagesQuotes(1, 16));
    	$this->set('recentImages', []);
        $this->set('templates', TemplatesService::list(null, 1, 8));
        $this->render('home');
    }

    public function templates()
    {
    	$recentImages = null;

    	$this->set('title', 'Quotes Templates');
    	$this->set('templates', TemplatesService::list(null, 1, 30));
        $this->render('templates');
    }

    public function embed($id)
    {
    	$Design = DesignService::get($id);

        $widget_version = (int) $this->request->getQuery('v', '1.0');

        if (!$Design) {
            echo 'Quote not found';
        }

    	$this->set('title', 'Embed Quote');
    	$this->set('widget_version', $widget_version);
    	$this->set('design', $Design);
        $this->render('embed');
    }
}
