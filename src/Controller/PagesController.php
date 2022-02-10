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
    	$recentImages = DesignService::getRecentImagesQuotes(1, 16);

    	$this->set('recentImages', $recentImages);
        $this->render('home');
    }

    public function templates()
    {
    	$recentImages = null;

    	$this->set('templates', TemplatesService::list(null, 1, 30));
        $this->render('templates');
    }
}
