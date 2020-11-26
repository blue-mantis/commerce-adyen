<p align="center"><img src="./src/icon.svg" width="100" height="100" alt="Adyen for Craft Commerce icon"></p>

<h1 align="center">Adyen for Craft Commerce</h1>

This plugin provides an [Adyen](https://www.adyen.com/) integration for [Craft Commerce](https://craftcms.com/commerce).

<h3>Note - this plugin is incomplete</h3>

<p>The client this was being written for opted to go with another payment provider, and so this has never seen any real 
world use. Use at your own risk.</p>

<h5>What it does</h5>
<ul>
   <li>Supports <a href="https://docs.adyen.com/checkout/classic-integrations/hosted-payment-pages">Hosted Payment Pages</a></li>
   <li>Supports Authorizations, Manual Captures, and Refunds</li>
   <li>Supports Commerce 3 - should work with Commerce 2 but has not been tested</li>
</ul>

<h5>What it <b>doesn't</b> do</h5>
<ul>
    <li>Support any method other than Hosted Payment Pages. Also note that HPP is now marked as a "classic" integration, 
    so it's not using the latest version of the Adyen API</li>
    <li>Take payments automatically - it simply authorizes them, pending you going into Commerce and clicking to
    capture the funds</li>
</ul>
