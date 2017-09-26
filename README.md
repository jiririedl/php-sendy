# PHP sendy client
PHP interface for Sendy api ([http://sendy.co/](http://sendy.co/?ref=q5mRD "Sendy - Send newsletters, 100x cheaper via Amazon SES")) with full support Sendy API ([http://sendy.co/api](http://sendy.co/api?ref=q5mRD "Sendy - API documentation"))

# Installation
## Using composer
Simply add new require <code>jiririedl/php-sendy</code> to your <code>composer.json</code>.
```json
"require": {
    "jiririedl/php-sendy" : ">=1.0.0"
}
```
and update in console
```sh
composer update
```
## Using autoload (alternative dirty way)
If you don't use Composer (for some reasons) you can download and include library bootstrap into your project manually. 
This wil add spl_autoload for SendyPHP namespace.
```php
$phpSendyPath = ''; // here you can fill something like 'vendor/SendyPHP' 
require_once($phpSendyPath.'/bootstrap.php');
```
## Using autoload method (alternative dirty dirty way)
If you have your own solution of class autoloading, there is prepared autload function in <code>/src/autoload.php</code>.
Calling ```\SendyPHP\autoload($className)``` includes requested class from <code>SendyPHP</code> namespace or returns FALSE

# Usage
Create instance of <code>\SendyPHP\Sendy</code> with URL of your Sendy installation and API key.
Your API key is located in sendy - login as Admin and go to "Settings" (/settings) - your key is located in right side under topic "Your API key" beware of white spaces!
```php
$sendy = new \SendyPHP\Sendy('http://mysendyinstalation.mydomain','myAPIKey');
```
Some request doesn't need API key for work (f.e. <a href="#subscribe">subscribe()</a> or <a href="#unsubscribe">unsubscribe()</a>) so setting api key is optional.
You can also set api key by using <a href="#user-content-set-api-key">setApiKey()</a> method or redefine sendy URL by <a href="#set-url">setURL()</a>.

## Methods

### Sendy API Requests
All requests uses curl library for calling Sendy API. 
If you have installed library by using Composer, curl was checked automatically, 
otherwise you can check this in your phpinfo, or just try to call some method from curl (http://php.net/manual/en/ref.curl.php).

#### Subscribe
This method adds a new subscriber to a list.
You can also use this method to update an existing subscriber.
```php
bool subscribe($listID, $email, $name = NULL, array $customFields = array(), &$statusMessage = NULL)
```
##### Parameters
* $listID <string> - the list id you want to subscribe a user to. This encrypted & hashed id can be found under View all lists section named ID
* $email <string> - user's email
* $name <string|null> - optional -user's name is optional
* $customFields <array> - optional - associative array of custom fields and their values f.e. array('salutation'=>'Mr.','userLevel'=>'VIP+')
* $statusMessage <string|null> - optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why

##### Example
```php
try{
    $sendy = new \SendyPHP\Sendy('http://mysendyinstalation.mydomain','myAPIKey');

    $statusMessage = '';
    $status = $sendy->subscribe('myHashedListID','newsubscribers@email.com','John Doe',$statusMessage);

    if($status)
        echo "Yeah! New subscriber successfully added";
    else
        echo "Ops! Sendy API responds a problem with adding subscriber - Sendy PHP message :".$statusMessage;

}catch (\SendyPHP\Exception $e)
{
    echo "Ops! An exception raised: ".$e;
}
```
##### Exceptions 
All exceptions are extended from <code>\SendyPHP\Exception</code> so you can easily catch just this parent class
* <code>\SendyPHP\Exception\InvalidEmailException</code> is thrown if email address is not valid
* <code>\SendyPHP\Exception\DomainException</code> is thrown if $listID is empty
* <code>\SendyPHP\Exception\CurlException</code> is thrown if cURL library could not handle your request

##### Return values
Returns TRUE on success or FALSE on failure.

#### UnSubscribe
This method unsubscribes a user from a list.
```php
bool unsubscribe($listID, $email,&$statusMessage = NULL)
```
##### Parameters
* $listID <string> - the list id you want to unsubscribe a user from. This encrypted & hashed id can be found under View all lists section named ID
* $email <string> - user's email
* $statusMessage <string|null> - optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why

##### Example
```php
try{
    $sendy = new \SendyPHP\Sendy('http://mysendyinstalation.mydomain','myAPIKey');

    $statusMessage = '';
    $status = $sendy->unsubscribe('myHashedListID','newsubscribers@email.com',$statusMessage);

    if($status)
        echo "Subscriber successfully removed from list";
    else
        echo "Ops! Sendy API responds a problem with unsubscribing - Sendy PHP message :".$statusMessage;

}catch (\SendyPHP\Exception $e)
{
    echo "Ops! An exception raised: ".$e;
}
```
##### Exceptions 
All exceptions are extended from <code>\SendyPHP\Exception</code> so you can easily catch just this parent class
* <code>\SendyPHP\Exception\InvalidEmailException</code> is thrown if email address is not valid
* <code>\SendyPHP\Exception\DomainException</code> is thrown if $listID is empty
* <code>\SendyPHP\Exception\CurlException</code> is thrown if cURL library could not handle your request

##### Return values
Returns TRUE on success or FALSE on failure.

#### Delete
This method deletes a subscriber off a list (only supported in Sendy version 2.1.1.4 and above).
```php
bool delete($listID, $email,&$statusMessage = NULL)
```
##### Parameters
* $listID <string> - the list id you want to delete a user from. This encrypted & hashed id can be found under View all lists section named ID
* $email <string> - user's email
* $statusMessage <string|null> - optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why

##### Example
```php
try{
    $sendy = new \SendyPHP\Sendy('http://mysendyinstalation.mydomain','myAPIKey');

    $statusMessage = '';
    $status = $sendy->delete('myHashedListID','newsubscribers@email.com',$statusMessage);

    if($status)
        echo "Subscriber successfully deleted from list";
    else
        echo "Ops! Sendy API responds a problem with deleting - Sendy PHP message :".$statusMessage;

}catch (\SendyPHP\Exception $e)
{
    echo "Ops! An exception raised: ".$e;
}
```
##### Exceptions 
All exceptions are extended from <code>\SendyPHP\Exception</code> so you can easily catch just this parent class
* <code>\SendyPHP\Exception\InvalidEmailException</code> is thrown if email address is not valid
* <code>\SendyPHP\Exception\DomainException</code> is thrown if $listID is empty
* <code>\SendyPHP\Exception\CurlException</code> is thrown if cURL library could not handle your request

##### Return values
Returns TRUE on success or FALSE on failure.

#### Get active subscriber count
This method gets the total active subscriber count.
```php
number|false getActiveSubscriberCount($listID, &$statusMessage = NULL)
```
##### Parameters
* $listID <string> - the list id you want to subscribe a user to. This encrypted & hashed id can be found under View all lists section named ID
* $statusMessage <string|null> - optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why

##### Example
```php
try{
    $sendy = new \SendyPHP\Sendy('http://mysendyinstalation.mydomain','myAPIKey');

    $statusMessage = '';
    $subscribersCount = $sendy->getActiveSubscriberCount('myHashedListID',$statusMessage);

    if($subscribersCount!==false)
        echo "In this list is $subscribersCount active subscribers";
    else
        echo "Ops! Sendy API responds a problem with getting active subscribers count - Sendy PHP message :".$statusMessage;

}catch (\SendyPHP\Exception $e)
{
    echo "Ops! An exception raised: ".$e;
}
```
##### Exceptions 
All exceptions are extended from <code>\SendyPHP\Exception</code> so you can easily catch just this parent class
* <code>\SendyPHP\Exception\DomainException</code> is thrown if $listID is empty
* <code>\SendyPHP\Exception\CurlException</code> is thrown if cURL library could not handle your request

##### Return values
Returns number of active subscribers or FALSE on failure.

#### Get subscribtion status
This method gets the current status of a subscriber (eg. subscribed, unsubscribed, bounced, complained).
```php
\SendyPHP\Response\SubscriptionStatus getSubscriptionStatus($listID, $email)
```
##### Parameters
* $listID <string> - the list id you want to subscribe a user to. This encrypted & hashed id can be found under View all lists section named ID
* $email <string> - user's email

##### Example
```php
try{
    $sendy = new \SendyPHP\Sendy('http://mysendyinstalation.mydomain','myAPIKey');

    $subscriptionStatus = $sendy->getSubscriptionStatus('myHashedListID','mysubscribers@email.com');

    if($subscriptionStatus->success())
    {
        switch(true)
        {
            case $subscriptionStatus->isSubscribed():
                echo "Subscribed";
                break;
            case $subscriptionStatus->isUnSubscribed():
                echo "Unsubscribed";
                break;
            case $subscriptionStatus->isComplained():
                echo "Complained";
                break;
            case $subscriptionStatus->isUnconfirmed():
                echo "Unconfirmed";
                break;
            case $subscriptionStatus->isHardBounced():
                echo "Hard Bounced";
                break;
            case $subscriptionStatus->isSoftBounced():
                echo "Soft bounced";
                break;
        }
    }
    else
        echo "Ops! Sendy API responds a problem with getting subscribtion status - Sendy PHP message :".$subscriptionStatus->getRawResponse();

}catch (\SendyPHP\Exception $e)
{
    echo "Ops! An exception raised: ".$e;
}
```
##### Exceptions 
All exceptions are extended from <code>\SendyPHP\Exception</code> so you can easily catch just this parent class
* <code>\SendyPHP\Exception\DomainException</code> is thrown if $listID is empty
* <code>\SendyPHP\Exception\InvalidEmailException</code> is thrown if email address is not valid
* <code>\SendyPHP\Exception\CurlException</code> is thrown if cURL library could not handle your request

##### Return values
<code>\SendyPHP\Response\SubscriptionStatus</code>
returned object has many of usable methods (see phpdoc) f.e. by calling success() are you able to check if API returns some subscribers status.

#### Create campaign
Creates draft of campaign
```php
bool createCampaign($brandID, Model\Campaign $campaign, &$statusMessage = NULL)
```
##### Parameters
* $brandID <number> - Brand IDs can be found under 'Brands' page named ID
* $campaign <\SendyPHP\Model\Campaign> - configured campaign
* $statusMessage <string|null> - optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why

##### Example
```php
try{
    $sender = new \SendyPHP\Model\Sender('From name','from-adrress@mydomain.com','reply-address@mydomain.com');
    $emailBody = new \SendyPHP\Model\EmailBody('<h1>HTML body of my newsletter</h1>', 'Plaintext body of my newsletter');
    $campaign = new \SendyPHP\Model\Campaign($sender,'My first great newsletter!',$emailBody);
    $brandID = 1; // here fill your brand ID

    $sendy = new \SendyPHP\Sendy('http://mysendyinstalation.mydomain','myAPIKey');

    $statusMessage = '';
    $status = $sendy->createCampaign($brandID,$campaign,$statusMessage);

    if($status)
    {
        echo "Campaign successfully created";
    }
    else
        echo "Ops! Sendy API responds a problem with creating campaign - Sendy PHP message :".$statusMessage;

}catch (\SendyPHP\Exception $e)
{
    echo "Ops! An exception raised: ".$e;
}
```
##### Exceptions 
All exceptions are extended from <code>\SendyPHP\Exception</code> so you can easily catch just this parent class
* <code>\SendyPHP\Exception\CurlException</code> is thrown if cURL library could not handle your request

##### Return values
Returns TRUE on success or FALSE on failure.

#### Send campaign
Creates draft and automatically sends campaign
```php
bool sendCampaign(array $listIDs, Model\Campaign $campaign, &$statusMessage = NULL)
```
##### Parameters
* $listIDs <number[]> - The encrypted & hashed ids can be found under View all lists section named ID.
* $campaign <\SendyPHP\Model\Campaign> - configured campaign
* $statusMessage <string|null> - optional - here will be returned status message f.e. if you get FALSE again, and again, here you can find why

##### Example
```php
try{
    $sender = new \SendyPHP\Model\Sender('From name','from-adrress@mydomain.com','reply-address@mydomain.com');
    $emailBody = new \SendyPHP\Model\EmailBody('<h1>HTML body of my newsletter</h1>', 'Plaintext body of my newsletter');
    $campaign = new \SendyPHP\Model\Campaign($sender,'My first great newsletter!',$emailBody);
    $listIDs = array(1); // here fill your list IDs

    $sendy = new \SendyPHP\Sendy('http://mysendyinstalation.mydomain','myAPIKey');

    $statusMessage = '';
    $status = $sendy->sendCampaign($listIDs,$campaign,$statusMessage);

    if($status)
    {
        echo "Campaign successfully created and now sending";
    }
    else
        echo "Ops! Sendy API responds a problem with creating and sending campaign - Sendy PHP message :".$statusMessage;

}catch (\SendyPHP\Exception $e)
{
    echo "Ops! An exception raised: ".$e;
}
```
##### Exceptions 
All exceptions are extended from <code>\SendyPHP\Exception</code> so you can easily catch just this parent class
* <code>\SendyPHP\Exception\CurlException</code> is thrown if cURL library could not handle your request
* <code>\SendyPHP\Exception\DomainException</code> is thrown if $listIDs array is empty

##### Return values
Returns TRUE on success or FALSE on failure.

### Other methods
#### Set cURL option
Sets cURL option
You can set cURL options f.e. <code>CURLOPT_SSL_VERIFYPEER</code> or <code>CURLOPT_SSL_VERIFYHOST</code>
some parameters (<code>\CURLOPT_RETURNTRANSFER</code>, <code>\CURLOPT_POST</code>, <code>\CURLOPT_POSTFIELDS</code>) are used, if you try to set one of these exception is thrown.
See http://php.net/manual/en/function.curl-setopt.php for more informations.
```php
void setCurlOption($option, $value)
```
##### Parameters
* $option <number> - use <code>\CURLOPT_</code>* constant
* $value mixed

##### Exceptions
<code>\SendyPHP\Exception\UnexpectedValueException</code> is thrown if you try to set one of predefined options (<code>\CURLOPT_RETURNTRANSFER</code>, <code>\CURLOPT_POST</code> and <code>\CURLOPT_POSTFIELDS</code>).

#### Clear cURL option
Sets cURL option
Clears user defined cURL options
```php
void clearCurlOptions()
```

#### Set URL
Sets sendy installation URL
Clears user defined cURL options
```php
void setURL($URL)
```
##### Exceptions
<code>\SendyPHP\Exception\InvalidURLException</code> is thrown if URL is invalid.

#### Set API key
Sets api key
```php
void setApiKey($apiKey)
```
##### Parameters
* $apiKey <string> - sendy API key - your API key is available in sendy Settings

##### Exceptions
<code>\SendyPHP\Exception\DomainException</code> is thrown if API key is not string.
