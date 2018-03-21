<?php
/**
 * File: Show.php
 * User: karan.tuteja26@gmail.com
 * Description: Show Model
 */

namespace Ticket\Models;


use Ticket\Utilities\ShowUtils;

class Show
{
    private $showID;
    private $name;
    private $date;
    private $genre;
    private $orderTransactions;
    private $currentDate;
    private $price;

    public function __construct($showID,$name,$date,$genre) {
        $this->showID = $showID;
        $this->name = $name;
        $this->date = $date;
        $this->genre = $genre;
        $this->currentDate = date('Y-m-d');
        $this->price = ShowUtils::getShowPrice($this->genre);
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param mixed $genre
     */
    public function setGenre($genre): void
    {
        $this->genre = $genre;
    }

    /** 
     * @param Array(Order) $orderTransactions 
     */
    public function setOrderTransactions($orderTransactions) {
        $this->orderTransactions = $orderTransactions;
    }

    /**
     * checks if the show is eligible for ticket sales
     * @param $purchaseDate
     * @return bool
     */
    private function isEligibleForTicketSale($purchaseDate) {
        $date1=date_create($this->currentDate);
        $date2=date_create($this->date);
        $interval=date_diff($date1,$date2);

        $diff = $interval->format('%R%a');
        $diff = intval($diff);

        return ($diff <= TICKET_SELLING_START_THRESHOLD) && (strtotime($purchaseDate) >= strtotime($this->currentDate));
    }

    /**
     * returns the show of the provided date
     * @param $purchaseDate
     * @return int
     */
    private function getShowNumber($purchaseDate) {
        $date1=date_create($this->date);
        $date2=date_create($purchaseDate);

        $interval=date_diff($date1,$date2);

        $diff = $interval->format('%R%a');

        $diff = intval($diff);

        return $diff < 0 ? -1 : $diff+1;
    }

    /**
     * Returns the max possible inventory from the date provided for sale
     * @param $purchaseDate
     * @return int
     */
    private function getTotalMaxInventory($purchaseDate) {
        $showNumber = $this->getShowNumber($purchaseDate);
        $showLeft = TOTAL_SHOWS - $showNumber + 1;
        $maxAttendance = 0;
        $bigHallShows = 0;

        if($showLeft >= SMALL_HALL_SHOWS) {
            $bigHallShows = $showLeft-SMALL_HALL_SHOWS;
            $maxAttendance += $bigHallShows * BIG_HALL_ATTENDANCE;
        }

        $smallHallShows = $showLeft - $bigHallShows;
        $maxAttendance += $smallHallShows * SMALL_HALL_ATTENDANCE;

        return $maxAttendance;

    }

    /**
     * returns the max possible inventory for the date provided for sale
     * @param $purchaseDate
     * @return int
     */
    private function getMaxInventoryForDay($purchaseDate) {

        return $this->getShowNumber($purchaseDate) <= BIG_HALL_SHOWS ? BIG_HALL_DAY_TICKETS : SMALL_HALL_DAY_TICKETS;
    }

    /**
     * returns the sold inventory for the day
     * @param $purchaseDate
     * @return int
     */
    private function getSoldInventoryForDay($purchaseDate) {
        $soldTickets = 0;

        if(!empty($this->orderTransactions)) {
            foreach ($this->orderTransactions as $order) {
                $orderDate = $order->getDate();
                if(strtotime($orderDate) == strtotime($purchaseDate)) {
                    $soldTickets += $order->getTickets();
                }
            }
        }

        return $soldTickets;
    }

    /**
     * returns the total sold inventory from the date provided
     * @param $purchaseDate
     * @return int
     */
    private function getTotalSoldInventory($purchaseDate) {
        $soldTickets = 0;

        if(!empty($this->orderTransactions)) {
            foreach ($this->orderTransactions as $order) {
                $orderDate = $order->getDate();
                if(strtotime($orderDate) >= strtotime($purchaseDate)) {
                    $soldTickets += $order->getTickets();
                }
            }
        }

        return $soldTickets;
    }

    /**
     * returns the remaining inventory for the day
     * @param $purchaseDate
     * @return int
     */
    private function getRemainingInventoryForDay($purchaseDate) {
        if(!$this->isEligibleForTicketSale($purchaseDate) || $this->getShowNumber($purchaseDate) < 0) {
            return 0;
        }

        return $this->getMaxInventoryForDay($purchaseDate) - $this->getSoldInventoryForDay($purchaseDate);

    }

    /**
     * returns the total remaining inventory from the date provided
     * @param $purchaseDate
     * @return int
     */
    private function getTotalRemainingInventory($purchaseDate) {

        if(!$this->isEligibleForTicketSale($purchaseDate) || $this->getShowNumber($purchaseDate) < 0) {
            return 0;
        }

        $maxAttendance = $this->getTotalMaxInventory($purchaseDate);
        $totalSoldTickets = $this->getTotalSoldInventory($purchaseDate);

        return $maxAttendance - $totalSoldTickets;

    }

    /**
     * returns the status of ticket sale for display purposes
     * @param $purchaseDate
     * @return string
     */
    private function getTicketStatus($purchaseDate) {

        if(strtotime($purchaseDate) < strtotime($this->currentDate)) {
            return 'In the past';
        }

        if(!$this->isEligibleForTicketSale($purchaseDate)) {
            return 'Sale not started';
        }

        if($this->isEligibleForTicketSale($purchaseDate) && $this->getRemainingInventoryForDay($purchaseDate) <= 0) {
            $ticketStatus = 'Sold out for day';
        }else {
            $ticketStatus = 'Open for sale';
        }

        return $ticketStatus;
    }

    /**
     * returns the show data for display
     * @param $purchaseDate
     * @return array
     */
    public function getShowData($purchaseDate)
    {
        $showData = array();
        $showData['showID'] = intval($this->showID);
        $showData['name'] = $this->name;
        $showData['genre'] = $this->genre;
        $showData['price'] = $this->getShowPrice($purchaseDate);
        $showData['left'] = $this->getTotalRemainingInventory($purchaseDate);
        $showData['available'] = $this->getRemainingInventoryForDay($purchaseDate);
        $showData['status'] = $this->getTicketStatus($purchaseDate);
        $showData['showBook'] = $this->isEligibleForTicketSale($purchaseDate) && $this->getRemainingInventoryForDay($purchaseDate) > 0 ? 1 : 0;
        //echo json_encode($showData);die();
        return $showData;
    }

    /**
     * validates if the order request provided can be processed or not
     * @param $orderParams
     * @return array
     */
    public function validateOrder($orderParams)
    {
        $date = $orderParams['date'];
        $quantity = $orderParams['quantity'];
        $response = array('status' => false);

        if (!$this->isEligibleForTicketSale($date)) {
            $response['errorMsg'] = 'Sales not open for this date';
            return $response;
        }
        
        if ($quantity > $this->getRemainingInventoryForDay($date)) {
            $response['errorMsg'] = 'Sold out for this date';
            return $response;
        }

        return array('status' => true);
    }

    /**
     * returns the show price for the date provided
     * @param $purchaseDate
     * @return float|int
     */
    public function getShowPrice($purchaseDate) {
        $showNumber = $this->getShowNumber($purchaseDate);
        if($showNumber > FULL_PRICE_SHOWS) {
            return $this->price * REDUCE_BY_FACTOR;
        }

        return $this->price;
    }

}