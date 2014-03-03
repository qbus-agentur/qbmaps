<?php
namespace Qbus\Qbmaps\ViewHelpers\Widget;

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
 */
class MapsViewHelper extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper 
{
	/**
	 * @var \Qbus\Qbmaps\ViewHelpers\Widget\Controller\MapsController
	 */
	protected $controller;

	/**
	 * @param \Qbus\Qbmaps\ViewHelpers\Widget\Controller\MapsController $controller
	 * @return void
	 */
	public function injectController(\Qbus\Qbmaps\ViewHelpers\Widget\Controller\MapsController $controller) {
		$this->controller = $controller;
	}

	/**
	 * @param $settings the maps settings
	 * @return string
	 */
	public function render(array $settings) 
	{
		return $this->initiateSubRequest();
	}
}