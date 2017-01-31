<?php
namespace Evoluted\PriceModifier\DiscountModifiers;

use Evoluted\PriceModifier\Interfaces\DiscountModifierInterface;
use Evoluted\PriceModifier\DiscountModifiers\BaseDiscountModifier;

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
class FixedDiscountModifier extends BaseDiscountModifier implements DiscountModifierInterface
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
					$this->_applyDiscount($basketItem->taxRate());
				}

			}
		} else {
			$this->_applyDiscount($this->basket->getBasketTaxRate());
		}

		return $this->basket;
	}

	/**
	 * Applies a discount to either basket item or entire basket
	 * @param  double $taxRate The tax rate of the basket item or average of the basket
	 */
	protected function _applyDiscount($taxRate = null)
	{
		if ($this->params['applyDiscountsAfterTax'] == true) {
			extract($this->__getDiscount($taxRate));
		} else {
			extract($this->__getDiscount(0));
		}

		// Normalise the discount to remove half penies, extracts back out into
		// $discountSubtotal and $discountTax
		extract($this->_normaliseDiscount($discountSubtotal, $discountTax));

		$this->basket->discountBreakdown[$this->id]['amount'] = $discountSubtotal;
		$this->basket->discountBreakdown[$this->id]['tax'] = $discountTax;

		$this->basket->discount += $discountSubtotal;
		$this->basket->discountTax += $discountTax;
	}

	/**
	 * Calculates the discount based on a given tax rate
	 * @param  double $taxRate Tax rate used to split fixed discount across subtotal and tax
	 * @return array  An array of the discount off the subtotal and tax
	 */
	private function __getDiscount($taxRate)
	{
		$discountTax = number_format($this->params['amount'] - ($this->params['amount'] / (1 + ($taxRate / 100))), 2);

		return [
			'discountSubtotal' => $this->params['amount'] - $discountTax,
			'discountTax' => $discountTax
		];
	}

}
