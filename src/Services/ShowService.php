<?php
/**
 * File: ShowService.php
 * User: karan.tuteja26@gmail.com
 * Description: Aggregator for show modules
 */

namespace Ticket\Services;

use Ticket\Models\Show;
use Ticket\Repositories\ShowRepository;

class ShowService
{
    private $showRepository;
    
    public function __construct(ShowRepository $showRepository) {
        $this->showRepository = $showRepository;
    }

    /**
     * Inventory information for a date
     * @param $date
     * @return array
     */
    public function getInventoryListForDate($date) {
        //getting an array containing show model obj
        $showObjArr = $this->showRepository->getShowInventoryListByDate($date);

        //formatting data for rendering
        $data = array();
        $pos = 0;
        $genreKey = array();

        foreach ($showObjArr as $show) {
            $showData = $show->getShowData($date);
            if(! array_key_exists($showData['genre'],$genreKey) ) {
                $genreKey[$showData['genre']] = $pos;
                $data[$pos]['genre'] = $showData['genre'];
                $data[$pos]['inventory'] = array();
                $pos++;
            }

            array_push($data[$genreKey[$showData['genre']]]['inventory'] , $showData);
        }

        return $data;
    }

    /**
     * returns data of show for booking
     * @param $showID
     * @param $date
     * @return mixed
     */
    public function getShowDataForBooking($showID,$date){

        $showObj = $this->showRepository->getShowData($showID);
        $data = array();
        if(! empty($showObj)) {
            $data = $showObj->getShowData($date);
        }


        return $data;

    }

    /**
     * booking a ticket
     * @param $showID
     * @param $orderParams
     * @return array|string
     */
    public function generateOrderForBooking($showID,$orderParams) {
        $data = $this->showRepository->generateOrderForShow($showID,$orderParams);
        return $data;
    }
}