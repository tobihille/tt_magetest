<?php

class Tt_MageTest_Xtest_Unit_Admin extends Codex_Xtest_Xtest_Unit_Admin
{

  public static function getAdminConfig($path, $noError = false)
  {
    $path = 'xtest/admintest/'.$path;
    $config = Mage::getStoreConfig($path);
    if( $config === NULL && !$noError ) {
      Mage::throwException( sprintf('Config path %s is null', $path) );
    }
    return $config;
  }

  protected function _doDispatch(Codex_Xtest_Model_Core_Controller_Request_Http $request, $postData = null, $adminuser = null)
  {
    Mage::app()->getStore()->setConfig("admin/security/use_form_key", 0);

    if( !$adminuser )
    {
      $adminUsrConfig = self::getAdminConfig('user', true);

      if ( !empty($adminUsrConfig) )
      {
        $adminuser = $adminUsrConfig;
      }
      else
      {
        $adminusers = Mage::getModel('admin/user')->getCollection();
        if ($adminusers->getSize() >= 1) {
          $adminuser = $adminusers->getFirstItem();
        }
      }
    }
    $adminsession = Mage::getSingleton('admin/session');
    $adminsession->setUser($adminuser);
    $adminsession->setAcl(Mage::getResourceModel('admin/acl')->loadAcl());

    parent::_doDispatch($request, $postData);
  }

  public function doGeneralAssert($textToCheck)
  {
    $this->assertContains( Mage::helper('adminhtml')->__('Logged in as'), $textToCheck);
    $this->assertContains( Mage::helper('adminhtml')->__('Log Out'), $textToCheck);
    $this->assertContains('skin/adminhtml/default/default/images/logo.gif" alt="Magento Logo" class="logo"/>', $textToCheck);
    $this->assertContains( "</body>", $textToCheck);
  }
}