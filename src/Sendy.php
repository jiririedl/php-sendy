<?php
namespace SendyPHP;
/**
 * PHP interface for Sendy api
 *
 * @author Jiri Riedl <riedl@dcommunity.org>
 * @package SendyPHP
 */
class Sendy
{
    CONST URI_CAMPAIGN = 'api/campaigns/create.php';
    /**
     * Sendy installation URL
     * @var string|NULL
     */
    protected $_URL = NULL;
    /**
     * Api key
     * @var string|null
     */
    protected $_apiKey = NULL;
    /**
     * Additional cUrL options
     * @var array
     */
    protected $_cURLOption = array();

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
    public function createCampaign($brandID, Model\Campaign $campaign)
    {

    }
    public function sendCampaign(array $listIDs, Model\Campaign $campaign)
    {
        if(count($listIDs) == 0)
            throw new DomainException('List IDs can not be empty');

        $request = array(   'api_key'=>$this->_getApiKey(),
                            'from_name'=>$campaign->getSender()->getName(),
                            'from_email'=>$campaign->getSender()->getAddress(),
                            'reply_to'=>$campaign->getSender()->getReplyAddress(),
                            'subject'=>$campaign->getSubject(),
                            'html_text'=>$campaign->getEmailBody()->getHtml(),
                            'list_ids'=>implode(',',$listIDs),
                            'send_campaign'=>1);

        $plainText = $campaign->getEmailBody()->getPlainText();
        if(!is_null($plainText))
            $request['plain_text'] = $plainText;

        $response = $this->_callSendy(self::URI_CAMPAIGN,$request);
        // @TODO parse response
    }
    /**
     * Sets sendy installation URL
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
     * Sets cURL option
     *
     * You can set cURL options f.e. CURLOPT_SSL_VERIFYPEER or CURLOPT_SSL_VERIFYHOST
     * some parameters (\CURLOPT_RETURNTRANSFER,\CURLOPT_POST,\CURLOPT_POSTFIELDS) are used, if you try to set one of these exception is thrown
     *
     * @param number $option use \CURLOPT_* constant
     * @param mixed $value
     * @throws UnexpectedValueException
     * @see http://php.net/manual/en/function.curl-setopt.php
     */
    public function setCurlOption($option, $value)
    {
        // reserved options check
        if(in_array($option,array(\CURLOPT_RETURNTRANSFER,\CURLOPT_POST,\CURLOPT_POSTFIELDS)))
            throw new UnexpectedValueException('cURL option ['.$option.'] is reserved and can not be changed');

        $this->_cURLOption[$option] = $value;
    }
    /**
     * Clears user defined cURL options
     */
    public function clearCurlOptions()
    {
        $this->_cURLOption = array();
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
     * Checks Email validity
     *
     * returns TRUE if given e-mail address is valid
     *
     * @param mixed $email
     * @return bool
     */
    public static function isEmailValid($email)
    {
        return (filter_var($email,\FILTER_VALIDATE_EMAIL)!==false);
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
    /**
     * Returns user specified cURL options
     *
     * @return array
     */
    protected function _getCurlOptions()
    {
        return $this->_cURLOption;
    }
    /**
     * Call sendy api
     *
     * @param string $URI
     * @param array $params
     * @return string
     */
    protected function _callSendy($URI, array $params)
    {
        $postData = http_build_query($params);
        $resource = curl_init($this->_getURL() .'/'. $URI);

        foreach($this->_getCurlOptions() as $option=>$value)
            curl_setopt($resource, $option, $value);

        curl_setopt($resource, \CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($resource, \CURLOPT_POST, 1);
        curl_setopt($resource, \CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($resource);
        curl_close($resource);

        return $result;
    }
}