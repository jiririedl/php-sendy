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
    /**
     * Subscribe API URI
     */
    CONST URI_SUBSCRIBE = 'subscribe';
    /**
     * Unsubscribe API URI
     */
    CONST URI_UNSUBSCRIBE = 'unsubscribe';
    /**
     * Create campaign API URI
     */
    CONST URI_DELETE = 'api/subscribers/delete.php';
    /**
     * Subscribtion status API URI
     */
    CONST URI_SUBSCRIPTION_STATUS = 'api/subscribers/subscription-status.php';
    /**
     * Active subscibers API URI
     */
    CONST URI_ACTIVE_SUBSCRIBER_COUNT = 'api/subscribers/active-subscriber-count.php';
    /**
     * Create campaign API URI
     */
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

    /**
     * PHP interface for Sendy api
     *
     * @param string $URL sendy installation URL
     * @param string|null $apiKey your API key is available in sendy Settings - api key is required almost for all requests
     * @throws Exception\InvalidURLException
     * @throws Exception\DomainException
     */
    public function __construct($URL, $apiKey = NULL)
    {
        $this->setURL($URL);

        if(!is_null($apiKey))
            $this->setApiKey($apiKey);
    }
    /**
     * This method adds a new subscriber to a list.
     *
     * You can also use this method to update an existing subscriber.
     * On another note, you can also embed a subscribe form on your website using Sendy's subscribe form HTML code.
     * Visit View all lists, select your desired list then click 'Subscribe form' at the top of the page.
     *
     * @param string $listID the list id you want to subscribe a user to. This encrypted & hashed id can be found under View all lists section named ID
     * @param string $email user's email
     * @param string|null $name user's name is optional
     * @param array $customFields associative array of custom fields and their values f.e. array('salutation'=>'Mr.','userLevel'=>'VIP+')
     * @param string|null $statusMessage optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why
     * @throws \SendyPHP\Exception [\SendyPHP\Exception\InvalidEmailException|\SendyPHP\Exception\DomainException|\SendyPHP\Exception\CurlException]
     * @return bool
     */
    public function subscribe($listID, $email, $name = NULL, array $customFields = array(), &$statusMessage = NULL)
    {
        if(strlen($listID) == 0)
            throw new Exception\DomainException('List ID can not be empty');
        if(!self::isEmailValid($email))
            throw new Exception\InvalidEmailException($email);

        $request = array(   'email'=>$email,
                            'list'=>$listID,
                            'boolean' => 'true');
        if(!is_null($name))
            $request['name'] = $name;

        foreach ($customFields as $fieldName=>$value)
        {
            if(array_key_exists($fieldName,$request))
                throw new Exception\ForbiddenCustomFieldNameException($fieldName);
            else
                $request[$fieldName] = $value;
        }

        $response = $this->_callSendy(self::URI_SUBSCRIBE,$request);
        if(!in_array($response,array(true,'true','1')))
        {
            $statusMessage = $response;
            return false;
        }
        else
        {
            $statusMessage = 'Success';
            return true;
        }
    }
    /**
     * This method unsubscribes a user from a list.
     *
     * @param string $listID the list id you want to unsubscribe a user from. This encrypted & hashed id can be found under View all lists section named ID
     * @param string $email user's email
     * @param string|null $statusMessage optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why
     * @throws \SendyPHP\Exception [\SendyPHP\Exception\InvalidEmailException|\SendyPHP\Exception\DomainException|\SendyPHP\Exception\CurlException]
     * @return bool
     */
    public function unsubscribe($listID, $email,&$statusMessage = NULL)
    {
        if(strlen($listID) == 0)
            throw new Exception\DomainException('List ID can not be empty');
        if(!self::isEmailValid($email))
            throw new Exception\InvalidEmailException($email);

        $request = array(   'email'=>$email,
                            'list'=>$listID,
                            'boolean' => 'true');

        $response = $this->_callSendy(self::URI_UNSUBSCRIBE,$request);
        if(!in_array($response,array(true,'true','1')))
        {
            $statusMessage = $response;
            return false;
        }
        else
        {
            $statusMessage = 'Success';
            return true;
        }
    }
    
    /**
     * This method deletes a user from a list.
     *
     * @param string $listID the list id you want to delete a user from. This encrypted & hashed id can be found under View all lists section named ID
     * @param string $email user's email
     * @param string|null $statusMessage optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why
     * @throws \SendyPHP\Exception [\SendyPHP\Exception\InvalidEmailException|\SendyPHP\Exception\DomainException|\SendyPHP\Exception\CurlException]
     * @return bool
     */
    public function delete($listID, $email,&$statusMessage = NULL)
    {
        if(strlen($listID) == 0)
            throw new Exception\DomainException('List ID can not be empty');
        if(!self::isEmailValid($email))
            throw new Exception\InvalidEmailException($email);

        $request = array(   'email'=>$email,
                            'list'=>$listID);

        $response = $this->_callSendy(self::URI_DELETE,$request);
        if(!in_array($response,array(true,'true','1')))
        {
            $statusMessage = $response;
            return false;
        }
        else
        {
            $statusMessage = 'Success';
            return true;
        }
    }
    /**
     * This method gets the current status of a subscriber (eg. subscribed, unsubscribed, bounced, complained).
     *
     * @param string $listID
     * @param string $email
     * @throws \SendyPHP\Exception [\SendyPHP\Exception\DomainException|\SendyPHP\Exception\InvalidEmailException|\SendyPHP\Exception\CurlException]
     * @return \SendyPHP\Response\SubscriptionStatus
     */
    public function getSubscriptionStatus($listID, $email)
    {
        if(strlen($listID) == 0)
            throw new Exception\DomainException('List ID can not be empty');
        if(!self::isEmailValid($email))
            throw new Exception\InvalidEmailException($email);

        $request = array(   'api_key'=>$this->_getApiKey(),
                            'list_id'=>$listID,
                            'email' => $email);

        $response = $this->_callSendy(self::URI_SUBSCRIPTION_STATUS,$request);
        return new Response\SubscriptionStatus($response);
    }
    /**
     * This method gets the total active subscriber count.
     *
     * @param string $listID the id of the list you want to get the active subscriber count. This encrypted id can be found under View all lists section named ID
     * @param string|null $statusMessage optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why
     * @throws \SendyPHP\Exception [\SendyPHP\Exception\DomainException|\SendyPHP\Exception\CurlException]
     * @return number|false
     */
    public function getActiveSubscriberCount($listID, &$statusMessage = NULL)
    {
        if(strlen($listID) == 0)
            throw new Exception\DomainException('List ID can not be empty');

        $request = array(   'api_key'=>$this->_getApiKey(),
                            'list_id'=>$listID);

        $response = $this->_callSendy(self::URI_ACTIVE_SUBSCRIBER_COUNT,$request);

        if(!is_numeric($response))
        {
            $statusMessage = $response;
            return false;
        }
        else
        {
            $statusMessage = 'Success';
            return intval($response);
        }
    }
    /**
     * Creates draft of campaign
     *
     * @param string $brandID Brand IDs can be found under 'Brands' page named ID
     * @param Model\Campaign $campaign configured campaign
     * @param string|NULL $statusMessage optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why
     * @throws \SendyPHP\Exception [\SendyPHP\Exception\CurlException]
     * @return bool
     */
    public function createCampaign($brandID, Model\Campaign $campaign, &$statusMessage = NULL)
    {
        $request = array(   'api_key'=>$this->_getApiKey(),
                            'from_name'=>$campaign->getSender()->getName(),
                            'from_email'=>$campaign->getSender()->getAddress(),
                            'reply_to'=>$campaign->getSender()->getReplyAddress(),
                            'subject'=>$campaign->getSubject(),
                            'html_text'=>$campaign->getEmailBody()->getHtml(),
                            'brand_id'=>$brandID,
                            'send_campaign'=>0);

        $plainText = $campaign->getEmailBody()->getPlainText();
        if(!is_null($plainText))
            $request['plain_text'] = $plainText;

        $response = $this->_callSendy(self::URI_CAMPAIGN,$request);
        $statusMessage = $response;
        if($response == 'Campaign created')
            return true;
        else
            return false;
    }
    /**
     * Creates draft and automatically sends campaign
     *
     * @param array $listIDs The encrypted & hashed ids can be found under View all lists section named ID.
     * @param Model\Campaign $campaign configured campaign
     * @param string|NULL $statusMessage optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why
     * @throws \SendyPHP\Exception [\SendyPHP\Exception\CurlException|\SendyPHP\Exception\DomainException]
     * @return bool
     */
    public function sendCampaign(array $listIDs, Model\Campaign $campaign, &$statusMessage = NULL)
    {
        if(count($listIDs) == 0)
            throw new Exception\DomainException('List IDs can not be empty');

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
        $statusMessage = $response;
        if($response == 'Campaign created and now sending')
            return true;
        else
            return false;
    }
    /**
     * Sets sendy installation URL
     *
     * @param string $URL
     * @throws \SendyPHP\Exception\InvalidURLException
     */
    public function setURL($URL)
    {
        if(!self::isURLValid($URL))
            throw new Exception\InvalidURLException($URL);

        $this->_URL = $URL;
    }
    /**
     * Sets api key
     *
     * your API key is available in sendy Settings
     *
     * @param string $apiKey
     * @throws \SendyPHP\Exception\DomainException
     */
    public function setApiKey($apiKey)
    {
        if(!is_string($apiKey))
            throw new Exception\DomainException('Api key have to be string '.gettype($apiKey).' given');

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
     * @throws \SendyPHP\Exception\UnexpectedValueException
     * @see http://php.net/manual/en/function.curl-setopt.php
     */
    public function setCurlOption($option, $value)
    {
        // reserved options check
        if(in_array($option,array(\CURLOPT_RETURNTRANSFER,\CURLOPT_POST,\CURLOPT_POSTFIELDS)))
            throw new Exception\UnexpectedValueException('cURL option ['.$option.'] is reserved and can not be changed');

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
     * @throws Exception\UnexpectedValueException
     * @return string
     */
    protected function _getURL()
    {
        if(is_null($this->_URL))
            throw new Exception\UnexpectedValueException('There is no Sendy URL defined - use setURL() first');

        return $this->_URL;
    }
    /**
     * Returns API key
     *
     * @return string
     * @throws Exception\UnexpectedValueException
     */
    protected function _getApiKey()
    {
        if(is_null($this->_apiKey))
            throw new Exception\UnexpectedValueException('There is no Sendy Api Key defined - use setAPIKey() first');

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
     * @throws Exception\CurlException
     * @return string
     */
    protected function _callSendy($URI, array $params)
    {
        $url = $this->_getURL() .'/'. $URI;
        $resource = curl_init($url);

        if($resource === false)
            throw new Exception\CurlException('initialization failed. URL: '.$url);

        $postData = http_build_query($params);

        $curlOptions = $this->_getCurlOptions();

        $curlOptions[\CURLOPT_RETURNTRANSFER] = 1;
        $curlOptions[\CURLOPT_POST] = 1;
        $curlOptions[\CURLOPT_POSTFIELDS] = $postData;

        foreach($curlOptions as $option=>$value)
        {
            if(!curl_setopt($resource, $option, $value))
                throw new Exception\CurlException('option setting failed. Option: ['.$option.'] , value ['.$value.'])',$resource);
        }

        $result = curl_exec($resource);
        if($result === false)
            throw new Exception\CurlException('exec failed',$resource);

        curl_close($resource);

        return $result;
    }
}
