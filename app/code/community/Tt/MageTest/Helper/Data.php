<?php

class Tt_MageTest_Helper_Data extends Mage_Core_Helper_Abstract
{

  /**
   * @param $config
   * @param Codex_Xtest_Xtest_Unit_Abstract $testObject
   */
  public function doGeneralTest(array $config, Codex_Xtest_Xtest_Unit_Abstract $testObject)
  {
    if ( !method_exists($testObject, 'doGeneralAssert') )
    {
      //just let the test fail
      $testObject->assertContains('Wrong object given: '.get_class($testObject), '');
    }

    foreach ($config as $configEntry)
    {
      if ( !isset($configEntry['url']) )
      {
        $configEntry['url'] = $this->fetchUrlFromConfig($configEntry);
      }

      error_log( $this->__('Running tests for URL %s', $configEntry['url']) );

      $testObject->dispatch($configEntry['url']);
      $responseBody = $testObject->getResponseBody();
      $testObject->renderHtml($configEntry['rendername'], $responseBody);

      if ( is_array($configEntry['assert']) )
      {
        foreach ($configEntry['assert'] as $assert)
        {
          $testObject->assertContains($assert, $responseBody);
        }
      }
      else
      {
        $testObject->assertContains($configEntry['assert'], $responseBody);
      }

      $testObject->doGeneralAssert($responseBody);
    }
  }

  public function fetchUrlFromConfig(array $configEntry)
  {
    $url = null;

    if ( isset($configEntry['sku']) )
    {
      $prod = Mage::getModel('catalog/product')->getCollection()
        ->addFieldToFilter('sku', $configEntry['sku']);
      $prod = $prod->getFirstItem();

      if ( $prod && $prod->getId() )
      {
        $url = $prod->getProductUrl();
      }
    }
    else if ( isset($configEntry['catid']) )
    {
      $cat = Mage::getModel('catalog/category')->load($configEntry['catid']);

      if ( $cat && $cat->getId() )
      {
        $url = $cat->getUrl();
      }
    }

    if ( stripos($url, 'http') !== false )
    {
      $url = str_replace(Mage::getStoreConfig('web/unsecure/base_url'), '', $url);
      if ( Mage::getStoreConfig('xtest/force/index') == true )
      {
        $url = str_replace('index.php', '', $url);
      }

      $url = trim($url, '/');
    }

    return $url;
  }

}