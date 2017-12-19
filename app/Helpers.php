<?php
use Carbon\Carbon;

/**
 * This function makes a euro value like "1234,55" out of an integer which is threatened as cent-value.
 *
 * @param int $centVal
 * @param int $comma = ',' The Delimiter between Euro and Cent Values
 * @param     $dot   = ''  The delimiter between the thousand-parts of euro
 */
function setEuro($centVal, $comma = ',', $dot = '.') {
	return number_format($centVal / 100, 2, $comma, $dot);
}

function getEuroVal($centVal) {
	if($centVal < 0) {
		return '-' . getEuroVal(abs($centVal));
	}
	
	return intval(floor($centVal / 100));
}

function toCents($euro, $cent) {
	return intval($euro . $cent);
}

function getCentVal($centVal) {
	return substr($centVal, -2, 2);
}

function deleteForm($params, $label = 'Löschen', $class = "btn btn-danger deleteIt") {
	$form = '';
	$form .= Form::open([ 'method' => 'DELETE', 'route' => $params ]);
	$form .= Form::submit($label, [ 'class' => $class ]);
	$form .= Form::close();
	
	return $form;
}

function d($str = null) {
	if(is_numeric($str) && $str == -1) {
		return date('d.m.Y');
	}
	if($str == '') {
		return '';
	}
	if(strlen($str) > 10) {
		return Carbon::createFromFormat('Y-m-d H:i:s', $str)->format('d.m.Y');
	} else {
		return Carbon::createFromFormat('Y-m-d', $str)->format('d.m.Y');
	}
	
}

function dt($str = null) {
	if(is_numeric($str) && $str == -1) {
		return date('d.m.Y H:i:s');
	}
	if(strlen($str) > 10) {
		return Carbon::createFromFormat('Y-m-d H:i:s', $str)->format('d.m.Y H:i:s');
	} else {
		return Carbon::createFromFormat('Y-m-d', $str)->format('d.m.Y');
	}
}

function t($str) {
	if(is_numeric($str) && $str == -1) {
		return date('H:i:s');
	}
	if(strlen($str) > 10) {
		return Carbon::createFromFormat('Y-m-d H:i:s', $str)->format('H:i:s');
	} else {
		return Carbon::createFromFormat('Y-m-d', $str)->format('H:i:s');
	}
}

function _red($str, $val = null) {
	$vergleich = (isset ($val)) ? $val : $str;
	if((int)$vergleich < 0) {
		return '<span class="red" style="vertical-align: middle">' . $str . '€</span>';
	} else {
		return $str . '€';
	}
}

function _color($data) {
	if($data->color == '') {
		if($data->parentData()->count() != 0) {
			return _color($data->parentData);
		} else {
			return 'black';
		}
	} else {
		return $data->color;
	}
}

function endDate($str) {
	return ($str == -1) ? config('custom.nowString') : d($str);
}

/**
 * Zählt Werte auf wie 1, 2, 3, 4 und gibt diese Aufzählung als String zurück
 *
 * @param array $values Die Werte
 *
 * @return string
 */
function enum($values) {
	if(count($values) == 1) {
		return $values[ 0 ];
	} else {
		$end = array_pop($values);
		
		return implode(', ', $values) . ' und ' . $end;
	}
}

/**
 * Wandelt eine HEX-Farbe ins RGB-Format um
 *
 * return[0] int Rot-Kanal (0 < $rgb[0] < 255)
 * return[1] int Grün-Kanal (0 < $rgb[1] < 255)
 * return[2] int Blau-Kanal (0 < $rgb[2] < 255)
 *
 * @link https://phpdoc.org/docs/latest/guides/types.html  
 * @author Philipp Lang
 * @param string $hex Farbe im HEX-Format (mit oder ohne führendem '#')
 * 
 * @return int[]
 */
function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
	  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
	  $r = hexdec(substr($hex,0,2));
	  $g = hexdec(substr($hex,2,2));
	  $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

/**
 * Wandelt eine Farbe von RGB in HEX um (mit führendem "#")
 *
 * $rgb[0] int Rot-Kanal (0 < $rgb[0] < 255)
 * $rgb[1] int Grün-Kanal (0 < $rgb[1] < 255)
 * $rgb[2] int Blau-Kanal (0 < $rgb[2] < 255)
 *
 * @link https://phpdoc.org/docs/latest/guides/types.html  
 * @author Philipp Lang
 * @param int[] $rgb Farbe im RGB-Format
 *
 * @return string
 */
function rgb2hex($rgb) {
   $hex = "#";
   $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

   return $hex; // returns the hex value including the number sign (#)
}
