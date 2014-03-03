<?php
namespace Qbus\Qbmaps\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Axel Wüstemann <awu@qbus.de>, Qbus Werbeagentur GmbH
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 * @package qbMaps
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * 
 * Displays a Google Map with a marked point
 * 
 * usage: <qb:gmaps coordinates="{geoCoordinates}" zoom="10" width="600px" height="300px" />
 *
 */
class GmapsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {
	
	const GAPI			= 'http://maps.google.com/maps/api/js?v=3&sensor=false';
	const TEMPLATE_JS	= 'Static/Gmaps.js';
	const TEMPLATE_CSS  = 'Static/Gmaps.css';
	
	/** 
	 * @var \Qbus\Qbtools\Utility\StandaloneTemplateRenderer
	 * @inject
	 */
	protected $standaloneTemplateRenderer;
    
    /** 
	 * @var \Qbus\Qbmaps\Utility\GeneralUtility
	 * @inject
	 */
	protected $qbmapsGeneralUtility;
    
    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject 
     */
    protected $configurationManager;
	
	/**
	 * Argument initalization
	 */
	public function initializeArguments()
	{
		$this->registerArgument('coordinates', 'array', 'array of lat,long data (lat,long of one point is a comma separated string');
		$this->registerArgument('zoom', 'string', 'The zoom level of the map', FALSE, '10');
		$this->registerArgument('width', 'string', 'The width of the map canvas', FALSE, '400px');
		$this->registerArgument('height', 'string', 'The height of the map canvas', FALSE, '300px');
        $this->registerArgument('mapsParameter', 'array', 'Google Maps parameter as descripted in the API');
        $this->registerArgument('markeronevent', 'string', 'Which event shout open a marker - click|mousover', FALSE, 'mouseover');
        $this->registerArgument('markeroffevent', 'string', 'Which event shout close a marker - click|mouseout', FALSE, 'mouseout');
	}
	
	/**
	 * returns a gmap div container <div id="map-canvas"></div>
	 * filled with a gmap card
	 *
	 * @return \string
	 */
	public function render() 
	{
		// get params
		$geoCoordinates = $this->arguments['coordinates'];
        $centerOfMap = $this->qbmapsGeneralUtility->calculateCenterOfMap($geoCoordinates);
        if (count($geoCoordinates) == 0) return '';
       

		// parameters to be passed to the javascript and css (to css only height and width)
		$params  = array (
            'mapId' => '',
			'locations'	 => $geoCoordinates,
            'center' => $centerOfMap,
			'zoom'	 => $this->arguments['zoom'],
			'width'	 => $this->arguments['width'],
			'height' => $this->arguments['height'],
            'markeronevent' => $this->arguments['markeronevent'],
            'markeroffevent' => $this->arguments['markeroffevent'],
		);
		
		// render the static js and css templates with the given parameters
        $templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:qbmaps/Resources/Private/');
		$js  = $this->standaloneTemplateRenderer->renderTemplate(self::TEMPLATE_JS, $params, $templateRootPath);
		$css = $this->standaloneTemplateRenderer->renderTemplate(self::TEMPLATE_CSS, $params, $templateRootPath);
		
		// put JS and CSS into the page head
        $extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('qbmaps');
		$GLOBALS['TSFE']->additionalHeaderData[md5('qb_qbmaps_api')]  = '<script type="text/javascript" src="' . self::GAPI . '"></script>';
        $GLOBALS['TSFE']->additionalHeaderData[md5('qb_gmaps3_lib')] = '<script type="text/javascript" src="' . $extPath . 'Resources/Public/JavaScript/gmap3v5.1.1/gmap3.min.js" /></script>';  
		$GLOBALS['TSFE']->additionalHeaderData[md5('qb_qbmaps_js')]   = \TYPO3\CMS\Core\Utility\GeneralUtility::wrapJS($js);
		$GLOBALS['TSFE']->additionalHeaderData[md5('qb_qbemaps_css')] = '<style type="text/css">' . $css . '</style>';
 
		// return the div container
		return '<div id="map-canvas"></div>';
	}
}