<?php

class Tt_MageTest_Test_AdminOrderTest extends Tt_MageTest_Xtest_Unit_Admin
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

  public function testSalesOrder()
  {
    $salesConfig = self::getAdminConfig('sales');

    $this->_helper->doGeneralTest($salesConfig, $this);
  }

}