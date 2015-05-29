<?php

class Tt_MageTest_Helper_Data extends Mage_Core_Helper_Abstract
{

  /**
   * @param $config
   * @param Tt_MageTest_Helper_AbstractFrontendT|Tt_MageTest_Helper_AbstractAdminT $testObject
   */
  public function doGeneralTest($config, Codex_Xtest_Xtest_Unit_Abstract $testObject)
  {
    if ( !method_exists($testObject, 'doGeneralAssert') )
    {
      //just let the test fail
      $testObject->assertContains('Wrong object given: '.get_class($testObject), '');
    }

    foreach ($config as $configEntry)
    {
      $testObject->dispatch($configEntry['url']);
      $responseBody = $testObject->getResponseBody();
      $testObject->renderHtml($configEntry['rendername'], $responseBody);

      $testObject->assertContains($configEntry['assert'], $responseBody);
      $testObject->doGeneralAssert($responseBody);
    }
  }

}