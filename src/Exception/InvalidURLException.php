<?php
namespace SendyPHP\Exception;
/**
 * Invalid URL Exception
 *
 * is usually thrown if URL is expected an given string is not valid URL
 *
 * @package SendyPHP
 */
class InvalidURLException extends DomainException
{
    /**
     * Invalid URL Exception
     *
     * @param string $URL
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($URL, $code = 0, \Exception $previous = NULL)
    {
        parent::__construct($this->_buildMessage($URL), $code, $previous);
    }
    /**
     * Builds Exception message text
     *
     * @param mixed $URL
     * @return string
     */
    protected function _buildMessage($URL)
    {
        $message = 'Valid URL expected ';
        if(is_string($URL))
            $message.= ' - invalid URL given [.'.$URL.'.]';
        else
            $message.= ' - '.gettype($URL).' given ['.var_export($URL,1).']';
        return $message;
    }
}