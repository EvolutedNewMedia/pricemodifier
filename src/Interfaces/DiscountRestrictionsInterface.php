<?php
namespace Evoluted\PriceModifier\Interfaces;

use Evoluted\PriceModifier\Interfaces\BasketInterface;

/**
 * The discount restrictions interface
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */
interface DiscountRestrictionsInterface
{
	/**
	 * Construct the discount restriction handler
	 *
	 * @param BasketInterface $basket Basket to use
	 */
	public function __construct(BasketInterface $basket);

	/**
	 * Set the basket to use
	 *
	 * @param BasketInterface $basket Basket Handler
	 *
	 * @return Basic Returns the entire class with the handler applied
	 */
	public function setBasket($basket);

	/**
	 * Returns the basket
	 *
	 * @return BasketInterface Basket Handler
	 */
	public function basket();

	/**
	 * Sets the restrictions on the basket.
	 *
	 * @return void
	 */
	public function setRestrictions();
}
