<?php
/**
 * Session library.
 *
 * @package    Core
 * @author     Beck Xu
 * @license    http://phpillphp.com/license.html
 */
namespace Phpill\Modules\Core\Libraries;
class MobileSession {
	// Session singleton
	private static $instance;
	private $config;
	private $expires = 86400;
	
	/**
	 * Singleton instance of Session.
	 * @return MobileSession
	 */
	public static function instance()
	{
		if (self::$instance == NULL)
		{
			// Create a new instance
			self::$instance = new MobileSession();
		}

		return self::$instance;
	}

	/**
	 * On first session instance creation, sets up the driver and creates session.
	 */
	public function __construct()
	{
		$this->config = Phpill::config('session');
	}
	
	public function get($key)
	{
		$time = Network::getCookie('time');
		$timestamp = PEAR::getStaticProperty('_APP', 'timestamp');
		if ($time + $this->expires < $timestamp) {
			return 0;
		}
		
		$value = Network::getCookie($key);
		
		if (empty($value)) {
			return false;
		}
		
		return $value;
	}
	
	public function set($key, $value)
	{
		Network::bufferCookie($key, $value);
	}
	
	public function delete($key)
	{
		Network::delCookie($key);
	}
	
	public static function sessionId()
	{
		$session_id = Network::getCookie('session_id');
		
		if (empty($session_id)) {
			session_start();
			$session_id = session_id();
			
			Network::bufferCookie('session_id', $session_id);
		}
		
		return $session_id;
	}
}
