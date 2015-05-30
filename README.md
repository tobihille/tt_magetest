# Goal

The main goal is to create a test for every single page in default magento, frontend and admin.

Another objective is to write the tests configurable so one can just create a module, reset the defaults in config.xml to fit their needs and run the tests.

Furthermore it should be easy to extend to allow it to easily cover third-party modules.

# Version

The included tests are based on Magento 1.9.1 CE with sample data but if you modify the XMLs it should work on any magento where Xtest can run.

# Requirements

Xtest from Code-x (https://github.com/code-x/Xtest)

Selenium server (http://docs.seleniumhq.org/download/)

Phpunit (included in Xtest)

Magento (of course) with sampledata (to run the included tests)

# Usage

Of course you can use Xtest as normal (see Documentation) but you can do simple Pagetests with minimal effort with this module.<br/>

For a complete overwiew have a look at: [config.xml](https://raw.githubusercontent.com/tobihille/tt_magetest/master/app/code/community/Tt/MageTest/etc/config.xml "config.xml")<br/>
The basics are covered below:<br/>

## Test multiple Frontend-Pages by url

place just an other URL-Element into /config/default/xtest/frontendtest/urls following this schema: <pre>&lt;url_1&gt;<br/>
  &lt;url&gt;/about-magento-demo-store&lt;/url&gt;<br/>
  &lt;rendername&gt;FE_Footer_AboutPage&lt;/rendername&gt;<br/>
  &lt;assert&gt;<br/>
  &lt;a1&gt;&lt;![CDATA[class=" cms-page-view cms-about-magento-demo-store"]]&gt;&lt;/a1&gt;<br/>
  &lt;/assert&gt;<br/>
&lt;/url_1&gt;</pre>

## Test multiple Adminhtml-Pages by url

place just an other URL-Element into /config/default/xtest/admintest/urls following this schema: <pre>&lt;url_1&gt;<br/>
  &lt;url&gt;/admin/sales_order/index&lt;/url&gt;<br/>
  &lt;rendername&gt;ADM_Sales_OrderIndex&lt;/rendername&gt;<br/>
  &lt;assert&gt;<br/>
    &lt;a1&gt;&lt;![CDATA[&lt;a href="#" name="real_order_id" title="asc" class="not-sort"&gt;]]&gt;&lt;/a1&gt;<br/>
  &lt;/assert&gt;<br/>
&lt;/url_1&gt;</pre>
You can configure the admin-user by changing /config/default/xtest/admintest/user

## Test multiple customer account-Pages

place just an other URL-Element into /config/default/xtest/customertest/urls following this schema: <pre>&lt;url_1&gt;<br/>
  &lt;url&gt;/customer/account/index&lt;/url&gt;<br/>
  &lt;assert&gt;<br/>
  &lt;a1&gt;&lt;![CDATA[&lt;body class=" customer-account-index customer-account"&gt;]]&gt;&lt;/a1&gt;<br/>
  &lt;/assert&gt;<br/>
  &lt;rendername&gt;FE_Customer_Dashboard&lt;/rendername&gt;<br/>
&lt;/url_1&gt;</pre>
You can configure the customer accounts by changing /config/default/xtest/customertest/customers<br/>
Please note that all URLs will be called for all accounts.

## Test multiple product pages

place just an other URL-Element into /config/default/xtest/producttest/products following this schema: <pre>&lt;sku_1&gt;<br/>
  &lt;sku&gt;hbm010&lt;/sku&gt;<br/>
  &lt;assert&gt;<br/>
  &lt;a1&gt;&lt;![CDATA[&lt;div class="price-box"&gt;]]&gt;&lt;/a1&gt;<br/>
  &lt;a2&gt;&lt;![CDATA[&lt;span class="regular-price" id="product-price-]]&gt;&lt;/a2&gt;<br/>
  &lt;a3&gt;&lt;![CDATA[&lt;span class="price"&gt;]]&gt;&lt;/a3&gt;<br/>
  &lt;/assert&gt;<br/>
  &lt;rendername&gt;FE_Product_hbm010&lt;/rendername&gt;<br/>
&lt;/sku_1&gt;</pre>

## Test multiple category pages

place just an other URL-Element into /config/default/xtest/catalogtest/categories following this schema: <pre>&lt;cat_1&gt;<br/>
  &lt;catid&gt;22&lt;/catid&gt;<br/>
  &lt;assert&gt;<br/>
  &lt;a1&gt;&lt;![CDATA[&lt;div class="sort-by"&gt;]]&gt;&lt;/a1&gt;<br/>
  &lt;a2&gt;&lt;![CDATA[&lt;div class="product-info"&gt;]]&gt;&lt;/a2&gt;<br/>
  &lt;a3&gt;&lt;![CDATA[&lt;div class="price-box"&gt;]]&gt;&lt;/a3&gt;<br/>
  &lt;/assert&gt;<br/>
&lt;/cat_1&gt;</pre>


## Test a page 'interactive' (click an an element and then assert)

place just an other URL-Element into /config/default/xtest/catalogtest/categories following this schema: <pre>&lt;url_1&gt;<br/>
  &lt;url&gt;/&lt;/url&gt;<br/>
  &lt;rendername&gt;FE_CustomerAccount&lt;/rendername&gt;<br/>
  &lt;clickon&gt;&lt;![CDATA[#header div.skip-links &gt; div.account-cart-wrapper &gt; a]]&gt;&lt;/clickon&gt;<br/>
  &lt;assert&gt;<br/>
    &lt;a1&gt;&lt;![CDATA[&lt;a class="skip-link skip-account skip-active" data-target-element="#header-account" href="[[unsecure_base_url]]customer/account/"&gt;]]&gt;&lt;/a1&gt;<br/>
    &lt;a2&gt;&lt;![CDATA[&lt;div class="header-minicart"&gt;]]&gt;&lt;/a2&gt;<br/>
  &lt;/assert&gt;<br/>
&lt;/url_1&gt;</pre>

## Test the checkout

Just have a look at config.xml at /config/default/xtest/checkouttest as well as xtext.xml at /config/default/xtest/selenium/checkout

## Configuring Test to fit for customized templates

Just have a look at config.xml at /config/default/xtest/pageconfig, you can configure every aspect covered in this module.
