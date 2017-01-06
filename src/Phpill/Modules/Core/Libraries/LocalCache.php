<?php
/**
 * LocalCache
 *
 */
namespace Phpill\Modules\Core\Libraries;
class LocalCache
{
    private $_cache = array();
	private static $instance;
	
	/**
	 * 防止命名冲突
	 * @var string
	 */
	private $namespace = '';

    public function __construct($namespace = '')
    {
		$this->namespace = $namespace;
        $this->_cache[$this->namespace] = array();
    }
	
	public static function instance($namespace = '')
	{
		if (self::$instance == NULL)
		{
			// Create a new instance
			self::$instance = new LocalCache($namespace);
		}

		return self::$instance;
	}

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->_cache[$this->namespace][$key]) ? $this->_cache[$this->namespace][$key] : null;
    }

    /**
     * @param $key
     * @param $var
     * @return bool
     */
    public function set($key, $var)
    {
        $this->_cache[$this->namespace][$key] = $var;
        return true;
    }

    public function hSet($key, $field, $value)
    {
        if (!isset($this->_cache[$this->namespace][$key])) {
            $this->_cache[$this->namespace][$key] = [];
        }
        $this->_cache[$this->namespace][$key][$field] = $value;
        return true;
    }
    
    public function hGetAll($key)
    {
        return $this->_cache[$this->namespace][$key];
    }
    
    public function getAll()
    {
        return $this->_cache[$this->namespace];
    }
    
    public function hGet($key, $field)
    {
        return $this->_cache[$this->namespace][$key][$field];
    }

    /**
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        unset($this->_cache[$this->namespace][$key]);
        return true;
    }

    /**
     * @return bool
     */
    public function flush()
    {
        $this->_cache = array();
        return true;
    }
}