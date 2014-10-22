<?php
namespace SendyPHP\Exception;
/**
 * Invalid email Exception
 *
 * is usually thrown if email is expected an given string is not valid email
 *
 * @package SendyPHP
 */
class InvalidEmailException extends DomainException
{
    /**
     * Invalid email Exception
     *
     * @param string $email
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($email, $code = 0, \Exception $previous = NULL)
    {
        parent::__consturuct($this->_buildMessage($email), $code, $previous);
    }
    /**
     * Builds Exception message text
     *
     * @param mixed $email
     * @return string
     */
    protected function _buildMessage($email)
    {
        $message = 'Valid e-mail address expected ';
        if(is_string($email))
            $message.= ' - invalid e-mail given [.'.$email.'.]';
        else
            $message.= ' - '.gettype($email).' given ['.var_export($email,1).']';
        return $message;
    }
}