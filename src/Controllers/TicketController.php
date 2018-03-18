<?php
/**
 * File: RecipeController.php
 * User: karan.tuteja26@gmail.com
 * Description: File will contain the recipe controller functions
 */

namespace Ticket\Controllers;

use Http\Request;
use Http\Response;

//use Psr\Http\Message\ResponseInterface;
//use Psr\Http\Message\ServerRequestInterface;


class TicketController
{
    private $request;
    private $response;
    private $redisClient;

    public function __construct(Request $request, Response $response,\Predis\Client $redisClient) {
        $this->request = $request;
        $this->response = $response;
        $this->redisClient = $redisClient;
    }

    /**
     * Default controller
     */
    public function index() {
        $this->response->setContent("PHP Web Test app");
    }

    /**
     * This  controller will populate the redis db from csv file
     */
    public function populateDB() {

        $arrShows = array();

        //read the data from file
        if (($file = fopen(DATA_FILE_PATH."shows.csv", "r")) !== FALSE) {
            while(! feof($file))  {
                array_push($arrShows,fgetcsv($file));
            }

            fclose($file);
        }

        //start populating the show info
        $showID = 1;
        $dateWiseArr = array();

        foreach ($arrShows as $show) {

            $redisData = array();
            $redisData['showID'] = $showID;
            $redisData['name'] = trim($show[0]);
            $redisData['date'] = trim($show[1]);
            $redisData['genre'] = trim($show[2]);

            if(!is_array($dateWiseArr[$show[1]])) {
                $dateWiseArr[$show[1]] = array();
            }

            array_push($dateWiseArr[$show[1]],$redisData['showID']);
            $this->redisClient->hmset(REDIS_SHOW_PREFIX.$showID, $redisData);
            $showID++;

        }


        //end populating the show info

        //categorize the date wise show info
        foreach ($dateWiseArr as $date => $showIDArr) {
            //var_dump($date);var_dump($showIDArr);die();
            $this->redisClient->sadd(REDIS_DATE_PREFIX.$date,$showIDArr);
        }

        //echo json_encode($dateWiseArr);die();

        $this->response->setContent("Population of redis db done please proceed to the main app");
    }
}
