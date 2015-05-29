<?php

class Tt_MageTest_Test_UrlFrontendTest extends Tt_MageTest_Xtest_Unit_Frontend
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
    $urlsConfig = self::getFrontendConfig('urls');

    $this->_helper->doGeneralTest($urlsConfig, $this);
  }

}