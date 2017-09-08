<?php
namespace Qbus\Qbmaps\Controller;

class MapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/*
	 * @return void
	 */
	public function showAction() {
		$geoCoordinates = array();
		foreach ($this->settings['coordinates'] as $key => $coordinate) {
			$address = $coordinate['item']['address'];
			$tmp = explode(',', $coordinate['item']['coordinate'], 2);
			if (count($tmp) != 2 && strlen($address) == 0)
				continue;
			if (count($tmp) != 2) {
				$tmp = array(NULL, NULL);
			}
			$geoCoordinates[] = array('lat' => $tmp[0], 'long' => $tmp[1],
						  'address' => $address,
						  'data' => $coordinate['item']['description'],
						  'id' => $key);
		}
//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($geoCoordinates);
		
		$this->view->assign('geoCoordinates', $geoCoordinates);
	}
}
?>
