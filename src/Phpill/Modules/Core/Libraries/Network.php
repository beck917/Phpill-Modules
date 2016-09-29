<?php
/**
 * 通讯类
 * 所有的输出都要经过此类处理
 */
namespace Phpill\Modules\Core\Libraries;
class Network {
	private static $buf = array(
		'protocol' => array(
			'result' => array('status' => 1, 'msg' => 'ok'),
		),
		'data' => array(
		),
	);
	//1:需要翻译 0:无需翻译
	private static $lang = 1;
	
	/**
	 * 缓冲数据
	 * @param $key
	 * @param $vl
	 */
	public static function buffer($key, $vl)
	{
		self::$buf['data'][$key] = $vl;
	}
	
	public static function bufferArray($key, $vl)
	{
		if (isset(self::$buf['data'][$key])) {
			self::$buf['data'][$key][] = $vl;
		} else {
			self::$buf['data'][$key] = array($vl);
		}
	}
	
	public static function bufferEncrypt($vl)
	{
		self::$buf['protocol']['encrypt'] = $vl;
	}
	
	public static function bufferCookie($key, $vl)
	{
		self::$buf['protocol']['cookie'][$key] = $vl;
	}
	
	public static function delCookie($key)
	{
		unset(self::$buf['protocol']['cookie'][$key]);
	}
	
	public static function getCookie($key)
	{
		static $cookie = null;
		
		if ($cookie === null) {
			$input = Input::instance();
			$cookie = $input->get('cookie');

			if (!empty($cookie)) {
				$cookie = json_decode(base64_decode($cookie), true);
				self::$buf['protocol']['cookie'] = $cookie;
				//self::$buf['protocol']['cookie'] = $cookie;
			}
		}
		
		if (!isset($cookie[$key])) {
			return false;
		} else {
			return $cookie[$key];
		}
	}
	
	public static function getBufferData($key)
	{
		if (isset(self::$buf['data'][$key])) {
			return self::$buf['data'][$key];
		} else {
			return array();
		}
	}
	
	
	/**
	 * 缓冲错误数据
	 * @param unknown_type $msg
	 * @param unknown_type $params
	 */
	public static function buffer_error($code, $msg = '', $params = array())
	{
		if (self::$lang == 1) {
			$msg = Phpill::lang('error.'.$msg);
		}
		
		self::$buf['protocol'] = array('result' => array('status' => $code, 'msg' => $msg)) + $params;
		unset(self::$buf['data']);

		self::send();
		die();
	}
	
	/**
	 * 发送数据
	 */
	public static function send()
	{
		if (!empty(self::$buf['protocol']['cookie'])) {
			self::$buf['protocol']['cookie'] = base64_encode(json_encode(self::$buf['protocol']['cookie']));
		}
		
		echo json_encode(self::$buf);
	}
	
	public static function Debug()
	{
		print_r(self::$buf);die();
	}
}