<?php
namespace SendyPHP;
/**
 * HP interface for Sendy api
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SendyPHP
 */
class Sendy
{
    /**
     * Sendy instalation URL
     * @var string|NULL
     */
    protected $_URL = NULL;
    /**
     * Api key
     * @var string|null
     */
    protected $_apiKey = NULL;

    public function __construct()
    {

    }
    public function subscribe($listID, $email, $name = NULL)
    {

    }
    public function unsubscribe($listID, $email)
    {

    }
    public function getSubscriptionStatus($listID, $email)
    {

    }
    public function getActiveSubscriberCount($listID)
    {

    }
    public function createCampaign($campaign)
    {

    }
    /**
     * Sets sendy instalation URL
     *
     * @param string $URL
     * @throws InvalidURLException
     */
    public function setURL($URL)
    {
        if(!self::isURLValid($URL))
            throw new InvalidURLException($URL);

        $this->_URL = $URL;
    }
    /**
     * Checks URL validity
     *
     * returns TRUE if given URL is valid
     *
     * @param mixed $url
     * @return bool
     */
    public static function isURLValid($url)
    {
        return (filter_var($url,\FILTER_VALIDATE_URL)!==false);
    }
    /**
     * Sets api key
     *
     * your API key is available in sendy Settings
     *
     * @param string $apiKey
     * @throws DomainException
     */
    public function setApiKey($apiKey)
    {
        if(!is_string($apiKey))
            throw new DomainException('Api key have to be string '.gettype($apiKey).' given');

        $this->_apiKey = $apiKey;
    }
    /**
     * Returns URL
     *
     * if no no URL is defined throws an exception
     *
     * @throws UnexpectedValueException
     * @return string
     */
    protected function _getURL()
    {
        if(is_null($this->_URL))
            throw new UnexpectedValueException('There is no Sendy URL defined - use setURL() first');
        return $this->_URL;
    }
    /**
     * Returns API key
     *
     * @return string
     * @throws UnexpectedValueException
     */
    protected function _getApiKey()
    {
        if(is_null($this->_apiKey))
            throw new UnexpectedValueException('There is no Sendy Api Key defined - use setAPIKey() first');

        return $this->_apiKey;
    }
}