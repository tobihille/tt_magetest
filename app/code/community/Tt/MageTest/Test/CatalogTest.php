<?php

class Tt_MageTest_Test_CatalogTest extends Tt_MageTest_Xtest_Unit_Catalog
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

  public function testCategories()
  {
    $productConfig = self::getCatalogConfig('categories');

    $this->_helper->doGeneralTest($productConfig, $this);
  }
}