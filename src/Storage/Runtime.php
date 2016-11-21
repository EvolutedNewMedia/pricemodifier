<?php
namespace Evoluted\PriceModifier\Storage;

use Evoluted\PriceModifier\Interfaces\StorageInterface;
use Evoluted\PriceModifier\Basket\BasketItem;

/**
 * The Runtime storage handler for PriceModifier is based on one by moltin and
 * allows for storing the basket items for the current running session only.
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */
class Runtime implements StorageInterface
{
    protected $id;
    protected static $basket = array();

    /**
     * Add or update an item in the basket
     *
     * @param  BasketItem   $item The item to insert or update
     * @return void
     */
    public function insertUpdate(BasketItem $item)
    {
        static::$basket[$this->id][$item->id] = $item;

        return $this->item($item->id);
    }

    /**
     * Retrieve the basket data
     *
     * @return array
     */
    public function &data($asArray = false)
    {
        $basket =& static::$basket[$this->id];
        if ( ! $asArray) return $basket;
        $data = $basket;
        foreach ($data as &$item) {
            $item = $item->toArray();
        }
        return $data;
    }

    /**
     * Check if the item exists in the basket
     *
     * @param  mixed  $id
     * @return boolean
     */
    public function has($id)
    {
        foreach (static::$basket[$this->id] as $item) {
            if ($item->id == $id) return true;
        }
        return false;
    }

    /**
     * Get a single basket item by id
     *
     * @param  mixed $id The item id
     * @return Item  The item class
     */
    public function item($id)
    {
        foreach (static::$basket[$this->id] as $item) {
            if ($item->id == $id) return $item;
        }
        return false;
    }

    /**
     * Remove an item from the basket
     *
     * @param  mixed $id
     * @return void
     */
    public function remove($id)
    {
        unset(static::$basket[$this->id][$id]);
    }

    /**
     * Destroy the basket
     *
     * @return void
     */
    public function destroy()
    {
        static::$basket[$this->id] = array();
    }

    /**
     * Set the basket id
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
        if ( ! array_key_exists($this->id, static::$basket)) {
            static::$basket[$this->id] = array();
        }
    }

    /**
     * Return the current basket id
     *
     * @return void
     */
    public function getId()
    {
        return $this->id;
    }
}
