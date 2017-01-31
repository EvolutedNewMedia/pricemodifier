<?php
namespace Evoluted\PriceModifier\DiscountModifiers;

use Evoluted\PriceModifier\Interfaces\DiscountModifierInterface;
use Evoluted\PriceModifier\Interfaces\BasketInterface;

/**
 * A fixed discount handler for the PriceModifer package.
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author 		Sam Biggins <sam@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */
abstract class BaseDiscountModifier implements DiscountModifierInterface
{
	/**
     * @var Evoluted\PriceModifier\Interfaces\BasketInterface
     */
	protected $basket;

	/**
	 * @var Mixed A unique identifier for the discount. This can be a string or a number.
	 */
	protected $id;

	/**
	 * @var Array This contains any settings/data needed for this handler.
	 */
	protected $params = [
		'amount' => 0,
		'applyToItems' => false // set to true to individually apply the discount to items instead of the basket total
	];

	/**
	 * Construct the discount handler
	 *
	 * @param mixed $id A unique identifier for this discount
	 * @param array $discountData Any data needed for the discount
	 * @param BasketInterface $basket The current basket to modify
	 */
	public function __construct($id, $discountData, BasketInterface $basket)
	{
		$this->id = $id;
		$this->params = array_merge($this->params, $discountData);
		$this->basket = $basket;
	}

	protected function _normaliseDiscount($discountSubtotal, $discountTax)
	{

		// Normalize the discount as we need to split between tax and subtotal with no half penny's
		// otherwise both sides may get rounded up adding extra pence to the total
		$totalDiscount = $discountSubtotal + $discountTax;
		$totalDiscount = number_format($totalDiscount, 2, '.', '');
		$discountSubtotal = number_format($discountSubtotal, 2, '.', '');
		// Whatever is left it tax of the discount
		$discountTax = $totalDiscount - $discountSubtotal;

		return [
			'discountSubtotal' => $discountSubtotal,
			'discountTax' => $discountTax
		];
	}
}
