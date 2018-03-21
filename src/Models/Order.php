<?php
/**
 * File: Order.php
 * User: karan.tuteja26@gmail.com
 * Description:
 */

namespace Ticket\Models;


class Order
{
	private $orderID;
    private $showID;
    private $tickets;
    private $price;
    private $date;

    public function __construct($orderID,$showID,$tickets,$price,$date) {
        $this->orderID = $orderID;
        $this->showID = $showID;
        $this->tickets = $tickets;
        $this->price = $price;
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getOrderID()
    {
        return $this->orderID;
    }

    /**
     * @param mixed $orderID
     */
    public function setOrderID($orderID): void
    {
        $this->orderID = $orderID;
    }

    /**
     * @return mixed
     */
    public function getShowID()
    {
        return $this->showID;
    }

    /**
     * @param mixed $showID
     */
    public function setShowID($showID): void
    {
        $this->showID = $showID;
    }

    /**
     * @return mixed
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param mixed $tickets
     */
    public function setTickets($tickets): void
    {
        $this->tickets = $tickets;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

}