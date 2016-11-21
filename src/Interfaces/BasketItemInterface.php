<?php
namespace Evoluted\PriceModifier\Interfaces;

interface BasketItemInterface
{
	/**
	 * Construct the basket item
	 *
	 * @param mixed $id The ID of the basket item (this can be a string or integer)
	 * @param array $item Array of item data
	 */
	public function __construct($id, $item);

	/**
	 * Magic get to allow us to pull individual item data vars
	 *
	 * @param string $param name of the var to pull
	 *
	 * @return mixed returns the param value if its found, or false
	 */
	public function __get($param);

	/**
	 * Allows setting any param value
	 *
	 * @param string $param Name of the param
	 * @param mixed $value The new value
	 */
	public function __set($param, $value);

	/**
	 * Calculates and returns the item total from the unit price and quantity
	 * @return double new total price
	 */
	public function total();

	/**
	 * Returns the item data, which is already an array
	 * @return array Item data
	 */
	public function toArray();

}
