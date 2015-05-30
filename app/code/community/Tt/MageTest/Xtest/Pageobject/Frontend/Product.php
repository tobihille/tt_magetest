<?php

class Tt_MageTest_Xtest_Pageobject_Frontend_Product extends Codex_Xtest_Xtest_Pageobject_Frontend_Product
{

  /**
   * just clear the input before we are adding another value
   * @param $qty
   * @return $this
   */
  public function setQty( $qty )
  {
    $eQty = $this->getAddToCartForm()->byId('qty');
    $eQty->clear();
    $eQty->value( $qty);
    return $this;
  }

}