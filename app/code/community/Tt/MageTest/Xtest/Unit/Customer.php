<?php

class Tt_MageTest_Xtest_Unit_Customer extends Tt_MageTest_Xtest_Unit_Frontend
{

  public static function getCustomerConfig($path)
  {
    $path = 'xtest/customertest/'.$path;
    $config = Mage::getStoreConfig($path);
    if( $config === NULL ) {
      Mage::throwException( sprintf('Config path %s is null', $path) );
    }
    return $config;
  }

}