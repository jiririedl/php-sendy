<?php
namespace SendyPHP\Response;
/**
 * Subscription status response
 *
 * represents API response
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SendyPHP
 */
class SubscriptionStatus
{
    /**
     * Subscribed text response from API
     * @link http://sendy.co/api
     */
    const RESPONSE_SUBSCRIBED = 'Subscribed';
    /**
     * Unsubscribed text response from API
     * @link http://sendy.co/api
     */
    const RESPONSE_UNSUBSCRIBED = 'Unsubscribed';
    /**
     * Unconfirmed text response from API
     * @link http://sendy.co/api
     */
    const RESPONSE_UNCONFIRMED = 'Unconfirmed';
    /**
     * Bounced text response from API - this usually means hard bounces
     * @link http://sendy.co/api
     */
    const RESPONSE_BOUNCED = 'Bounced';
    /**
     * Soft bounced text response from API
     * @link http://sendy.co/api
     */
    const RESPONSE_SOFT_BOUNCED = 'Soft bounced';
    /**
     * Complained text response from API
     * @link http://sendy.co/api
     */
    const RESPONSE_COMPLAINED = 'Complained';

    /**
     * RAW sendy response
     * @var string
     */
    protected $_rawResponse = NULL;

    /**
     * Subscription status
     *
     * @param string $rawResponse
     */
    public function __construct($rawResponse)
    {
        $this->_rawResponse = $rawResponse;
    }
    /**
     * Returns TRUE if user is subscribed
     *
     * @return bool
     */
    public function isSubscribed()
    {
        return ($this->getRawResponse() == self::RESPONSE_SUBSCRIBED);
    }
    /**
     * Returns true if user is unsubscribed
     *
     * @return bool
     */
    public function isUnSubscribed()
    {
        return ($this->getRawResponse() == self::RESPONSE_UNSUBSCRIBED);
    }
    /**
     * Returns true if user is unconfirmed
     *
     * @return bool
     */
    public function isUnconfirmed()
    {
        return ($this->getRawResponse() == self::RESPONSE_UNCONFIRMED);
    }
    /**
     * Returns true if user is bounced
     *
     * The email is rejected by the recipient's ISP or rejected by Amazon SES because the email address is on the Amazon SES suppression list.
     * For ISP bounces, Amazon SES reports only hard bounces and soft bounces that will no longer be retried by Amazon SES.
     * In these cases, your recipient did not receive your email message, and Amazon SES will not try to resend it.
     * Bounce notifications are available through email and Amazon SNS.
     * You are notified of out-of-the-office (OOTO) messages through the same method as bounces, although they don't count toward your bounce statistics.
     *
     * You can also call isHardBounced() or isSoftBounced()
     *
     * @see http://docs.aws.amazon.com/ses/latest/DeveloperGuide/notifications.html
     * @return bool
     */
    public function isBounced()
    {
        return ($this->isSoftBounced() || $this->isHardBounced());
    }
    /**
     * Returns TRUE if user id hard bounced
     *
     * A hard bounce indicates a persistent delivery failure (e.g., mailbox does not exist).
     * In other words, your recipient did not receive your email message, and your server will not try to resend it.
     *
     * @return bool
     */
    public function isHardBounced()
    {
        return ($this->getRawResponse() == self::RESPONSE_BOUNCED);
    }
    /**
     * Returns true if user is soft bounced
     *
     * Unlike a hard bounce, a soft bounce is not a permanent failure or rejection (e.g., mailbox full).
     * Many email systems (including Amazon SES) will automatically try to resend a message that has generated a soft bounce over a period of time until the message either delivers or the system will no longer retry the delivery and in turn generates a hard bounce.
     *
     * @return bool
     */
    public function isSoftBounced()
    {
        return ($this->getRawResponse() == self::RESPONSE_SOFT_BOUNCED);
    }
    /**
     * Returns true if user is complained
     *
     * The email is accepted by the ISP and delivered to the recipient, but the recipient does not want the email and clicks a button such as "Mark as spam."
     * If Amazon SES has a feedback loop set up with the ISP, Amazon SES will send you a complaint notification.
     * Complaint notifications are available through email and Amazon SNS.
     *
     * @return bool
     */
    public function isComplained()
    {
        return ($this->getRawResponse() == self::RESPONSE_COMPLAINED);
    }
    /**
     * Returns TRUE if request successfully obtains status
     *
     * if FALSE you can get error message by calling getRawMessage()
     * @return bool
     */
    public function success()
    {
        return ($this->isSubscribed() || $this->isUnSubscribed() || $this->isUnconfirmed() || $this->isBounced() || $this->isSoftBounced() || $this->isComplained());
    }
    /**
     * Returns TRUE if request obtaining status failed
     *
     * If TRUE you can get error message by calling getRawMessage()
     * @return bool
     */
    public function failed()
    {
        return !$this->success();
    }
    /**
     * Returns raw response (plaintext api response)
     *
     * @see http://sendy.co/api section "subscription status":"response"
     * @return string
     */
    public function getRawResponse()
    {
        return $this->_rawResponse;
    }
}