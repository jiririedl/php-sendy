<?php
namespace SendyPHP\Model;
/**
 * Sendy Campaign settings
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SendyPHP
 */
class Campaign
{
    /**
     * Sender settings
     * @var Sender
     */
    protected $_sender = NULL;
    /**
     * Email subject
     * @var string
     */
    protected $_subject = NULL;
    /**
     * Email body
     * @var EmailBody
     */
    protected $_emailBody = NULL;

    /**
     * Sendy campaign settings
     *
     * @param Sender $sender Sender settings
     * @param string|null $subject email subject
     * @param EmailBody $emailBody Email body variants
     *
     * @throws \SendyPHP\Exception\DomainException
     */
    public function __construct(Sender $sender = NULL, $subject = NULL, EmailBody $emailBody = NULL)
    {
        if(!is_null($sender))
            $this->setSenderSettings($sender);

        if(!is_null($subject))
            $this->setSubject($subject);

        if(!is_null($emailBody))
            $this->setEmailBodyVariants($emailBody);
    }
    /**
     * Sets e-mail body
     *
     * @param string $html
     * @param string|null $plainText
     * @throws \SendyPHP\Exception\DomainException
     */
    public function setEmailBody($html, $plainText = NULL)
    {
        $this->setEmailBodyVariants(new EmailBody($html,$plainText));
    }
    /**
     * Sets e-mail body variants
     *
     * @param EmailBody $emailBody
     */
    public function setEmailBodyVariants(EmailBody $emailBody)
    {
        $this->_emailBody = $emailBody;
    }
    /**
     * Returns e-mail body
     *
     * @return EmailBody
     */
    public function getEmailBody()
    {
        return $this->_emailBody;
    }
    /**
     * Sets sender settings
     *
     * @param string $fromName 'From name' of your campaign
     * @param string $fromAddress 'From email' of your campaign
     * @param string $replyAddress 'Reply to' of your campaign
     * @throws \SendyPHP\Exception\DomainException
     */
    public function setSender($fromName, $fromAddress, $replyAddress)
    {
        $this->setSenderSettings(new Sender($fromName, $fromAddress, $replyAddress));
    }
    /**
     * Sets sender settings
     *
     * @param Sender $sender
     */
    public function setSenderSettings(Sender $sender)
    {
        $this->_sender = $sender;
    }
    /**
     * Returns Sender settings
     *
     * @return Sender
     */
    public function getSender()
    {
        return $this->_sender;
    }
    /**
     * Sets e-mail subject
     *
     * @param string $subject
     * @throws \SendyPHP\Exception\DomainException
     */
    public function setSubject($subject)
    {
        if(strlen($subject) == 0)
            throw new \SendyPHP\Exception\DomainException('Email subject can not be empty');

        $this->_subject = $subject;
    }
    /**
     * Returns e-mail subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->_subject;
    }
}