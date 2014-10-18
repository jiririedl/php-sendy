<?php
namespace SendyPHP\Model;

use SendyPHP\DomainException;
/**
 * Email body variants
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SendyPHP
 */
class EmailBody
{
    /**
     * HTML email code
     * @var string
     */
    protected $_html = NULL;
    /**
     * Plain text version of e-mail
     * @var string|null
     */
    protected $_plainText = NULL;

    /**
     * Email body
     *
     * @param string $html the 'HTML version' of your e-mail
     * @param string|null $plainText the 'Plain text version' of your e-mail (optional)
     * @throws \SendyPHP\DomainException
     */
    public function __construct($html, $plainText = NULL)
    {
        $this->setHtml($html);

        if(!is_null($plainText))
            $this->setPlainText($plainText);
    }
    /**
     * Sets HTML email code
     *
     * @param string $html
     * @throws \SendyPHP\DomainException
     */
    public function setHtml($html)
    {
        if(strlen($html) == 0)
            throw new DomainException('HTML email code can not be empty');

        $this->_html = $html;
    }
    /**
     * Returns HTML email code
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->_html;
    }
    /**
     * Sets plain text version of e-mail
     *
     * @param string $plainText
     * @throws \SendyPHP\DomainException
     */
    public function setPlainText($plainText)
    {
        if(strlen($plainText) == 0)
            throw new DomainException('Plain text version of e-mai can not be empty - you can disable plaintext version by calling removePlainText().');

        $this->_plainText = $plainText;
    }
    /**
     * Remove plaintext version
     */
    public function removePlainText()
    {
        $this->_plainText = NULL;
    }
    /**
     * Returns plain text version of e-mail
     *
     * NULL is returned, if no plain text version is set
     *
     * @return null|string
     */
    public function getPlainText()
    {
        return $this->_plainText;
    }

}