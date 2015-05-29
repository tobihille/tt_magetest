<?php

class Tt_MageTest_Xtest_Selenium_TestCase extends Codex_Xtest_Xtest_Selenium_TestCase
{

  public static function getCheckoutConfig($path)
  {
    $path = 'xtest/checkouttest/'.$path;
    $config = Mage::getStoreConfig($path);
    if( $config === NULL ) {
      Mage::throwException( sprintf('Config path %s is null', $path) );
    }
    return $config;
  }


}