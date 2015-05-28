<?php

class Tt_MageTest_Test_CmsFrontendTest extends Tt_MageTest_Helper_AbstractFrontendT
{

  /**
   *
   */
  public function testRenderHomepage()
  {
    $homepageConf = self::getCmsConfig('homepage');

    $this->doGeneralTest($homepageConf);
  }

  public function testRenderFooter()
  {
    $footerConf = self::getCmsConfig('footerlinks');

    $this->doGeneralTest($footerConf);
  }

}