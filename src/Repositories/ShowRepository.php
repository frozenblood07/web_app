<?php
/**
 * File: ShowRepository.php
 * User: karan.tuteja26@gmail.com
 * Description: Contains for the db calls for all show modules
 */

namespace Ticket\Repositories;


class ShowRepository
{
	private $client;

	public function __construct(\Predis\Client $client) {
		$this->client = $client;
	}

    /**
     * fetch the showID for a particular date
     * @param $date
     * @return array
     */
	private function getShowIDsByDate($date) {
		$redisKey = REDIS_DATE_PREFIX.$date;
		$showIDs = $this->client->smembers($redisKey);
		return $showIDs;
	}

    /**
     * fetches the showData by ID
     * @param $arrShowIDs
     * @return array|\Predis\Pipeline\Pipeline
     */
	private function getShowDatabyID($arrShowIDs) {
		// Executes a transaction inside the callable block:
		$showData = $this->client->pipeline(function ($tx) use ($arrShowIDs) {
			foreach ($arrShowIDs as $showID) {
				$tx->hgetall(REDIS_SHOW_PREFIX.$showID);
			}
		});

		return array_filter($showData);
	}

    /**
     * Gets the order details for the show ids provided
     * @param $arrShowIDs
     * @return array
     */
	private function getOrderDetailsByShow($arrShowIDs) {

		$orderInfo = $this->client->pipeline(function ($tx) use ($arrShowIDs) {
            foreach ($arrShowIDs as $showID) {
                $tx->smembers(REDIS_ORDER_PREFIX.$showID);
            }
        });

		$orderData = array();

		if(empty($orderInfo)) {
			return $orderData;
		}

		foreach ($orderInfo as $orderDataKey) {

		    if(empty($orderDataKey)) continue;

		    foreach ($orderDataKey as $order) {
                $order = json_decode($order, true);

                $showID = $order['showID'];

                if (!is_array($orderData[$showID])) {
                    $orderData[$showID] = array();
                }

                array_push($orderData[$showID], $order);
            }
		}

		return $orderData;
	}

    /**
     * Returns an array of objects of show models
     * @param $date
     * @return array
     */
	public function getShowInventoryListByDate($date) {
		$showIDs = $this->getShowIDsByDate($date);

        $showObjArr = $this->getShowObjArr($showIDs);

        return $showObjArr;
	}

    /**
     * returns show object of an showID
     * @param $showID
     * @return mixed
     */
	public function getShowData($showID) {

        $showObjArr = $this->getShowObjArr(array($showID));
        //var_dump($showObjArr);die();

        return $showObjArr[0];
    }

    /**
     * returns an array of objects for the showIDs provided
     * @param $showIDs
     * @return array
     */
    private function getShowObjArr($showIDs) {
        $arrShowData = $this->getShowDatabyID($showIDs);

        if (empty($arrShowData)) {
            return array();
        }
        $orderData = $this->getOrderDetailsByShow($showIDs);

        $showObjArr = Array();
        foreach ($arrShowData as $showData) {

            $showObj = new \Ticket\Models\Show($showData['showID'],$showData['name'],$showData['date'],$showData['genre']);
            $orderObjArr = array();
            if( is_array($orderData[$showData['showID']]) && !empty($orderData[$showData['showID']]) ) {

                foreach ($orderData[$showData['showID']] as $order) {
                    $orderObj = new \Ticket\Models\Order($order['orderID'],$order['showID'],$order['tickets'],$order['price'],$order['date']);
                    array_push($orderObjArr,$orderObj);
                }

            }
            $showObj->setOrderTransactions($orderObjArr);
            array_push($showObjArr,$showObj);
        }

        return $showObjArr;
    }

    /**
     * books tickets for show
     * @param $showID
     * @param $orderParams
     * @return array
     */
    public function generateOrderForShow($showID,$orderParams) {

        $showObj = $this->getShowData($showID);
        $validateResp = $showObj->validateOrder($orderParams);

        if(!$validateResp['status']) {
            return $validateResp;
        }

        $generateOrderParams = array('showID' => $showID, 'tickets' => $orderParams['quantity'],'date' => $orderParams['date'], 'price' => $showObj->getShowPrice($orderParams['date']));

        return $this->generateOrder($generateOrderParams);
    }

    /**
     * gets the idea of latest generated order
     * @return string|int
     */
    private function getLatestOrderID() {
	    return $this->client->get(LATEST_ORDER_ID) ?  $this->client->get(LATEST_ORDER_ID) : 1;
    }

    /**
     * increments the latest order id
     * @return int
     */
    private function setLatestOrderID() {
	    return $this->client->incr(LATEST_ORDER_ID);
    }

    /**
     * inserts the order into redis and updates the latest order ids
     * @param $params
     * @return array
     */
    private function generateOrder($params) {

	    $params['orderID'] = $this->getLatestOrderID();

	    $this->client->sadd(REDIS_ORDER_PREFIX.$params['showID'], json_encode($params));

	    $this->setLatestOrderID();

	    return array('status' => true,'orderID' => $params['orderID'],'msg' => 'Booked successfully. Order ID: '.$params['orderID']);

    }

}