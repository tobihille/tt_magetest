<?php

class Tt_MageTest_Test_AdminTest extends Tt_MageTest_Xtest_Unit_Admin
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

  public function testGeneralAdmin()
  {
    $salesConfig = self::getAdminConfig('urls');

    $this->_helper->doGeneralTest($salesConfig, $this);
  }

}