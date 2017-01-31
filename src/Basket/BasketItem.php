<?php
namespace Evoluted\PriceModifier\Basket;

use Evoluted\PriceModifier\Interfaces\BasketItemInterface;

/**
 * The BasketItem class is the default basket item handler. This can be replaced
 * with your own, however its extremely unlikely you would need to in most
 * situations.
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
class BasketItem implements BasketItemInterface
{
	/**
	 * @var mixed Id of the basket item
	 */
	protected $id;

	/**
	 * @var Array The basket item data
	 */
	protected $data = [];

	/**
	 * Construct the basket item
	 *
	 * @param mixed $id The ID of the basket item (this can be a string or integer)
	 * @param array $item Array of item data
	 */
	function __construct($id, $item)
	{
		$this->id = $id;
		$this->data = $item;
	}

	/**
	 * Magic get to allow us to pull individual item data vars
	 *
	 * @param string $param name of the var to pull
	 *
	 * @return mixed returns the param value if its found, or false
	 */
	public function __get($param)
	{
		if ($param == 'id') return $this->id;

		return array_key_exists($param, $this->data) ? $this->data[$param] : null;
	}

	/**
	 * Allows setting any param value
	 *
	 * @param string $param Name of the param
	 * @param mixed $value The new value
	 */
	public function __set($param, $value)
	{
		$this->data[$param] = $value;
	}

	/**
	 * Calculates and returns the item total from the unit price and quantity
	 * @return double new total price
	 */
	public function subtotal()
	{
		if (isset($this->subtotal)) {
			return $this->subtotal;
		}
		return $this->unitPrice * $this->quantity;
	}

	public function taxRate() {
		if (isset($this->data['taxRate'])) {
			return $this->data['taxRate'];
		}
		return 0;
	}

	/**
	 * Calculates and returns the item total from the unit price and quantity
	 * @return double new total price
	 */
	public function tax()
	{
		if (isset($this->tax)) {
			return $this->tax;
		}
		return number_format($this->subtotal() * $this->taxRate() / 100, 2, '.', ',');
	}

	/**
	 * Calculates and returns the item total from the unit price and quantity
	 * @return double new total price
	 */
	public function total()
	{
		if (isset($this->total)) {
			return $this->total;
		}
		return $this->subtotal() + $this->tax();
	}

	/**
	 * Returns the item data, which is already an array
	 * @return array Item data
	 */
	public function toArray()
	{
		return $this->data;
	}
}
