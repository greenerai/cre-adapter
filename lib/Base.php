<?php
/**
 * A Base Class for an RBE
 */

namespace OpenTHC\CRE\Adapter;

class Base
{
	protected $_api_base;

	protected $_License;

	const ENGINE = null;

	/**
	 * Get Configuration of a Specific Engine
	 */
	function getConfig($key)
	{
		$dir = __DIR__;
		$dir = dirname($dir);

		$ini_file = sprintf('%s/etc/cre.ini', $dir);
		$ini_data = parse_ini_file($ini_file, true, INI_SCANNER_RAW);

		$cfg = $ini_data[$key];

		return $cfg;
	}

	function getLicense()
	{
		return $this->_License;
	}

	/**
	 * Set License
	 * @param array $l License Data Array
	 */
	function setLicense($l)
	{
		if (is_array($l)) {
			// Perfect
		} elseif (is_object($l)) {
			$l = $l->toArray(); // Hope it has this routine!
		} elseif (is_string($l)) {
			$l = [
				'id' => $l,
				'code' => $l,
				'guid' => $l,
			];
		} else {
			throw new \Exception('Invalid Parameters [LRB#066]');
		}

		if (empty($l['id'])) {
			throw new \Exception('License Missing ID');
		}

		if (empty($l['code'])) {
			throw new \Exception('License Missing CODE');
		}

		if (empty($l['guid'])) {
			throw new \Exception('License Missing GUID');
		}

		$this->_License = $l;

		return $this->_License;
	}

	/**
	 * Everyone else should implement this
	 */
	function ping()
	{
		return false;
	}

	/**
	 * Normalize record data array and return a hash
	 * @param [type] $a [description]
	 * @return [type] [description]
	 */
	static function recHash($a)
	{
		if (!is_array($a)) {
			if (is_object($a)) {
				if (method_exists($a, 'toArray')) {
					$a = $a->toArray();
				} else {
					// JSON?
				}
			}
		}
		$a = self::ksort_r($a);
		return md5(json_encode($a));
	}

	/*
	* Key-Sort Array, Recursively
	*/
	static function ksort_r($a)
	{
		foreach ($a as &$v) {
			if (is_array($v)) {
					self::_ksort_r($v);
			}
		}

		return ksort($a);
	}

}
