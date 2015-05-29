<?php

class Tt_MageTest_Xtest_Selenium_Interactive extends Codex_Xtest_Xtest_Selenium_TestCase
{

  public function doGeneralAssert($textToCheck)
  {
    $this->assertContains( "</body>", $textToCheck);
  }

  public static function getInteractiveConfig($path)
  {
    $path = 'xtest/interactivetest/'.$path;
    $config = Mage::getStoreConfig($path);
    if( $config === NULL ) {
      Mage::throwException( sprintf('Config path %s is null', $path) );
    }
    return $config;
  }
}