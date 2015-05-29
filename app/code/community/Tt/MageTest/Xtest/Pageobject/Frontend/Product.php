<?php

class Tt_MageTest_Xtest_Pageobject_Frontend_Product extends Codex_Xtest_Xtest_Pageobject_Frontend_Product
{

  public function setQty( $qty )
  {
    $eQty = $this->getAddToCartForm()->byId('qty');
    $eQty->clear();
    $eQty->value( $qty);
    return $this;
  }

}