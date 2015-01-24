<?php
namespace SendyPHP\Tests\Response;

/**
 * Checks all subscription status response parsing
 *
 * @package SendyPHP\Tests
 */
class SubscriptionStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider successResponseProvider
     * @param string $rawResponse
     */
    public function testSuccess($rawResponse)
    {
        $response = new \SendyPHP\Response\SubscriptionStatus($rawResponse);
        $this->assertTrue($response->success());
        $this->assertFalse($response->failed());
    }
    /**
     * @test
     * @dataProvider failedResponseProvider
     * @param string $rawResponse
     */
    public function testFailed($rawResponse)
    {
        $response = new \SendyPHP\Response\SubscriptionStatus($rawResponse);
        $this->assertFalse($response->success());
        $this->assertTrue($response->failed());
    }
    /**
     * @test
     */
    public function testSubscribed()
    {
        $response = new \SendyPHP\Response\SubscriptionStatus('Subscribed');
        $this->assertTrue($response->success());
        $this->assertFalse($response->failed());
        $this->assertTrue($response->isSubscribed());
        $this->assertFalse($response->isBounced());
        $this->assertFalse($response->isSoftBounced());
        $this->assertFalse($response->isHardBounced());
        $this->assertFalse($response->isUnconfirmed());
        $this->assertFalse($response->isUnSubscribed());
        $this->assertFalse($response->isComplained());
    }
    /**
     * @test
     */
    public function testUnSubscribed()
    {
        $response = new \SendyPHP\Response\SubscriptionStatus('Unsubscribed');
        $this->assertTrue($response->success());
        $this->assertFalse($response->failed());
        $this->assertFalse($response->isSubscribed());
        $this->assertFalse($response->isBounced());
        $this->assertFalse($response->isSoftBounced());
        $this->assertFalse($response->isHardBounced());
        $this->assertFalse($response->isUnconfirmed());
        $this->assertTrue($response->isUnSubscribed());
        $this->assertFalse($response->isComplained());
    }
    /**
     * @test
     */
    public function testUnconfirmed()
    {
        $response = new \SendyPHP\Response\SubscriptionStatus('Unconfirmed');
        $this->assertTrue($response->success());
        $this->assertFalse($response->failed());
        $this->assertFalse($response->isSubscribed());
        $this->assertFalse($response->isBounced());
        $this->assertFalse($response->isSoftBounced());
        $this->assertFalse($response->isHardBounced());
        $this->assertTrue($response->isUnconfirmed());
        $this->assertFalse($response->isUnSubscribed());
        $this->assertFalse($response->isComplained());
    }
    /**
     * @test
     */
    public function testBounced()
    {
        $response = new \SendyPHP\Response\SubscriptionStatus('Bounced');
        $this->assertTrue($response->success());
        $this->assertFalse($response->failed());
        $this->assertFalse($response->isSubscribed());
        $this->assertTrue($response->isBounced());
        $this->assertFalse($response->isSoftBounced());
        $this->assertTrue($response->isHardBounced());
        $this->assertFalse($response->isUnconfirmed());
        $this->assertFalse($response->isUnSubscribed());
        $this->assertFalse($response->isComplained());
    }
    /**
     * @test
     */
    public function testSoftBounced()
    {
        $response = new \SendyPHP\Response\SubscriptionStatus('Soft bounced');
        $this->assertTrue($response->success());
        $this->assertFalse($response->failed());
        $this->assertFalse($response->isSubscribed());
        $this->assertTrue($response->isBounced());
        $this->assertTrue($response->isSoftBounced());
        $this->assertFalse($response->isHardBounced());
        $this->assertFalse($response->isUnconfirmed());
        $this->assertFalse($response->isUnSubscribed());
        $this->assertFalse($response->isComplained());
    }
    /**
     * @test
     */
    public function testComplained()
    {
        $response = new \SendyPHP\Response\SubscriptionStatus('Complained');
        $this->assertTrue($response->success());
        $this->assertFalse($response->failed());
        $this->assertFalse($response->isSubscribed());
        $this->assertFalse($response->isBounced());
        $this->assertFalse($response->isSoftBounced());
        $this->assertFalse($response->isHardBounced());
        $this->assertFalse($response->isUnconfirmed());
        $this->assertFalse($response->isUnSubscribed());
        $this->assertTrue($response->isComplained());
    }
    /**
     * @test
     * @dataProvider badResponseProvider
     * @param mixed $response
     */
    public function testBadResponse($response)
    {
        $response = new \SendyPHP\Response\SubscriptionStatus(NULL);
        $this->assertFalse($response->success());
        $this->assertTrue($response->failed());
        $this->assertFalse($response->isSubscribed());
        $this->assertFalse($response->isBounced());
        $this->assertFalse($response->isSoftBounced());
        $this->assertFalse($response->isHardBounced());
        $this->assertFalse($response->isUnconfirmed());
        $this->assertFalse($response->isUnSubscribed());
        $this->assertFalse($response->isComplained());
    }
    /**
     * Dataprovider for success responses
     *
     * Includes all success responses from documentation
     * @link http://sendy.co/api section ["Subscription status" > "Response" > "Error"]
     * @return array
     */
    public static function successResponseProvider()
    {
        return array(array('Subscribed'),array('Unsubscribed'),array('Unconfirmed'),array('Bounced'),array('Soft bounced'),array('Complained'));
    }
    /**
     * Dataprovider for error responses
     *
     * Includes all error responses from documentation
     * @link http://sendy.co/api section ["Subscription status" > "Response" > "Error"]
     * @return array
     */
    public static function failedResponseProvider()
    {
        return array(array('No data passed'),array('API key not passed'),array('Email not passed'),array('List ID not passed'),array('Email does not exist in list'));
    }
    /**
     * Dataprovider for bad responses
     *
     * @return array
     */
    public static function badResponseProvider()
    {
        return array(array(NULL),array(''),array(-1),array(0));
    }
}
 