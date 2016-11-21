<?php
namespace Evoluted\PriceModifier\Interfaces;

use Evoluted\PriceModifier\Basket\BasketItem;

/**
 * Generic storage interface for handling baskets. Based on the generic moltin
 * storage interface pattern.
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */
interface StorageInterface
{
	/**
	     * Add or update an item in the basket
	     *
	     * @param  BasketItem   $item The item to insert or update
	     * @return void
	     */
	    public function insertUpdate(BasketItem $item);
	    /**
	     * Retrieve the basket data
	     *
	     * @return array
	     */
	    public function &data($asArray = false);
	    /**
	     * Check if the item exists in the basket
	     *
	     * @param  mixed  $id
	     * @return boolean
	     */
	    public function has($id);
	    /**
	     * Get a single basket item by id
	     *
	     * @param  mixed $id The item id
	     * @return Item  The item class
	     */
	    public function item($id);
	    /**
	     * Remove an item from the basket
	     *
	     * @param  mixed $id
	     * @return void
	     */
	    public function remove($id);
	    /**
	     * Destroy the basket
	     *
	     * @return void
	     */
	    public function destroy();

	    /**
	     * Set the basket identifier
	     *
	     * @param string $id
	     */
	    public function setId($id);

	    /**
	     * Return the current basket identifier
	     *
	     * @return void
	     */
	    public function getId();

}
