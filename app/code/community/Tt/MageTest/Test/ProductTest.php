<?php

class Tt_MageTest_Test_ProductTest extends Tt_MageTest_Xtest_Unit_Product
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

  public function testProducts()
  {
    $productConfig = self::getProductConfig('products');

    $this->_helper->doGeneralTest($productConfig, $this);
  }
}