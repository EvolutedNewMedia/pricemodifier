<?php
/**
 * This example assumes you've already loaded up the price modifier package using composer. 
 * Depending on your setup, you'll want to add 'use' lines at the top of your class to load
 * all the classes you use below.
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */

// Load the price modifier, setting our required basket handler with it's storage method inside.
$priceModifier = new Evoluted\PriceModifier\PriceModifier(
	new Evoluted\PriceModifier\Basket\Basket(new Evoluted\PriceModifier\Storage\Session, '1')
);

// Create a base set of basket data. Your own basket system would normally handle this,
// or alternatively you would use your own basket class when loading the price modifier
// above. Then using that you can handle how you populate it.
$basketData = array(
	'id' => '200',
	'items' => array(
		array(
			'id' => 1,
			'reference' => 'Product.1',
			'amount' => 10,
			'quantity' => 3,
			'total' => 30,
			'tax_rate' => 0,
			// You can add any custom data to pass through. This is ideal if you're using
			// a complex custom discount rule and/or modifier as you can provide additional
			// data on each item.
			'any_custom_vars' => 'custom data' 
		),
		array(
			'id' => 2,
			'reference' => 'Product.2',
			'amount' => 4.55,
			'quantity' => 1,
			'total' => 4.55,
			'tax_rate' => 20.00
		),
		array(
			'id' => 3,
			'reference' => 'Product.3',
			'amount' => 15,
			'quantity' => 1,
			'total' => 15,
			'tax_rate' => 20.00
		)
	),
	'total' => '49.55'
);

// Populate the basked from the above data
$priceModifier->basket()->populate($basketData);

// Add a 10% percent to all items
$priceModifier->addDiscountRule([
	'id' => 1,
	'discountType' => 'percentage',
	'percent' => '10',
	'applyToItems' => true
]);

// Add a fixed discount of 1.50 (any currency) to all items
$priceModifier->addDiscountRule([
	'id' => 2,
	'discountType' => 'fixed',
	'amount' => '1.50',
	'applyToItems' => true
]);

/**
 * Written your own discount rule? You can add it to your price modifier rule list like so:
 *
 * $priceModifier->addDiscountModifier('MyDiscountModifier', 'YourNamespace\MyDiscountModifier');
 * Then you can add a rule like above, using 'MyDiscountModifier' as the discount type.
 */



// Apply the discounts
$priceModifier->applyDiscounts();

// You can pull out the total discount at any time like so:
echo '<hr />TOTAL DISCOUNT: ' . $priceModifier->getDiscountTotal();

// You can also get the entire basket contents as an array - this can be done at any time.
$basketData = $priceModifier->basket()->toArray();

// The total discount can then also be used like this:
echo '<hr />TOTAL AFTER DISCOUNT: ' . $basketData['discountedTotal'];

