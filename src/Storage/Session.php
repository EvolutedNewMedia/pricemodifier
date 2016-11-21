<?php
namespace Evoluted\PriceModifier\Storage;

use Evoluted\PriceModifier\Interfaces\StorageInterface;
use Evoluted\PriceModifier\Storage\Runtime;


/**
 * The Session storage handler for PriceModifier is based on one by moltin and
 * allows for storing the basket items into a session.
 *
 * @package 	PriceModifier
 * @author 		Rick Mills <rick@evoluted.net>
 * @author		Evoluted New Media <developers@evoluted.net>
 * @license     http://mit-license.org/
 *
 * @link		https://github.com/evolutednewmedia/pricemodifier
 *
 */
class Session extends Runtime implements StorageInterface
{
	protected $sessionName = 'basket';

	/**
     * The Session store constructor
     */
    public function restore()
    {
        session_id() or session_start();
        if (isset($_SESSION[$this->sessionName])) static::$basket = unserialize($_SESSION[$this->sessionName]);
    }

    /**
     * The session store destructor.
     */
    public function __destruct()
    {
        $_SESSION[$this->sessionName] = serialize(static::$basket);
    }

	/**
	 * Sets the id of the basket into the session and updates the session name
	 * to reflect the change.
	 *
	 * @param mixed $id basket id to use
	 *
	 * @return void
	 */
	public function setBasketId($id) {
		if (! empty($_SESSION[$this->sessionName])) {
			$_SESSION['basket.' . $id] = $_SESSION[$this->sessionName];
		}

		$this->sessionName = 'basket.' . $id;

	}
}
