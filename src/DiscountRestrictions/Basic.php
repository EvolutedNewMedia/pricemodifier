<?php
namespace Evoluted\PriceModifier\DiscountRestrictions;

use Evoluted\PriceModifier\Interfaces\DiscountRestrictionsInterface;
use Evoluted\PriceModifier\Interfaces\BasketInterface;

/**
 * The basic discount restriction handler allows all discount types through without
 * restricting them.
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */
class Basic implements DiscountRestrictionsInterface
{
	public $restrictions;

	/**
     * @var Evoluted\PriceModifier\Interfaces\BasketInterface
     */
	public $basket;

	/**
	 * Construct the discount restriction handler
	 *
	 * @param BasketInterface $basket Basket to use
	 */
	public function __construct(BasketInterface $basket)
	{
		$this->setBasket($basket);
	}

	/**
	 * Set the basket to use
	 *
	 * @param BasketInterface $basket Basket Handler
	 *
	 * @return Basic Returns the entire class with the handler applied
	 */
	public function setBasket($basket)
	{
		$this->basket = $basket;

		return $this;
	}

	/**
	 * Returns the basket
	 *
	 * @return BasketInterface Basket Handler
	 */
	public function basket()
	{
		return $this->basket;
	}

	/**
	 * Sets the restrictions on the basket.
	 *
	 * @return void
	 */
	public function setRestrictions()
	{
		foreach ($this->basket->discountRules as $rules) {
			$this->basket->validDiscounts[$rules['id']] = [];
		}
	}
}
