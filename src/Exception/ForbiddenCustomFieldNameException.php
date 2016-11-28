<?php
namespace SendyPHP\Exception;
/**
 * Invalid custom field name Exception
 *
 * is usually thrown if name of custom field match with some of reserved keywords
 *
 * @package SendyPHP
 */
class ForbiddenCustomFieldNameException extends DomainException
{
    /**
     * Invalid URL Exception
     *
     * @param string $customFieldName
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($customFieldName, $code = 0, \Exception $previous = NULL)
    {
        parent::__construct($this->_buildMessage($customFieldName), $code, $previous);
    }
    /**
     * Builds Exception message text
     *
     * @param mixed $customFieldName
     * @return string
     */
    protected function _buildMessage($customFieldName)
    {
        $message = 'Forbidden custom field name detected ';
        if(is_string($customFieldName))
            $message.= ' - [.'.$customFieldName.'.] - this field name is reserved.';
        else
            $message.= ' - '.gettype($customFieldName).' given ['.var_export($customFieldName,1).'], string expected.';
        return $message;
    }
}