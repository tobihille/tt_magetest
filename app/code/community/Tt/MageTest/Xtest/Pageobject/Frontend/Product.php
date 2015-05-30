<?php

class Tt_MageTest_Xtest_Pageobject_Frontend_Product extends Codex_Xtest_Xtest_Pageobject_Frontend_Product
{

  protected function getAddToCartForm()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('product/addtocartform');
    if ( empty($css) )
    {
      $css = '#product_addtocart_form';
    }

    $eForm = $this->byCssSelector($css);
    return $eForm;
  }


  /**
   * just clear the input before we are adding another value
   * @param $qty
   * @return $this
   */
  public function setQty( $qty )
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('product/qtyinput');
    if ( empty($css) )
    {
      $css = '#qty';
    }

    $eQty = $this->getAddToCartForm()->byCssSelector($css);
    $eQty->clear();
    $eQty->value( $qty);
    return $this;
  }

  public function pressAddToCart()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('product/addtocartbutton');
    if ( empty($css) )
    {
      $css = '.add-to-cart-buttons button';
    }

    $elements = $this->findElementsByCssSelector($css, $this->getAddToCartForm() );
    foreach( $elements AS $element )
    {
      if( $element->displayed() )
      {
        $element->click();
      }
    }
    return $this;
  }

  public function assertAddToCartMessageAppears()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('product/addtocartmessage');
    if ( empty($css) )
    {
      $css = 'ul.messages li.success-msg';
    }

    $addToCartText = $this->byCssSelector($css)->text();
    $this->assertStringEndsWith( Mage::helper('checkout')->__("%s was added to your shopping cart.",''), $addToCartText );
    return $this;
  }

  public function getProductName()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('product/productname');
    if ( empty($css) )
    {
      $css = '.product-name';
    }

    return $this->byCssSelector($css)->text();
  }

  public function executeClickOn($clickon)
  {
    if ( empty($clickon) )
    {
      return;
    }

    if ( is_string($clickon) )
    {
      $clickElements = $this->findElementsByCssSelector( $clickon );
      foreach ($clickElements as $element)
      {
        $element->click();
      }
    }

    if ( is_array($clickon) )
    {
      foreach ($clickon as $clickElement)
      {
        $clickElements = $this->findElementsByCssSelector( $clickElement );
        foreach ($clickElements as $element)
        {
          $element->click();
        }
      }
    }
  }
}