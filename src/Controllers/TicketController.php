<?php
/**
 * File: TicketController.php
 * User: karan.tuteja26@gmail.com
 * Description: File will contain the ticket controller action
 */

namespace Ticket\Controllers;

use Http\Request;
use Http\Response;
use Ticket\Services\ShowService;
use Ticket\Template\Renderer;
use Ticket\Utilities\ResponseFormatter;
use Ticket\Utilities\Validator;

class TicketController
{
    private $request;
    private $response;
    private $showService;
    private $renderer;
    private $validator;
    private $responseFormatter;
    private $redisClient;


    public function __construct(Request $request, Response $response,\Predis\Client $redisClient, ShowService $showService, Renderer $renderer, Validator $validator, ResponseFormatter $responseFormatter ) {
        $this->request = $request;
        $this->response = $response;
        $this->showService = $showService;
        $this->renderer = $renderer;
        $this->validator = $validator;
        $this->responseFormatter = $responseFormatter;
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
            if(!$show) break;

            $redisData = array();
            $redisData['showID'] = $showID;
            $redisData['name'] = trim($show[0]);
            $redisData['date'] = trim($show[1]);
            $redisData['genre'] = trim($show[2]);

            for($i=0;$i<TOTAL_SHOWS;$i++) {

                if($i == 0){
                    $date =$show[1];
                }else {

                    $new_date = new \DateTime($date);
                    $new_date->modify('+1 day');
                    $date = $new_date->format('Y-m-d');
                }

                if (!is_array($dateWiseArr[$date])) {
                    $dateWiseArr[$date] = array();
                }

                array_push($dateWiseArr[$date], $redisData['showID']);
            }

            $this->redisClient->hmset(REDIS_SHOW_PREFIX.$showID, $redisData);
            $showID++;

        }


        //end populating the show info

        //categorize the date wise show info
        foreach ($dateWiseArr as $date => $showIDArr) {

            $this->redisClient->sadd(REDIS_DATE_PREFIX.$date,$showIDArr);
        }

        //set the latest order id
        $this->redisClient->set(LATEST_ORDER_ID,1);


        $this->response->setContent("Population of redis db done please proceed to the main app");
    }

    /**
     * Inventory list view
     */
    public function inventoryList() {

        $viewDate = $this->request->getParameter('date', date('Y-m-d'));

        $inventoryList = $this->showService->getInventoryListForDate($viewDate);

        $data = [
            "inventoryList" => $inventoryList,
            "date" => $viewDate
        ];

        $html = $this->renderer->render('List', $data);

        $this->response->setContent($html);
    }


    /**
     * booking information
     * @param $pathParams
     */
    public function book($pathParams)
    {
        $showID = $pathParams['showID'];
        $date = $this->request->getParameter('date');

        $showData = $this->showService->getShowDataForBooking($showID,$date);
        $data = [
            "showData" => $showData
        ];

        $html = $this->renderer->render('Book', $data);

        $this->response->setContent($html);
    }

    /**
     * order generation
     * @param $pathParams
     */
    public function checkout($pathParams) {
        $showID = $pathParams['showID'];
        $orderParams = $this->request->getBodyParameters();
        $orderParams['showID'] = $showID;

        $requirements = array(
            "showID" => array("required" => true, "type" => "int"),
            "quantity" => array("required" => true, "type" => "int"),
            "date" => array("required" => true,"type" => "date")
        );

        //validate the input
        $validatorResponse = $this->validator->validateInput($requirements,$orderParams);

        if(!$validatorResponse['status']) {
            $response = $this->responseFormatter->generatorAPIResponseForFailure(BAD_REQUEST,$validatorResponse['msg']);
        }else {
            $response = $this->showService->generateOrderForBooking($showID,$orderParams);

            if($response['status']) {
                $response = $this->responseFormatter->generatorAPIResponseForSuccess(DATA_FOUND,$response);
            } else {
                $response = $this->responseFormatter->generatorAPIResponseForFailure(DATA_ERROR,$response['errorMsg']);
            }
        }

        $this->response->setHeader("Content-Type","application/json");
        $this->response->setStatusCode($response['statusCode']);
        $this->response->setContent($response['response']);
    }

}
