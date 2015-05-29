<?php

class Tt_MageTest_Test_CheckoutTest extends Tt_MageTest_Xtest_Selenium_TestCase
{

  protected static $_customerEmail;
  protected static $_customerPassword;

  public function setUp()
  {
    parent::setUp();

    $customerConfig = self::getSeleniumConfig('checkout/customer');
    self::$_customerEmail = $customerConfig['email'];

    // Delete Testcustomer
    $customerCol = Mage::getModel('customer/customer')->getCollection();
    $customerCol->addFieldToFilter('email', self::$_customerEmail );
    $customerCol->walk('delete');

    // Create a new one
    $customer = Mage::getModel('customer/customer');
    $customer->setData($customerConfig);
    self::$_customerPassword = $customer->generatePassword();
    $customer->setStore( current( Mage::app()->getStores() ) );
    $customer->setPassword( self::$_customerPassword );
    $customer->validate();
    $customer->setConfirmation(null);
    $customer->save();

    $customer->load( $customer->getId() );
    $customer->setConfirmation(null);
    $customer->save();

    $_custom_address = array (
        'firstname' => self::getCheckoutConfig('customer/firstname'),
        'lastname' => self::getCheckoutConfig('customer/lastname'),
        'street' => array (
            '0' => self::getCheckoutConfig('customer/billing_address/street1'),
        ),
        'city' => self::getCheckoutConfig('customer/billing_address/city'),

        'region_id' => self::getCheckoutConfig('customer/billing_address/region_id'),
        //'region' => '88',
        'postcode' => self::getCheckoutConfig('customer/billing_address/postcode'),
        'country_id' => self::getCheckoutConfig('customer/billing_address/country_id'),
        'telephone' => self::getCheckoutConfig('customer/billing_address/telephone'),
    );
    $customAddress = Mage::getModel('customer/address');
    $customAddress->setData($_custom_address)
        ->setCustomerId($customer->getId())
        ->setIsDefaultBilling('1')
        ->setIsDefaultShipping('1')
        ->setSaveInAddressBook('1');
    $customAddress->save();
  }

  public function testOnepageCheckout()
  {
    $cartConfig = $this->getSeleniumConfig('checkout/addtocart');

    $noShippingMarker = true; //indicates that only virtual goods are in cart

    foreach( $cartConfig AS $_productData )
    {
      $p = Mage::getModel('catalog/product')->getCollection()->
          addFieldToFilter('sku', $_productData['sku']);
      /** @var Mage_Catalog_Model_Product $p */
      $p = $p->getFirstItem();

      if ( $p->getTypeId() !== 'downloadable' )
      {
        $noShippingMarker = false;
      }

      /** @var $productPageObject Codex_Xtest_Xtest_Pageobject_Frontend_Product */
      $productPageObject = $this->getPageObject('tt_magetest/pageobject_frontend_product');

      $productPageObject->openBySku( $_productData['sku'] );
      $productPageObject->setQty( $_productData['qty'] );

      if ( isset($_productData['clickon']) )
      {
        $clickElements = $productPageObject->findElementsByCssSelector( $_productData['clickon'] );
        foreach ($clickElements as $element)
        {
          $element->click();
        }
      }

      $productPageObject->pressAddToCart();
      $productPageObject->assertAddToCartMessageAppears();

    }

    /** @var $cartPageObject Codex_Xtest_Xtest_Pageobject_Frontend_Cart */
    $cartPageObject = $this->getPageObject('xtest/pageobject_frontend_cart');
    $cartPageObject->open();

    $cartPageObject->takeResponsiveScreenshots('products in cart');

    $this->assertEquals( count($cartConfig), count( $cartPageObject->getItems() ), 'cart is missing some items' );

    $cartPageObject->clickProceedCheckout();
    $this->assertContains('checkout/onepage/', $this->url() );

    // ---

    /** @var $checkoutPageObject Codex_Xtest_Xtest_Pageobject_Frontend_Checkout */
    $checkoutPageObject = $this->getPageObject('xtest/pageobject_frontend_checkout');

    $checkoutPageObject->takeResponsiveScreenshots('login');
    $checkoutPageObject->login( self::$_customerEmail, self::$_customerPassword );
    $checkoutPageObject->assertStepIsActive('billing');

    // ---

    $checkoutPageObject->setBillingAddress();
    $checkoutPageObject->takeResponsiveScreenshots('billing address');
    $checkoutPageObject->nextStep();


    if (!$noShippingMarker)
    {
      // ---
      // TODO: Shipping Address
      // ---

      $checkoutPageObject->assertStepIsActive('shipping_method');
      $checkoutPageObject->setShippingMethod( self::getSeleniumConfig('payment_method/method') );
      $checkoutPageObject->takeResponsiveScreenshots('shipping method');
      $checkoutPageObject->nextStep();
    }
    // ---

    $checkoutPageObject->assertStepIsActive('payment');
    $checkoutPageObject->setPaymentMethod( self::getSeleniumConfig('payment_method/method') );
    $checkoutPageObject->takeResponsiveScreenshots('payment method');
    $checkoutPageObject->nextStep();

    // ---

    $checkoutPageObject->assertStepIsActive('review');
    $checkoutPageObject->acceptAgreements();
    $checkoutPageObject->takeResponsiveScreenshots('review');
    $checkoutPageObject->nextStep();

    // ---

    $checkoutPageObject->takeResponsiveScreenshots();
    $checkoutPageObject->assertIsSuccessPage();

    $checkoutPageObject->takeResponsiveScreenshots('success');
  }

}