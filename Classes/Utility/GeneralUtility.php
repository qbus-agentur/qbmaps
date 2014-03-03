<?php
namespace Qbus\Qbmaps\Utility;

/* * *************************************************************
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
 * ************************************************************* */

/**
 * Contains a collection several usefull geo functions not related to a specific service (GM or OSM)
 * 
 * @package qbMaps
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class GeneralUtility
{
    /**
     * calculates the distance between to sets of geo coords
     * using the Haversine formular
     * 
     * http://snipplr.com/view/2531/calculate-the-distance-between-two-coordinates-latitude-longitude/
     * http://en.wikipedia.org/wiki/Great-circle_distance 
     * 
     * @param string $latLongA		comma separated
     * @param string $latLongB		comma separated
     * @param string $measure		'm' | 'km' - default 'km'
     * @return string
     */
    public function calculateDistance($latLongA, $latLongB, $measure = 'km')
    {
        list($lat1, $lng1) = explode(',', $latLongA);
        list($lat2, $lng2) = explode(',', $latLongB);
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return $km;
    }

    /**
     * Calculates the center position of map
     * taken from  EXT:pit_googlemaps from Paulsen-IT <service@paulsen-it.de>
     * http://typo3.org/extensions/repository/view/pit_googlemaps
     * 
     * @param array $coords
     * @return array array('lat' => 'xx.xx', 'long' => 'yy.yy')
     */
    public function calculateCenterOfMap(array $coords)
    {
        if (is_array($coords) && count($coords) > 1)
        {
            foreach ($coords as $coord)
            {
                $lons[] = $coord['long'];
                $lats[] = $coord['lat'];
            }
            sort($lons, SORT_NUMERIC);
            sort($lats, SORT_NUMERIC);

            $biggestlon = array_pop($lons);
            $biggestlat = array_pop($lats);

            $smallestlon = array_shift($lons);
            $smallestlat = array_shift($lats);

            return array(
                'long' => round(($biggestlon + $smallestlon) / 2, 3),
                'lat'  => round(($biggestlat + $smallestlat) / 2, 3)
            );
        } 
        elseif (is_array($coords) && count($coords) == 1)
        {
            return array(
                'long' => $coords[0]['long'],
                'lat'  => $coords[0]['lat']
            );
        }
    }

}
?>