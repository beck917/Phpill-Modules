<?php
/**
 * Minion exception
 *
 * @package    Kohana
 * @category   Minion
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
namespace Phpill\Modules\Minion\Libraries;
class Exception extends \Phpill_Exception {
	/**
	 * Inline exception handler, displays the error message, source of the
	 * exception, and the stack trace of the error.
	 *
	 * Should this display a stack trace? It's useful.
	 *
	 * @uses    Kohana_Exception::text
	 * @param   Exception   $e
	 * @return  boolean
	 */
	public static function handler($e)
	{
		try
		{
            \Phpill_Exception::log($e);
			// Log the exception
			if ($e instanceof Exception)
			{
				echo $e->format_for_cli();
			}
			else
			{
				echo \Phpill_Exception::text($e);
			}

			$exit_code = $e->getCode();

			// Never exit "0" after an exception.
			if ($exit_code == 0)
			{
				$exit_code = 1;
			}
			exit($exit_code);
		}
		catch (\Exception $e)
		{ 
			// Clean the output buffer if one exists
			ob_get_level() and ob_clean();

			// Display the exception text
			echo \Phpill_Exception::text($e), "\n";

			// Exit with an error status
			exit(1);
		}
	}
    
	/**
	 * PHP error handler, converts all errors into ErrorExceptions. This handler
	 * respects error_reporting settings.
	 *
	 * @throws  ErrorException
	 * @return  TRUE
	 */
	public static function error_handler($code, $error, $file = NULL, $line = NULL)
	{
        // Clean the output buffer if one exists
		ob_get_level() and ob_clean();
		if (error_reporting() & $code)
		{
			// This error is not suppressed by current error reporting settings
			// Convert the error into an ErrorException
			//throw new \ErrorException($error, $code, 0, $file, $line);
            // Remove the first entry of debug_backtrace(), it is the exception_handler call
            $trace = array_slice(debug_backtrace(), 1);

            // Beautify backtrace
            $trace = \Phpill::backtraceCli($trace);
            echo "        0) ".$error."\n";
            echo $trace;
		}
		if ( ! \Event::has_run('system.shutdown'))
		{
			// Run the shutdown even to ensure a clean exit
			\Event::run('system.shutdown');
		}

		// Turn off error reporting
		error_reporting(0);
		exit;
	}
    
    public static function generateCallTrace()
    {
        $e = new \Exception();
        $trace = explode("\n", $e->getTraceAsString());
        // reverse array to make steps line up chronologically
        $trace = array_reverse($trace);
        array_shift($trace); // remove {main}
        array_pop($trace); // remove call to this method
        $length = count($trace);
        $result = array();

        for ($i = 0; $i < $length; $i++)
        {
            $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
        }

        return "\t" . implode("\n\t", $result);
    }
    
	public function format_for_cli()
	{
		return \Phpill_Exception::text($this);
	}
}
