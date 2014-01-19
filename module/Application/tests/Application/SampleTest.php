<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Album for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApplicationTest;

use Zend\Validator\Sitemap\Priority;
use Zend\Validator\Sitemap\Lastmod;
use Zend\Validator\Sitemap\Changefreq;

class SampleTest extends Framework\TestCase
{
    public function testNavigationSiteMap()
    {
    	//analyse du fichier navigation.global.php pour le SiteMap
    	$navigation = include __DIR__ . '/../../../../config/autoload/navigation.global.php';
    	$this->verifyFormat($navigation['navigation']['default']);
    }
    /**
     * 
     * @param array $array
     */
    private function verifyFormat($array){
    	//Déclaration des contrôleurs
    	$vPriority 	 = new Priority();
    	$vLastMod  	 = new Lastmod();
    	$vChangeFreq = new Changefreq();
    	foreach($array as $page){
    		if (isset($page['priority']))
   				$this->assertTrue($vPriority($page['priority']),'Priority format error in navigation.global.php for label : '.$page['label']);
    		if (isset($page['lastmod']))
    			$this->assertTrue($vLastMod($page['lastmod']),'Lastmod format error in navigation.global.php for label : '.$page['label']);
    		if (isset($page['changefreq']))
	    		$this->assertTrue($vChangeFreq($page['changefreq']),'Changefreq format error in navigation.global.php for label : '.$page['label']);
    		if (isset($page['pages']))
    			$this->verifyFormat($page['pages']);
    	}
    }
}
