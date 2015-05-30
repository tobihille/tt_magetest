<?php

class Tt_MageTest_Xtest_Pageobject_Frontend_Cart extends Codex_Xtest_Xtest_Pageobject_Frontend_Cart
{

  public function getShoppingCartTable()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('cart/shoppingcarttable');
    if ( empty($css) )
    {
      $css = '#shopping-cart-table';
    }

    return $this->byCssSelector($css);
  }

  public function getCartForm()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('cart/cartform');
    if ( empty($css) )
    {
      $css = 'div.cart form';
    }

    return $this->byCssSelector($css);
  }

  public function getCouponForm()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('cart/couponform');
    if ( empty($css) )
    {
      $css = '#discount-coupon-form';
    }

    return $this->byCssSelector($css);
  }

  public function getItems()
  {
    $cssCartItemsRows = Tt_MageTest_Helper_Data::getPageConfig('cart/cartitemsrows');
    if ( empty($cssCartItemsRows) )
    {
      $cssCartItemsRows = 'tr';
    }

    $cssProductPrice = Tt_MageTest_Helper_Data::getPageConfig('cart/productprice');
    if ( empty($cssProductPrice) )
    {
      $cssProductPrice = '.product-cart-price .price';
    }

    $cssRowTotal = Tt_MageTest_Helper_Data::getPageConfig('cart/rowtotal');
    if ( empty($cssRowTotal) )
    {
      $cssRowTotal = '.product-cart-total .price';
    }

    $cssProductName = Tt_MageTest_Helper_Data::getPageConfig('cart/productname');
    if ( empty($cssProductName) )
    {
      $cssProductName = '.product-name';
    }

    $result = array();

    $cartTable = $this->getShoppingCartTable();
    $trList = $this->findElementsByCssSelector( $cssCartItemsRows, $cartTable );
    foreach( $trList AS $tr )
    {
      /** @var PHPUnit_Extensions_Selenium2TestCase_Element $tr */

      if( $item_id = $this->getItemId( $tr ) )
      {
        $result[ $item_id ] = array(
            'tr' => $tr,
            'product_price' => $tr->byCssSelector($cssProductPrice)->text(),
            'row_total' => $tr->byCssSelector($cssRowTotal)->text(),
            'name' => $tr->byCssSelector($cssProductName),
          //'qty' => $tr->byName('cart['.$item_id.'][qty]')->value(), // TODO
        );
      }

    }

    return $result;
  }

  public function setQty( $item_id, $qty )
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('cart/qtyinput', array('item_id' => $item_id));
    if ( empty($css) )
    {
      $css = 'input.input-text.qty[name="cart['.$item_id.'][qty]';
    }

    $qty = $this->byCssSelector($css);
    $qty->clear(); //if we SET, we should clear before, else we APPEND
    $qty->value( $qty );
  }

  public function getGrandTotal()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('cart/grandtotal');
    if ( empty($css) )
    {
      $css = '.shopping-cart-totals-table td .price';
    }

    $prices = $this->byCssSelector($css);
    $grand_total = end( $prices ); // Last Element is Grand-Total
    return $grand_total->text();
  }

  public function clickProceedCheckout()
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('cart/proceedcheckoutbtn');
    if ( empty($css) )
    {
      $css = '.btn-proceed-checkout';
    }

    $this->byCssSelector($css)->click();
  }

  public function setCouponCode( $code )
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('cart/couponcode');
    if ( empty($css) )
    {
      $css = '#coupon_code';
    }

    $this->getCouponForm()->byCssSelector($css)->value( $code );
  }

  public function getCouponCode( $code )
  {
    $css = Tt_MageTest_Helper_Data::getPageConfig('cart/couponcode');
    if ( empty($css) )
    {
      $css = '#coupon_code';
    }

    return $this->getCouponForm()->byCssSelector($css)->value();
  }

  /**
   * Extracts Item-Id from delete cart-item url
   *
   * @param PHPUnit_Extensions_Selenium2TestCase_Element $tr
   * @return bool|int
   */
  protected function getItemId( PHPUnit_Extensions_Selenium2TestCase_Element $tr )
  {
    $cssItemId = Tt_MageTest_Helper_Data::getPageConfig('cart/itemidcss');
    if ( empty($cssItemId) )
    {
      $cssItemId = 'a';
    }

    $regexItemId = Tt_MageTest_Helper_Data::getPageConfig('cart/itemidregex');
    if ( empty($regexItemId) )
    {
      $regexItemId = '#checkout/cart/delete/id/([0-9]*)/#siU';
    }

    $aList = $this->findElementsByCssSelector($cssItemId, $tr);
    foreach( $aList AS $a )
    {
      /** @var PHPUnit_Extensions_Selenium2TestCase_Element $a */
      if( preg_match($regexItemId, $a->attribute('href'), $matches ) ) {
        return $matches[1];
      }
    }
    return false;
  }

}