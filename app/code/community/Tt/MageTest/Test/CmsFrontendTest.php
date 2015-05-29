<?php

class Tt_MageTest_Test_CmsFrontendTest extends Tt_MageTest_Xtest_Unit_Frontend
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

  public function testRenderHomepage()
  {
    $homepageConf = self::getFrontendConfig('homepage');

    $this->_helper->doGeneralTest($homepageConf, $this);
  }

  public function testRenderFooter()
  {
    $footerConf = self::getFrontendConfig('footerlinks');

    $this->_helper->doGeneralTest($footerConf, $this);
  }

}