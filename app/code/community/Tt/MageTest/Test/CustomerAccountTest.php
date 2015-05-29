<?php

class Tt_MageTest_Test_CustomerAccountTest extends Tt_MageTest_Xtest_Unit_Customer
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

  public function testCustomerAccount()
  {
    $cstConf = self::getCustomerConfig('customers');

    foreach ($cstConf as $customer)
    {
      $customers = Mage::getModel('customer/customer')->getCollection()
          ->addFieldToFilter('email', $customer['username']);

      $this->setCustomerAsLoggedIn($customers->getFirstItem());

      $pagesConf = self::getCustomerConfig('urls');

      $this->_helper->doGeneralTest($pagesConf, $this);
    }
  }

}