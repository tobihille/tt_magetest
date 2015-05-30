<?php

class Tt_MageTest_Helper_Data extends Mage_Core_Helper_Abstract
{

  public static function getPageConfig($path, $replacements = array())
  {
    $path = 'xtest/pageconfig/'.$path;
    $config = Mage::getStoreConfig($path);

    if ( !empty($config) && is_string($config) )
    {
      $config = Mage::helper('tt_magetest')->stringParser($config, $replacements);
    }

    return $config;
  }

  /**
   * Wrapper for simple call in *Test - Classes
   * @param array $config Array which contains all XML-Nodes belonging to this test
   * @param $testObject should be "$this", needs to implement doGeneralAssert
   */
  public function doGeneralTest(array $config, $testObject)
  {
    if ( !method_exists($testObject, 'doGeneralAssert') )
    {
      //just let the test fail
      $testObject->assertContains('Wrong object given: '.get_class($testObject), '');
    }

    foreach ($config as $configEntry)
    {
      $configEntry['url'] = $this->fetchUrlFromConfig($configEntry);

      error_log( $this->__('Running tests for URL %s', $configEntry['url']['url']) );

      if ( isset($configEntry['clickon']) && $testObject instanceof Codex_Xtest_Xtest_Selenium_TestCase)
      {
        $this->doPageTest($configEntry, $testObject);
      }
      else
      {
        $this->doRegularTest($configEntry, $testObject);
      }
    }
  }

  /**
   * Runs a Selenium_TestCase, useful if we are running an interactive Test with assertions
   * @param array $configEntry Single test-node from XML
   * @param Codex_Xtest_Xtest_Selenium_TestCase $testObject Passed testObject, needs to implement doGeneralAssert
   */
  protected function doPageTest(array $configEntry, Codex_Xtest_Xtest_Selenium_TestCase $testObject)
  {
    /**
     * @var Codex_Xtest_Xtest_Pageobject_Frontend_Homepage $page
     */
    $page = $testObject->getPageObject('xtest/pageobject_frontend_homepage');

    $urlinfo = parse_url(Mage::getBaseUrl());
    $urlinfo['path'] = trim($urlinfo['path']).$configEntry['url']['url'];

    if ( $configEntry['url']['method'] === 'get' ) //POST is not poassible with selenium :-(
    {
      $urlinfo['query'] = $configEntry['url']['params'];
    }

    $url = $this->http_build_url($urlinfo);
    $page->url($url);

    $elements = $page->findElementsByCssSelector($configEntry['clickon']);

    foreach ($elements as $element)
    {
      $element->click();
    }

    $responseBody = $page->source();

    if ( is_array($configEntry['assert']) )
    {
      foreach ($configEntry['assert'] as $assert)
      {
        $assert = $this->stringParser($assert);
        $page->assertContains($assert, $responseBody, 'Assert failed: '.$assert);
      }
    }
    else
    {
      $assert = $this->stringParser($configEntry['assert']);
      $page->assertContains($assert, $responseBody, 'Assert failed: '.$assert);
    }

    $page->takeResponsiveScreenshots($configEntry['rendername']);

    $testObject->doGeneralAssert($responseBody);
  }

  /**
   * Does a 'simple' test, no interactivity
   * @param array $configEntry Single test-node from XML
   * @param Codex_Xtest_Xtest_Unit_Abstract $testObject Passed testObject, needs to implement doGeneralAssert
   * @param bool $omitScreenshot defines if to take a screenshots, default = yes (do not omit)
   */
  protected function doRegularTest(array $configEntry, Codex_Xtest_Xtest_Unit_Abstract $testObject, $omitScreenshot = false)
  {
    if ( $configEntry['url']['method'] === 'get' )
    {
      $testObject->dispatch($configEntry['url']['url'], $configEntry['url']['params']);
    }
    else
    {
      $testObject->dispatch($configEntry['url']['url'], array(), $configEntry['url']['params']);
    }

    $responseBody = $testObject->getResponseBody();

    if ( !$omitScreenshot )
    {
      $testObject->renderHtml($configEntry['rendername'], $responseBody);
    }

    if ( is_array($configEntry['assert']) )
    {
      foreach ($configEntry['assert'] as $assert)
      {
        $assert = $this->stringParser($assert);
        $testObject->assertContains($assert, $responseBody);
      }
    }
    else
    {
      $assert = $this->stringParser($configEntry['assert']);
      $testObject->assertContains($assert, $responseBody);
    }

    $testObject->doGeneralAssert($responseBody);
  }

  /**
   * Parses config-entry and generates a url (get URL from SKU, get URL from Category-ID, fetch query-params)
   * @param array $configEntry
   * @return array in format (url => /foo..., params => array(bar => baz), method => get)
   */
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
    else if ( isset($configEntry['url']) )
    {
      $url = $configEntry['url'];
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

    $paramArray = array();
    $method = 'get';

    if (  isset($configEntry['params']) )
    {
      foreach ($configEntry['params'] as $param)
      {
        $paramArray[$param['key']] = $param['value'];
        if ( isset($param['method']) )
        {
          $method = $param['method'];
        }
      }
    }

    return array('url' => $url, 'params' => $paramArray, 'method' => $method);
  }

  /**
   * Replaces certain strings in given string
   * @param $rawString
   * @return string
   */
  public function stringParser($rawString, $additionalReplacements = array())
  {
    $replacedString = str_replace(
        '[[unsecure_base_url]]',
        Mage::getStoreConfig('web/unsecure/base_url'). (Mage::getStoreConfig('xtest/force/index') ? 'index.php/' : ''),
        $rawString);

    $replacedString = str_replace(
        '[[secure_base_url]]',
        Mage::getStoreConfig('web/secure/base_url'). (Mage::getStoreConfig('xtest/force/index') ? 'index.php/' : ''),
        $replacedString);

    foreach ( $additionalReplacements as $key => $value )
    {
      $replacedString = str_replace(
          '[['.$key.']]',
          $value,
          $replacedString);
    }

    return $replacedString;
  }

  /**
   * Wrapper for http_build_url, just to reduce dependencies
   * @param array $urlinfo Output-array from parse_url
   * @return string
   */
  protected function http_build_url(array $urlinfo)
  {
    $str = '';

    $str .= $urlinfo['scheme'].'://';

    if ( !empty($urlinfo['user']) )
    {
      $str .= $urlinfo['user'];
    }
    if ( !empty($urlinfo['pass']) )
    {
      $str .= ':'.$urlinfo['pass'];
    }

    $str .= $urlinfo['host'];

    if ( !empty($urlinfo['port']) )
    {
      $str .= ':'.$urlinfo['port'];
    }

    $str .= '/'.$urlinfo['path'];

    if ( !empty($urlinfo['query']) )
    {
      $str .= '?'.http_build_query($urlinfo['query']);
    }

    if ( !empty($urlinfo['fragment']) )
    {
      $str .= '#'.$urlinfo['fragment'];
    }

    return $str;
  }
}