<?php
/**
 *  OpenLSS - Lighter Smarter Simpler
 *
 *	This file is part of OpenLSS.
 *
 *	OpenLSS is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU Lesser General Public License as
 *	published by the Free Software Foundation, either version 3 of
 *	the License, or (at your option) any later version.
 *
 *	OpenLSS is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU Lesser General Public License for more details.
 *
 *	You should have received a copy of the 
 *	GNU Lesser General Public License along with OpenLSS.
 *	If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * ModelService implements a callable object from an array of data
 *	It can then be extended to implement formatters
 *	This will most likely become an LSS package
 */
namespace LSS;
use \Exception;

class DataModel {

	const KEYS_ASSOC = 0;
	const KEYS_NUMERIC = 1;

	public $data;

	public static function _setup($data){
		$obj = new static();
		$obj->_setData($data);
		return $obj;
	}

	public function _setData($data){
		if(!is_array($data))
			throw new Exception('Model Service data must be an associative array');
		$this->data = $data;
		return $this;
	}

	public function _getColumns($cols=array(),$flags=self::KEYS_ASSOC){
		if(!is_array($cols) || !count($cols)) return array();
		$row = array();
		foreach($cols as $key => $col){
			switch($flags){
				case self::KEYS_ASSOC:
					$key = $col;
					break;
				default:
				case self::KEYS_NUMERIC:
					//default
					break;
			}
			$row[$key] = call_user_func(array($this,self::_camelName($col,'get')));
		}
		return $row;
	}

	public function _getAll($flags=self::KEYS_ASSOC){
		return $this->_getColumns(array_keys($this->data),$flags);
	}

	//example function would be getMyData which corresponds to $arr['my_data']
	//	setting is possible with setMyData($val)
	public function __call($name,$args){
		if(strpos($name,'get') === 0){
			$name = self::_realName($name,'get');
			return mda_get($this->data,$name);
		}
		if(strpos($name,'set') === 0){
			$name = self::_realName($name,'set');
			return mda_set($this->data,$name,array_shift($args));
		}
		//we dont know what to do with this
		return false;
	}

	//converts real_name into camelName
	public static function _camelName($name,$prefix=null){
		$name = str_replace('_',' ',$name);
		$name = ucwords($name);
		$name = str_replace(' ','',$name);
		if(!is_null($prefix))
			$name = lcfirst($name);
		return $prefix.$name;
	}

	//this converts camel case into real_case
	public static function _realName($name,$prefix=null){
		$name = preg_replace('/^'.$prefix.'/','',$name);
		$name = preg_replace('/([A-Z]{1})/','_$1',$name);
		$name = strtolower($name);
		$name = trim($name,'_');
		return $name;
	}

}
