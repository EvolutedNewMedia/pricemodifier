<?php
namespace Evoluted\PriceModifier\Interfaces;

use Evoluted\PriceModifier\Interfaces\BasketInterface;

interface DiscountModifierInterface
{
	/**
	 * Construct the discount handler
	 *
	 * @param mixed $id A unique identifier for this discount
	 * @param array $discountData Any data needed for the discount
	 * @param BasketInterface $basket The current basket to modify
	 */
	public function __construct($id, $discountData, BasketInterface $basket);

	/**
	 * Applies the discount to the loaded basket.
	 *
	 * @return BasketInterface Returns the modified basket
	 */
	public function applyDiscount();


}
