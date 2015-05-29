<?php

class Tt_MageTest_Test_UrlInteractiveFrontendTest extends Tt_MageTest_Xtest_Selenium_Interactive
{

  /**
   * @var Tt_MageTest_Helper_Data
   */
  protected $_helper = null;

  public function __construct()
  {
    parent::__construct();

    $this->_helper = Mage::helper('tt_magetest');
  }

  public function testRenderUrls()
  {
    $urlsConfig = self::getInteractiveConfig('urls');

    $this->_helper->doGeneralTest($urlsConfig, $this);
  }
}