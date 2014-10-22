<?php
namespace SendyPHP\Model;
use SendyPHP\Sendy;
/**
 * Sender settings
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SendyPHP
 */
class Sender
{
    /**
     * "From name" of your campaign
     * @var string
     */
    protected $_name = NULL;
    /**
     * "From email" of your campaign
     * @var string
     */
    protected $_address = NULL;
    /**
     * "Reply to" of your campaign
     * @var string
     */
    protected $_replyAddress = NULL;

    /**
     * Sender settings
     *
     * @param string $fromName 'From name' of your campaign
     * @param string $fromAddress 'From email' of your campaign
     * @param string $replyAddress 'Reply to' of your campaign
     * @throws \SendyPHP\Exception\DomainException
     * @throws \SendyPHP\Exception\InvalidEmailException
     */
    public function __construct($fromName, $fromAddress, $replyAddress)
    {
        $this->setName($fromName);
        $this->setAddress($fromAddress);
        $this->setReplyAddress($replyAddress);
    }
    /**
     * Sets "From" e-mail address
     *
     * @param string $address
     * @throws \SendyPHP\Exception\InvalidEmailException
     * @uses \SendyPHP\Sendy::isEmailValid()
     */
    public function setAddress($address)
    {
        if(Sendy::isEmailValid($address))
            throw new \SendyPHP\Exception\InvalidEmailException($address);

        $this->_address = $address;
    }
    /**
     * Returns "From" e-mail address
     * @return string
     */
    public function getAddress()
    {
        return $this->_address;
    }
    /**
     * Sets From name
     *
     * @param string $name
     * @throws \SendyPHP\Exception\DomainException
     */
    public function setName($name)
    {
        if(strlen($name) == 0)
            throw new \SendyPHP\Exception\DomainException('From name have to longer than zero');

        $this->_name = $name;
    }
    /**
     * Returns From name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    /**
     * Sets reply e-mail address
     *
     * @param string $replyAddress
     * @throws \SendyPHP\Exception\InvalidEmailException
     * @uses \SendyPHP\Sendy::isEmailValid()
     */
    public function setReplyAddress($replyAddress)
    {
        if(Sendy::isEmailValid($replyAddress))
            throw new \SendyPHP\Exception\InvalidEmailException($replyAddress);

        $this->_replyAddress = $replyAddress;
    }
    /**
     * Returns reply e-mail address
     *
     * @return string
     */
    public function getReplyAddress()
    {
        return $this->_replyAddress;
    }
}