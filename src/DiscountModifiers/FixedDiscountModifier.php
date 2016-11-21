<?php
namespace Evoluted\PriceModifier\DiscountModifiers;

use Evoluted\PriceModifier\Interfaces\DiscountModifierInterface;
use Evoluted\PriceModifier\Interfaces\BasketInterface;

/**
 * A fixed discount handler for the PriceModifer package.
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */
class FixedDiscountModifier implements DiscountModifierInterface
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

	/**
	 * Applies the discount to the loaded basket.
	 *
	 * @return BasketInterface Returns the modified basket
	 */
	public function applyDiscount()
	{
		if ($this->params['applyToItems']) {
			foreach ($this->basket->items() as $basketItem) {

				if (empty($this->basket->validDiscounts[$this->id]) || in_array($basketItem->reference, $this->basket->validDiscounts[$this->id])) {
					$discount = $this->params['amount'];
					$this->basket->discount += $discount;
				}

			}
		} else {
			$this->basket->discount += $this->params['amount'];
		}

		return $this->basket;
	}

}
