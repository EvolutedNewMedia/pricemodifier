<?php
namespace Evoluted\PriceModifier\Basket;

use Evoluted\PriceModifier\Interfaces\BasketInterface;
use Evoluted\PriceModifier\Interfaces\StorageInterface;
use Evoluted\PriceModifier\Basket\BasketItem;

/**
 * This is the default basket handler for the PriceModifier package. You can
 * optionally write your own, however this one should be sufficient for most
 * applications.
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
class Basket implements BasketInterface
{
	/**
	 * @var Evoluted\PriceModifier\Interfaces\StorageInterface
	 */
	protected $storage;

	/**
	 * @var A unique id for the basket
	 */
	protected $basketId;

	/**
	 * @var  Stores the discount rules to be applied to the basket
	 */
	public $discountRules = [];

	/**
	 * @var double The total discount applied to the basket
	 */
	public $discount = 0;

	/**
	 * @var double The total tax discount applied to the basket
	 */
	public $discountTax = 0;

	/**
	 * @var array A breakdown of the amount of discount from each discount
	 */
	public $discountBreakdown = [];

	/**
	 * Construct the basket
	 *
	 * @param StorageInterface $storage  The storage interface to use
	 * @param mixed $basketId A unique identifier for the basket (can be an integer or a string)
	 */
	public function __construct(StorageInterface $storage, $basketId)
	{
		$this->basketId = $basketId;
		$this->setStorage($storage);
		$this->storage->setId($basketId);
	}

	/**
	 * Bulk populate the basket.
	 *
	 * @param array $basket The basket data to populate with (see examples)
	 *
	 * @return void
	 */
	public function populate($basket)
	{
		if (! empty($basket['items'])) {
			foreach ($basket['items'] as $item) {

				$this->insert($item);
			}
		}
	}

	/**
	 * Insert an item into the basket
	 *
	 * @param  array  $item array of item data
	 *
	 * @return BasketItem The new item object
	 */
	public function insert(array $item)
	{
		if (array_key_exists('id', $item)) {
			$itemId = $item['id'];
			unset($item['id']);
		} else {
			$itemId = uniqid();
		}

		$item = new BasketItem($itemId, $item);

		return $this->getStorage()->insertUpdate($item);
	}

	/**
	 * Remove an item from the basket
	 *
	 * @param mixed $id Identifier of the item to remove
	 *
	 * @return void
	 */
	public function removeItem($id)
	{
		$this->storage->remove($id);
	}

	/**
	 * Check if an item exists in the basket
	 *
	 * @param mixed  $id Identifier of the item to check
	 * @return boolean
	 */
	public function hasItem($id)
	{
		return $this->storage->has($id);
	}

	/**
	 * Return a basket item from its id
	 *
	 * @param  mixed $id Identifier of the item to find
	 * @return BasketItem
	 */
	public function item($id)
	{
		return $this->storage->item($id);
	}

	/**
	 * Set the storage handler
	 *
	 * @param StorageInterface $storage Storage Handler to use
	 */
	public function setStorage(StorageInterface $storage)
	{
		$this->storage = $storage;
		return $this;
	}

	/**
	 * Gets the storage handler currently in use
	 *
	 * @return StorageInterface Storage Handler
	 */
	public function getStorage()
	{
		return $this->storage;
	}

	/**
	 * Returns the total discount on the basket
	 * @return double Discount total
	 */
	public function getDiscountTotal()
	{
		return $this->discount;
	}

	/**
	 * Returns the basket tax
	 *
	 * @return double Basket total
	 */
	public function getBasketTax()
	{
		$tax = 0;
		foreach($this->items() as $item) {
			$tax += $item->tax();
		}

		return $tax;
	}

	/**
	 * Returns the basket total
	 *
	 * @return double Basket total
	 */
	public function getBasketTotal()
	{
		$total = 0;
		foreach($this->items() as $item) {
			$total += $item->total();
		}

		return $total;
	}

	/**
	 * Returns the basket subtotal
	 *
	 * @return double Basket subtotal
	 */
	public function getBasketSubtotal()
	{
		$subtotal = 0;
		foreach($this->items() as $item) {
			$subtotal += $item->subtotal();
		}

		return $subtotal;
	}

	/**
	 * Returns the average tax rate of the basket
	 * This can be useful when splitting a fixed price discount across tax and subtotal
	 *
	 * @return double Basket tax rate
	 */
	public function getBasketTaxRate()
	{
		return number_format($this->getBasketTax() / $this->getBasketSubtotal() * 100, 2);
	}

	/**
	 * Returns  all basket item objects
	 *
	 * @return array Returns an array of all item objects in the basket
	 */
	public function items()
	{
		return $this->getStorage()->data();
	}

	/**
	 * Returns whether a discount can be used again a particular basket item
	 *
	 * @return bool true if discount can be applied
	 */
	public function validDiscount($discountId, $basketItem) {
		if (empty($this->validDiscounts[$discountId]) || in_array($basketItem->reference, $this->validDiscounts[$discountId])) {
			return true;
		}
		return false;
	}

	/**
	 * Formats the basket out as an array
	 *
	 * @return array basket data
	 */
	public function toArray()
	{
		return [
			'id' => $this->basketId,
			'items' => $this->getStorage()->data(true),
			'subtotal' => $this->getBasketTotal(),
			'discount' => $this->discount,
			'discountTax' => $this->discountTax,
			'discountBreakdown' => $this->discountBreakdown,
			'discountedTotal' => $this->getBasketTotal() - $this->discount - $this->discountTax
		];
	}

}
