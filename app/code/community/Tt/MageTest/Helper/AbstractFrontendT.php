<?php

class Tt_MageTest_Helper_AbstractFrontendT extends Codex_Xtest_Xtest_Unit_Frontend
{

  public static function getCmsConfig($path)
  {
    $path = 'xtest/cmstest/'.$path;
    $config = Mage::getStoreConfig($path);
    if( $config === NULL ) {
      Mage::throwException( sprintf('Config path %s is null', $path) );
    }
    return $config;
  }

  protected function doGeneralTest($config)
  {
    foreach ($config as $configEntry)
    {
      $this->dispatch($configEntry['url']);
      $responseBody = $this->getResponseBody();
      $this->renderHtml($configEntry['rendername'], $responseBody);

      $this->assertContains($configEntry['assert'], $responseBody);
      $this->doGeneralAssert($responseBody);
    }
  }

  public function doGeneralAssert($textToCheck)
  {

    $this->assertContains( Mage::getUrl('checkout/cart'), $textToCheck);
    $this->assertContains( Mage::getUrl('checkout'), $textToCheck);
    $this->assertContains( Mage::getUrl('customer/account'), $textToCheck);
    $this->assertContains( "</body>", $textToCheck);

  }

}