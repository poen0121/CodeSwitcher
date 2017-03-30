<?php
defined('BASEPATH') OR exit ('No direct script access allowed');
if (!class_exists('csl_browser')) {
	/**
	 * @about - query the user's browser information.
	 */
	class csl_browser {
		/** Get client ip.
		 * @access - private function
		 * @return - string|null
		 * @usage - self::client_ip();
		 */
		private static function client_ip() {
			foreach (array (
					'HTTP_CLIENT_IP',
					'HTTP_X_FORWARDED_FOR',
					'HTTP_X_FORWARDED',
					'HTTP_X_CLUSTER_CLIENT_IP',
					'HTTP_FORWARDED_FOR',
					'HTTP_FORWARDED',
					'REMOTE_ADDR'
				) as $key) {
				if (isset ($_SERVER[$key])) {
					foreach (explode(',', $_SERVER[$key]) as $ip) {
						$ip = trim($ip);
						if ((bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
							return $ip;
						}
					}
				}
			}
			return;
		}
		/** Get the browser information, if less information will return NULL.
		 * @access - public function
		 * @param - string $index (information index name)
		 * @index $index -----------------------
		 * language : browser language
		 * server : server address
		 * host : http host
		 * source : http source
		 * url : browser URL
		 * ip : client ip
		 * proxy : proxy address
		 * name : browser name
		 * version : browser version
		 * os : browser os
		 * device : user device type (`desktop` , `mobile` , `tablet`)
		 * -------------------------------------
		 * @return - string|null|boolean
		 * @usage - csl_browser::info($index);
		 */
		public static function info($index = null) {
			if (!csl_func_arg :: delimit2error() && !csl_func_arg :: string2error(0)) {
				$index = strtolower($index);
				$userAgent = (isset ($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
				$info = null;
				switch ($index) {
					case 'language' :
						if (isset ($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE']) {
							$info = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'], 2);
							$info = (isset ($info[0]) ? $info[0] : null);
						}
						break;
					case 'server' :
						$info = (isset ($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '');
						$info = ($info ? $info : null);
						break;
					case 'host' :
						$info = (isset ($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
						$info = ($info ? $info : null);
						break;
					case 'source' :
						$info = (isset ($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
						$info = ($info ? $info : null);
						break;
					case 'url' :
						if (self :: info('host')) {
							$info = (isset ($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] . '://' : '') . self :: info('host') . (isset ($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
						}
						break;
					case 'ip' :
						$info = self :: client_ip();
						break;
					case 'proxy' :
						if (isset ($_SERVER['HTTP_X_FORWARDED_FOR'], $_SERVER['REMOTE_ADDR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != 'unknown') {
							$info = $_SERVER['REMOTE_ADDR'];
							$info = ($info ? $info : null);
						}
						break;
					case 'name' :
						$list = array (
							'IE' => '/IE/',
							'Flock' => '/Flock/',
							'Firefox' => '/Firefox|fennec/',
							'Opera' => '/Opera|Mobile.*OPR\/[0-9.]+|Coast\/[0-9.]+/',
							'Puffin' => '/Puffin/',
							'Chrome' => '/Chrome|\bCrMo\b|CriOS/',
							'Safari' => '/Safari/',
							'Netscape' => '/Netscape/',
							'Dolfin' => '/\bDolfin\b/',
							'Skyfire' => '/Skyfire/',
							'Bolt' => '/bolt/',
							'TeaShark' => '/teashark/',
							'Blazer' => '/Blazer/',
							'Tizen' => '/Tizen/',
							'UCBrowser' => '/UC.*Browser|UCWEB/',
							'baiduboxapp' => '/baiduboxapp/',
							'baidubrowser' => '/baidubrowser/',
							'DiigoBrowser' => '/DiigoBrowser/',
							'Mercury' => '/\bMercury\b/',
							'ObigoBrowser' => '/Obigo/',
							'NetFront' => '/NF-Browser/',
							'GenericBrowser' => '/NokiaBrowser|OviBrowser|OneBrowser|TwonkyBeamBrowser|SEMC.*Browser|FlyFlow|Minimo|NetFront|Novarra-Vision|MQQBrowser|MicroMessenger/',
							'PaleMoon' => '/PaleMoon/'
						);
						foreach ($list as $name => $match) {
							if (preg_match($match, $userAgent)) {
								$info = $name;
								break;
							}
						}
						break;
					case 'version' :
						if (preg_match('/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/i', $userAgent, $matches)) {
							$info = (isset ($matches[1]) ? $matches[1] : null);
						}
						break;
					case 'os' :
						$list = array (
							'AndroidOS' => '/Android/',
							'iOS' => '/\biPhone.*Mobile|\biPod|\biPad/',
							'Linux' => '/Linux/',
							'Mac' => '/Macintosh|Mac OS X/',
							'Windows' => '/Windows|Win32/',
							'BlackBerryOS' => '/blackberry|\bBB10\b|rim tablet os/',
							'PalmOS' => '/PalmOS|avantgo|blazer|elaine|hiptop|palm|plucker|xiino/',
							'SymbianOS' => '/Symbian|SymbOS|Series60|Series40|SYB-[0-9]+|\bS60\b/',
							'MeeGoOS' => '/MeeGo/',
							'MaemoOS' => '/Maemo/',
							'JavaOS' => '/J2ME\/|\bMIDP\b|\bCLDC\b/',
							'webOS' => '/webOS|hpwOS/',
							'badaOS' => '/\bBada\b/',
							'BREWOS' => '/BREW/',

						);
						foreach ($list as $os => $match) {
							if (preg_match($match, $userAgent)) {
								$info = $os;
								break;
							}
						}
						break;
					case 'device' :
						$info = 'desktop';
						$list = array (
							'mobile' => '/Mobile|Phone|Opera Mini|320x320|240x320|176x220/i', //Too many devices on the market.
							'tablet' => '/Tablet|Pad|Android.*Build\/|Silk.*Accelerated|Kindle|600x800|824x1200|1200x824/i' //Too many devices on the market.
						);
						foreach ($list as $device => $match) {
							if (preg_match($match, $userAgent)) {
								$info = $device;
								break;
							}
						}
						break;
					default :
						csl_error :: cast(__CLASS__ . '::' . __FUNCTION__ . '(): Invalid index specified', E_USER_WARNING, 1);
						return false;
				}
				return $info;
			}
			return false;
		}
		/** Verify execute source.
		 * @access - public function
		 * @return - boolean
		 * @usage - csl_browser::in_source();
		 */
		public static function in_source() {
			if (!csl_func_arg :: delimit2error()) {
				$source = self :: info('source');
				if ($source) {
					$source = parse_url($source);
					if (isset ($source['host']) && isset ($_SERVER['SERVER_NAME']) && $source['host'] == $_SERVER['SERVER_NAME']) {
						return true;
					}
				}
			}
			return false;
		}
	}
}
?>