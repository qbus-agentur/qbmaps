<?php
namespace Qbus\Qbmaps\Service;

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
 * API Functions for MapQuest services
 * 
 * @package qbMaps
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class MapQuestService 
{
	/**
	 * http://developer.mapquest.com/web/products/open
	 */
	const MAPQUEST_APP_KEY = 'Fmjtd%7Cluubn9u825%2C7a%3Do5-902llz';
	
	/**
	 * http://open.mapquestapi.com/directions/v2/route?key=[YOUR_KEY_HERE]&from={latLng:{lat:54.0484068,lng:-2.7990345}}&to={latLng:{lat:53.9593817,lng:-1.0814175}}
	 */
	const MAPQUEST_URL_DIRECTIONS = 'http://open.mapquestapi.com/directions/v2/route';
	
	/**
	 * http://open.mapquestapi.com/geocoding/v1/address?key=YOUR_KEY_HERE&location=Lancaster,PA&callback=renderGeocode
	 */
	const MAPQUEST_URL_ADDRESS = 'http://open.mapquestapi.com/geocoding/v1/address';
	
	
	/**
	 * queries the Mapquest API direction service
	 * http://open.mapquestapi.com/directions
	 * 
	 * built uri:
	 * http://open.mapquestapi.com/directions/v2/route?key=[YOUR_KEY_HERE]&from={latLng:{lat:54.0484068,lng:-2.7990345}}&to={latLng:{lat:53.9593817,lng:-1.0814175}}
	 * 
	 * @param string $latLongA		start
	 * @param string $latLongB		target
	 * @return string				the distance in km
	 */
	public function mapQuestDistanceService($latLongA, $latLongB)
	{
		list($lat1, $lng1) = explode(',', $latLongA);
		list($lat2, $lng2) = explode(',', $latLongB);
		
		$uri  = self::MAPQUEST_URL_DIRECTIONS;
		$uri .= '?key=' . self::MAPQUEST_APP_KEY;
		$uri .= '&from={latLng:{lat:' . $lat1 . ',lng:' . $lng1 . '}}';
		$uri .= '&to={latLng:{lat:' . $lat2 . ',lng:' . $lng2 . '}}';
		$uri .= '&unit=k';
		$uri .= '&doReverseGeocode=false';
		$uri .= '&locale=de_DE';
		$response = json_decode(\TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($uri), TRUE);	
		return $response['route']['distance'];
	}
	
	/**
	 * Gets the geocoords from an address
	 * address must be passed as array with the keys:
	 *		location, postcode, street, street_number
	 * 
	 * @param array $address
	 * @return string	'lat,long'
	 */
	public function mapQuestAddressService($address)
	{
		$addr = ( ! empty($address['street'])) ? urlencode($address['street']) : '';
		$addr .= ( ! empty($address['street_number'])) ? ' ' . $address['street_number'] : '';
		$addr .= ',' . urlencode($host['location']);
		$addr .= ( ! empty($address['postcode'])) ? ',' . $address['postcode'] : '';
		$addr .= '&country=Germany';
			
		$uri  = self::MAPQUEST_URL_ADDRESS;
		$uri .= '?key=' . self::MAPQUEST_APP_KEY;
		$uri .= '&location=' . $addr;	
		$response = json_decode(\TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($uri), TRUE);
		$latLong =  $response['results'][0]['locations'][0]['latLng'];
		$coords = '';
		if ( ! empty($latLong['lat']))
		{
			$coords = $latLong['lat'] . ',' . $latLong['lng'];
		}
		return $coords;
	}

}
?>