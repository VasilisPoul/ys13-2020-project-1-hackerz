<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Holds class PMA_Error
 *
 * @version $Id: Error.class.php 12202 2009-01-20 18:04:20Z lem9 $
 * @package phpMyAdmin
 */

/**
 * base class
 */
require_once './libraries/Message.class.php';

/**
 * a single error
 *
 * @package phpMyAdmin
 */
class PMA_Error extends PMA_Message
{
    /**
     * Error types
     *
     * @var array
     */
    static public $errortype = array(
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parsing Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Runtime Notice',
        E_DEPRECATED => 'Deprecation Notice',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
    );

    /**
     * Error levels
     *
     * @var array
     */
    static public $errorlevel = array(
        E_ERROR => 'error',
        E_WARNING => 'warning',
        E_PARSE => 'error',
        E_NOTICE => 'notice',
        E_CORE_ERROR => 'error',
        E_CORE_WARNING => 'warning',
        E_COMPILE_ERROR => 'error',
        E_COMPILE_WARNING => 'warning',
        E_USER_ERROR => 'error',
        E_USER_WARNING => 'warning',
        E_USER_NOTICE => 'notice',
        E_STRICT => 'notice',
        E_DEPRECATED => 'notice',
        E_RECOVERABLE_ERROR => 'error',
    );

    /**
     * The file in which the error occured
     *
     * @var string
     */
    protected $_file = '';

    /**
     * The line in which the error occured
     *
     * @var integer
     */
    protected $_line = 0;

    /**
     * Holds any variables defined in the context where the error occured
     * f. e. $this if the error occured in an object method
     *
     * @var array
     */
    protected $_context = array();

    /**
     * Holds the backtrace for this error
     *
     * @var array
     */
    protected $_backtrace = array();

    /**
     * Unique id
     *
     * @var string
     */
    protected $_hash = null;

    /**
     * Constructor
     *
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param integer $errline
     * @param array $errcontext
     * @uses    debug_backtrace()
     * @uses    PMA_Error::setNumber()
     * @uses    PMA_Error::setMessage()
     * @uses    PMA_Error::setFile()
     * @uses    PMA_Error::setLine()
     * @uses    PMA_Error::setContext()
     * @uses    PMA_Error::setBacktrace()
     */
    public function __construct($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->setNumber($errno);
        $this->setMessage($errstr, false);
        $this->setFile($errfile);
        $this->setLine($errline);
        $this->setContext($errcontext);

        $backtrace = debug_backtrace();
        // remove last two calls: debug_backtrace() and handleError()
        unset($backtrace[0]);
        unset($backtrace[1]);

        $this->setBacktrace($backtrace);
    }

    /**
     * sets PMA_Error::$_backtrace
     *
     * @param array $backtrace
     * @uses    PMA_Error::$_backtrace to set it
     */
    public function setBacktrace($backtrace)
    {
        $this->_backtrace = $backtrace;
    }

    /**
     * sets PMA_Error::$_context
     *
     * @param array $context
     * @uses    PMA_Error::$_context to set it
     */
    public function setContext($context)
    {
        $this->_context = $context;
    }

    /**
     * sets PMA_Error::$_line
     *
     * @param integer $line
     * @uses    PMA_Error::$_line to set it
     */
    public function setLine($line)
    {
        $this->_line = $line;
    }

    /**
     * sets PMA_Error::$_file
     *
     * @param string $file
     * @uses    PMA_Error::relPath()
     * @uses    PMA_Error::$_file to set it
     */
    public function setFile($file)
    {
        $this->_file = PMA_Error::relPath($file);
    }


    /**
     * returns unique PMA_Error::$_hash, if not exists it will be created
     *
     * @param string $file
     * @return  string PMA_Error::$_hash
     * @uses    PMA_Error::getMessage()
     * @uses    PMA_Error::getFile()
     * @uses    PMA_Error::getLine()
     * @uses    PMA_Error::getBacktrace()
     * @uses    md5()
     * @uses    PMA_Error::$_hash as return value and to set it if required
     * @uses    PMA_Error::getNumber()
     */
    public function getHash()
    {
        if (null === $this->_hash) {
            $this->_hash = md5(
                $this->getNumber() .
                $this->getMessage() .
                $this->getFile() .
                $this->getLine() .
                $this->getBacktrace()
            );
        }

        return $this->_hash;
    }

    /**
     * returns PMA_Error::$_backtrace
     *
     * @return  array PMA_Error::$_backtrace
     * @uses    PMA_Error::$_backtrace as return value
     */
    public function getBacktrace()
    {
        return $this->_backtrace;
    }

    /**
     * returns PMA_Error::$_file
     *
     * @return  string PMA_Error::$_file
     * @uses    PMA_Error::$_file as return value
     */
    public function getFile()
    {
        return $this->_file;
    }

    /**
     * returns PMA_Error::$_line
     *
     * @return  integer PMA_Error::$_line
     * @uses    PMA_Error::$_line as return value
     */
    public function getLine()
    {
        return $this->_line;
    }

    /**
     * returns type of error
     *
     * @return  string  type of error
     * @uses    PMA_Error::getNumber()
     * @uses    PMA_Error::$errortype
     */
    public function getType()
    {
        return PMA_Error::$errortype[$this->getNumber()];
    }

