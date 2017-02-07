<?php
namespace Evoluted\PriceModifier\Interfaces;

/**
 * The Basket Interface
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
interface BasketInterface {

	/**
	 * Bulk populate the basket.
	 *
	 * @param array $basket The basket data to populate with (see examples)
	 *
	 * @return void
	 */
	public function populate($basket);

	/**
	 * Insert an item into the basket
	 *
	 * @param  array  $item array of item data
	 *
	 * @return BasketItem The new item object
	 */
	public function insert(array $item);

	/**
	 * Remove an item from the basket
	 *
	 * @param mixed $id Identifier of the item to remove
	 *
	 * @return void
	 */
	public function removeItem($id);

	/**
	 * Check if an item exists in the basket
	 *
	 * @param mixed  $id Identifier of the item to check
	 * @return boolean
	 */
	public function hasItem($id);

	/**
	 * Return a basket item from its id
	 *
	 * @param  mixed $id Identifier of the item to find
	 * @return BasketItem
	 */
	public function item($id);

	/**
	 * Set the storage handler
	 *
	 * @param StorageInterface $storage Storage Handler to use
	 */
	public function setStorage(StorageInterface $storage);

	/**
	 * Gets the storage handler currently in use
	 *
	 * @return StorageInterface Storage Handler
	 */
	public function getStorage();

	/**
	 * Returns the total discount on the basket
	 * @return double Discount total
	 */
	public function getDiscountTotal();

	/**
	 * Returns the basket total
	 *
	 * @return double Basket total
	 */
	public function getBasketTotal();

	/**
	 * Returns  all basket item objects
	 *
	 * @return array Returns an array of all item objects in the basket
	 */
	public function items();

	/**
	 * Returns whether a discount can be used again a particular basket item
	 *
	 * @return bool true if discount can be applied
	 */
	public function validDiscount($discountId, $basketItem);

	/**
	 * Formats the basket out as an array
	 *
	 * @return array basket data
	 */
	public function toArray();

}
