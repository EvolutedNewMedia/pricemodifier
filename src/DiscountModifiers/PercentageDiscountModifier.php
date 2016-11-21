<?php
namespace Evoluted\PriceModifier\DiscountModifiers;

use Evoluted\PriceModifier\Interfaces\DiscountModifierInterface;
use Evoluted\PriceModifier\Interfaces\BasketInterface;

/**
 * A percentage discount handler for the PriceModifer package.
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */
class PercentageDiscountModifier implements DiscountModifierInterface
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
		'percent' => 0,
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
					$discount = $this->__getDiscount($basketItem->total);
					$this->basket->discount += $discount;
				}

			}
		} else {
			$this->basket->discount += $this->__getDiscount($this->basket->getBasketTotal());
		}

		return $this->basket;
	}

	/**
	 * Works out the percentage discount from the stored params.
	 *
	 * @param  double $amount the amount to apply the discount to
	 *
	 * @return double the discount amount
	 */
	private function __getDiscount($amount)
	{
		return ($this->params['percent'] / 100) * $amount;
	}

}
