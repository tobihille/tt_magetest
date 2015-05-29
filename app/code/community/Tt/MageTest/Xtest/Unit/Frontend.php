<?php

class Tt_MageTest_Xtest_Unit_Frontend extends Codex_Xtest_Xtest_Unit_Frontend
{

  public static function getFrontendConfig($path)
  {
    $path = 'xtest/frontendtest/'.$path;
    $config = Mage::getStoreConfig($path);
    if( $config === NULL ) {
      Mage::throwException( sprintf('Config path %s is null', $path) );
    }
    return $config;
  }

  public function doGeneralAssert($textToCheck)
  {
    $this->assertContains( Mage::getUrl('checkout/cart'), $textToCheck);
    $this->assertContains( Mage::getUrl('checkout'), $textToCheck);
    $this->assertContains( Mage::getUrl('customer/account'), $textToCheck);
    $this->assertContains( "</body>", $textToCheck);
  }

}