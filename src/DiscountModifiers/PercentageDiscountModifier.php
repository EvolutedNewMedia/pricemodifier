<?php
namespace Evoluted\PriceModifier\DiscountModifiers;

use Evoluted\PriceModifier\Interfaces\DiscountModifierInterface;
use Evoluted\PriceModifier\DiscountModifiers\BaseDiscountModifier;

/**
 * A percentage discount handler for the PriceModifer package.
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
class PercentageDiscountModifier extends BaseDiscountModifier implements DiscountModifierInterface
{
	/**
	 * Applies the discount to the loaded basket.
	 *
	 * @return BasketInterface Returns the modified basket
	 */
	public function applyDiscount()
	{
		if ($this->params['applyToItems']) {
			foreach ($this->basket->items() as $basketItem) {

				if ($this->basket->validDiscount($this->id, $basketItem)) {
					if ($this->params['applyDiscountsAfterTax'] == true) {
						$discountSubtotal = $this->__getDiscount($basketItem->subtotal());
						$discountTax = $this->__getDiscount($basketItem->tax());
					} else {
						$discountSubtotal = $this->__getDiscount($basketItem->subtotal());
						$discountTax = 0;
					}

					// Normalise the discount to remove half penies, extracts back out into
					// $discountSubtotal and $discountTax
					extract($this->_normaliseDiscount($discountSubtotal, $discountTax));

					$this->basket->discountBreakdown[$this->id]['amount'] = $discountSubtotal;
					$this->basket->discountBreakdown[$this->id]['tax'] = $discountTax;

					$this->basket->discount += $discountSubtotal;
					$this->basket->discountTax += $discountTax;
				}

			}
		} else {
			if ($this->params['applyDiscountsAfterTax'] = true) {
				$amount = $this->__getDiscount($this->basket->getBasketTotal());
					$discountSubtotal = $this->__getDiscount($this->basket->getBasketSubtotal());
					$discountTax = $this->__getDiscount($this->basket->getBasketTax());
			} else {
				$discountSubtotal = $this->__getDiscount($this->basket->getBasketTotal());
				$discountTax = 0;
			}

			// Normalise the discount to remove half penies, extracts back out into
			// $discountSubtotal and $discountTax
			extract($this->_normaliseDiscount($discountSubtotal, $discountTax));

			$this->basket->discountBreakdown[$this->id]['amount'] = $discountSubtotal;
			$this->basket->discountBreakdown[$this->id]['tax'] = $discountTax;

			$this->basket->discount += $discountSubtotal;
			$this->basket->discountTax += $discountTax;
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
