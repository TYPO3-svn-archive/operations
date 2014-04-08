<?php
namespace KN\Operations\ViewHelpers;

/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

	/**
	* google map for operation
	*/

 class MapViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * 
	 * @param \KN\Operations\Domain\Model\Operation	 $object current operation
	 * @param array $settings
	 * @param string $description content for infowindow
	 * @return string
	 */


	public function render(\KN\Operations\Domain\Model\Operation $object, $settings, $description = NULL) {
		
		$coordinates;
		$coordinates = "var Coordinates = new google.maps.LatLng(".$object->getLatitude().",".$object->getLongitude().");";
		
		if($object->getZoom()) {
			$zoom = $object->getZoom();
		} else {
			$zoom = $settings[map][defaultZoomSingle];
		}
		
		if(!$description) {
			$description = $object->getTitle();
		}
		
		$mapOptions;
		$mapOptions = "var mapOptions = {\n
    zoom:$zoom,\n
    center: Coordinates,\n
		//panControl: true,\n
	  zoomControl: true,\n
	  zoomControlOptions: {\n
	  	style:google.maps.ZoomControlStyle.SMALL,\n
		position: google.maps.ControlPosition.TOP_LEFT\n
	  },\n
	mapTypeControl: true,\n
	mapTypeControlOptions: {\n
		style: google.maps.MapTypeControlStyle.DROPDOWN_MENU\n
	},\n
	  //scaleControl: true,\n
	  streetViewControl: false,\n
	  overviewMapControl: true\n
  };\n";
		
		$map;
		$map = "var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);";
		
		$infowindow;
		$infowindow = "var infowindow = new google.maps.InfoWindow({\n
	    content:'$description'\n
	});\n";
		
		$marker;
		$marker = "var marker = new google.maps.Marker({\n
		position: Coordinates,\n
		map: map,\n
	});\n";
		
		$addListener;
		$addListener = "google.maps.event.addListener(marker, 'click', function() {\n
	  infowindow.open(map,marker);\n
	});\n";

		$initialize;
		$initialize = "function initialize() {".$coordinates."\n".$mapOptions."\n".$map."\n".$infowindow."\n".$marker."\n".$addListener."}";
		
		$loadScript;
		$loadScript = "\nfunction loadScript() {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' +
      'callback=initialize';
  document.body.appendChild(script);
}
window.onload = loadScript;";

$mapData = '<script type="text/javascript">'.$initialize.$loadScript.'</script>';
		
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($map);
		return $mapData;
	}
}

?>