    /**
     * returns level of error
     *
     * @return  string  level of error
     * @uses    PMA_Error::getNumber()
     * @uses    PMA_Error::$$errorlevel
     */
    public function getLevel()
    {
        return PMA_Error::$errorlevel[$this->getNumber()];
    }

    /**
     * returns title prepared for HTML Title-Tag
     *
     * @return  string   HTML escaped and truncated title
     * @uses    htmlspecialchars()
     * @uses    substr()
     * @uses    PMA_Error::getTitle()
     */
    public function getHtmlTitle()
    {
        return htmlspecialchars(substr($this->getTitle(), 0, 100));
    }

    /**
     * returns title for error
     *
     * @return string
     * @uses    PMA_Error::getMessage()
     * @uses    PMA_Error::getType()
     */
    public function getTitle()
    {
        return $this->getType() . ': ' . $this->getMessage();
    }

    /**
     * Display HTML backtrace
     *
     * @uses    PMA_Error::getBacktrace()
     * @uses    PMA_Error::relPath()
     * @uses    PMA_Error::displayArg()
     * @uses    count()
     */
    public function displayBacktrace()
    {
        foreach ($this->getBacktrace() as $step) {
            echo PMA_Error::relPath($step['file']) . '#' . $step['line'] . ': ';
            if (isset($step['class'])) {
                echo $step['class'] . $step['type'];
            }
            echo $step['function'] . '(';
            if (isset($step['args']) && (count($step['args']) > 1)) {
                echo "<br />\n";
                foreach ($step['args'] as $arg) {
                    echo "\t";
                    $this->displayArg($arg, $step['function']);
                    echo ',' . "<br />\n";
                }
            } elseif (isset($step['args']) && (count($step['args']) > 0)) {
                foreach ($step['args'] as $arg) {
                    $this->displayArg($arg, $step['function']);
                }
            }
            echo ')' . "<br />\n";
        }
    }

    /**
     * Display a single function argument
     * if $function is one of include/require the $arg is converted te relative path
     *
     * @param string $arg
     * @param string $function
     * @uses    gettype()
     * @uses    PMA_Error::relPath()
     * @uses    in_array()
     */
    protected function displayArg($arg, $function)
    {
        $include_functions = array(
            'include',
            'include_once',
            'require',
            'require_once',
        );

        if (in_array($function, $include_functions)) {
            echo PMA_Error::relPath($arg);
        } elseif (is_scalar($arg)) {
            echo gettype($arg) . ' ' . $arg;
        } else {
            echo gettype($arg);
        }
    }

    /**
     * Displays the error in HTML
     *
     * @uses    PMA_Error::getLevel()
     * @uses    PMA_Error::getType()
     * @uses    PMA_Error::getMessage()
     * @uses    PMA_Error::displayBacktrace()
     * @uses    PMA_Error::isDisplayed()
     */
    public function display()
    {
        echo '<div class="' . $this->getLevel() . '">';
        if (!$this->isUserError()) {
            echo '<strong>' . $this->getType() . '</strong>';
            echo ' in ' . $this->getFile() . '#' . $this->getLine();
            echo "<br />\n";
        }
        echo $this->getMessage();
        if (!$this->isUserError()) {
            echo "<br />\n";
            echo "<br />\n";
            echo "<strong>Backtrace</strong><br />\n";
            echo "<br />\n";
            echo $this->displayBacktrace();
        }
        echo '</div>';
        $this->isDisplayed(true);
    }

    /**
     * whether this error is a user error
     *
     * @return  boolean
     * @uses    E_USER_ERROR
     * @uses    E_USER_NOTICE
     * @uses    PMA_Error::getNumber()
     * @uses    E_USER_WARNING
     */
    public function isUserError()
    {
        return $this->getNumber() & (E_USER_WARNING | E_USER_ERROR | E_USER_NOTICE);
    }

    /**
     * return short relative path to phpMyAdmin basedir
     *
     * prevent path disclusore in error message,
     * and make users feel save to submit error reports
     *
     * @static
     * @param string $dest path to be shorten
     * @return  string shortened path
     * @uses    PHP_OS()
     * @uses    __FILE__()
     * @uses    realpath()
     * @uses    substr()
     * @uses    explode()
     * @uses    dirname()
     * @uses    implode()
     * @uses    count()
     * @uses    array_pop()
     * @uses    str_replace()
     */
    static function relPath($dest)
    {
        $dest = realpath($dest);

        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $path_separator = '\\';
        } else {
            $path_separator = '/';
        }

        $Ahere = explode($path_separator, realpath(dirname(__FILE__) . $path_separator . '..'));
        $Adest = explode($path_separator, $dest);

        $result = '.';
        // && count ($Adest)>0 && count($Ahere)>0 )
        while (implode($path_separator, $Adest) != implode($path_separator, $Ahere)) {
            if (count($Ahere) > count($Adest)) {
                array_pop($Ahere);
                $result .= $path_separator . '..';
            } else {
                array_pop($Adest);
            }
        }
        $path = $result . str_replace(implode($path_separator, $Adest), '', $dest);
        return str_replace($path_separator . $path_separator, $path_separator, $path);
    }
}

?>
