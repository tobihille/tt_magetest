<?php

class Tt_MageTest_Xtest_Pageobject_Frontend_Checkout extends Codex_Xtest_Xtest_Pageobject_Frontend_Checkout
{

  public function getLoginForm()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('checkout/loginform');
    if ( empty($css) )
    {
      $css = '#login-form';
    }

    return $this->byCssSelector($css);
  }

  public function login( $email, $password )
  {
    $cssMail = Tt_MageTest_Helper_Data::getPageConfig('checkout/loginemail');
    if ( empty($cssMail) )
    {
      $cssMail = '#login-email';
    }

    $cssPass = Tt_MageTest_Helper_Data::getPageConfig('checkout/loginpass');
    if ( empty($cssPass) )
    {
      $cssPass = '#login-password';
    }

    $this->getLoginForm()->byCssSelector($cssMail)->value($email);
    $this->getLoginForm()->byCssSelector($cssPass)->value($password);

    $this->getLoginForm()->submit();
  }

  /**
   * @param $step_id eg billing, shipping,
   */
  public function assertStepIsActive( $step_id )
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('checkout/currentstepactive', array('step_id' => $step_id));
    if ( empty($css) )
    {
      $css = '#opc-'.$step_id;
    }

    $cssMarker = Tt_MageTest_Helper_Data::getPageConfig('checkout/stepactivemarker');
    if ( empty($cssMarker) )
    {
      $cssMarker = 'active';
    }

    $step = $this->byCssSelector($css);

    $this->assertElementHasClass($cssMarker, $step );
  }

  public function getActiveStepName()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('checkout/currentstep');
    if ( empty($css) )
    {
      $css = '#checkoutSteps .section.active';
    }

    $active = $this->byCssSelector($css);
    return substr($active->attribute('id'), 4);
  }

  public function setCheckoutAddress( $prefix,  $data = null )
  {
    if( $data === null )
    {
      $tmp = str_replace(':', '_', $prefix); //convert billing: to billing_
      $data = $this->getSeleniumConfig('checkout/'.$tmp.'address');
    }

    foreach( $data AS $key => $value )
    {
      if( $element = $this->byId($prefix.$key) ) {
        if( $element->displayed() ) {
          $element->value( $value );
        }
      }
    }

  }

  public function setShippingMethod( $data = null )
  {
    if( $data === null )
    {
      $data = $this->getSeleniumConfig('checkout/shipping_method');
    }

    $css = Tt_MageTest_Helper_Data::getPageConfig('checkout/shippingmethod', array('method' => $data['method']));

    if ( empty($css) )
    {
      $css = '#s_method_'.$data['method'];
    }

    $this->getActiveStepElement()->byCssSelector($css)->click();
  }

  public function setPaymentMethod( $data = null )
  {
    if( $data === null )
    {
      $data = $this->getSeleniumConfig('checkout/payment_method');
    }

    $css = Tt_MageTest_Helper_Data::getPageConfig('checkout/paymentmethod', array('method' => $data['method']));

    if ( empty($css) )
    {
      $css = '#p_method_'.$data['method'];
    }

    try {
      $this->getActiveStepElement()->byCssSelector($css)->click();
    } catch( Exception $e )
    {
      // TODO: Wenn es nur eine gibt vorher logisch abfangen
    }

    unset( $data['method'] );

    foreach( $data AS $key => $value )
    {
      try {
        if( $element = $this->byId($key) ) {
          $element->value( $value );
        }
      } catch ( \PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e ) {
        // Do nothing here
      }
    }
  }

  public function acceptAgreements()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('checkout/agreements');

    if ( empty($css) )
    {
      $css = 'input[type=checkbox]';
    }

    $activeStepElement = $this->getActiveStepElement();
    $checkboxes = $this->findElementsByCssSelector($css, $activeStepElement);
    foreach( $checkboxes AS $checkbox )
    {
      $checkbox->click();
    }
  }

  public function getActiveStepElement()
  {
    $activeStepName = $this->getActiveStepName();

    $css = Tt_MageTest_Helper_Data::getPageConfig('checkout/activestepelement', array('step_name' => $activeStepName));

    if ( empty($css) )
    {
      $css = '#opc-'.$activeStepName;
    }

    return $this->byCssSelector($css);
  }

  public function nextStep()
  {
    $activeStepName = $this->getActiveStepName();
    $currentLocation = (string)$this->url();


    $cssNextStepButton = Tt_MageTest_Helper_Data::getPageConfig('checkout/nextstepbutton');

    if ( empty($cssNextStepButton) )
    {
      $cssNextStepButton = '.buttons-set button';
    }

    $this->getActiveStepElement()->byCssSelector($cssNextStepButton)->click();

    $this->waitUntil(function ( ) use ( $activeStepName, $currentLocation ) {
      try {
        if ( $this->getActiveStepName() != $activeStepName ||
            (string)$this->url() != $currentLocation ) {
          return true;
        }
      } catch( Exception $e ) {
        return true;
      }
      return null;
    }, 60000);

    sleep(0.5); // Rendering Time
  }

}