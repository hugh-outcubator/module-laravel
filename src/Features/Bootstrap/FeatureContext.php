<?php

namespace PaymentwallLaravel\Features\Bootstrap;

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use PaymentwallLaravel\Lib\Base;
use PaymentwallLaravel\Lib\Config;
use PaymentwallLaravel\Features\Bootstrap\PingbackContext;
use PaymentwallLaravel\Features\Bootstrap\WidgetContext;
use PaymentwallLaravel\Features\Bootstrap\ChargeContext;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->useContext('pingback', new PingbackContext(array()));
        $this->useContext('widget', new WidgetContext(array()));
        $this->useContext('charge', new ChargeContext(array()));
    }

    /**
     * @Given /^Public key "([^"]*)"$/
     */
    public function publicKey($publicKey)
    {
        Base::setAppKey($publicKey);
    }

    /**
     * @Given /^Secret key "([^"]*)"$/
     */
    public function secretKey($secretKey)
    {
        Base::setSecretKey($secretKey);
    }

    /**
     * @Given /^Private key "([^"]*)"$/
     */
    public function privateKey($privateKey)
    {
        Config::getInstance()->set(array(
            'private_key' => $privateKey
        ));
    }

    /**
     * @Given /^API type "([^"]*)"$/
     */
    public function apiType($apiType)
    {
        Base::setApiType($apiType);
        $this->apiType = $apiType;
    }
}
