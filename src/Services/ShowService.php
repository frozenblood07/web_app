<?php
/**
 * File: ShowService.php
 * User: karan.tuteja26@gmail.com
 * Description:
 */

namespace Ticket\Services;

use Ticket\Models\Show;
use Ticket\Repositories\ShowRepository;

class ShowService
{
    private $show;
    private $showRepository;
    /*
    public function __construct(Show $show, ShowRepository $showRepository) {
        $this->show  = $show;
        $this->showRepository = $showRepository;
    }*/

    public function getInventoryListForDate($date) {
        //$this->showRepository->
        $data = array();
        $data[0]['genre'] = "Musician";
        $data[0]['inventory'] = array(array('showID' => 1,'title'=>'cats','left'=>'50','available'=>0,'status'=>'open for sale','price' => '70', 'showBook'=>1),
            array('showID' => 2,'title'=>'cats2','left'=>'50','available'=>'3','status'=>'open for sale','price' => '70'));
        $data[1]['genre'] = "Comedy";
        $data[1]['inventory'] = array(array('showID' => 3,'title'=>'cats3','left'=>'50','available'=>'3','status'=>'open for sale','price' => '70'),
            array('showID' => 4,'title'=>'cats4','left'=>'50','available'=>'3','status'=>'open for sale','price' => '70'));

        $data[2]['genre'] = "Horror";
        $data[2]['inventory'] = array(array('showID' => 5,'title'=>'cats5','left'=>'50','available'=>'3','status'=>'open for sale','price' => '70'),
            array('showID' => 6,'title'=>'cats6','left'=>'50','available'=>'3','status'=>'open for sale','price' => '70'));


        //echo json_encode($data);die();
        return $data;
    }

    public function getShowDataForBooking($showID,$date){

        $data = array('showID' => 1, 'price'=>70, 'title'=>'cats','available'=>'3','date' => '2018-06-01');
        //echo json_encode($data);die();
        return $data;

    }

    public function generateOrderForBooking($showID,$orderParams) {
        //$data = array('status' => false,'error_msg' => 'Quantity not allowed');

        $data = array('status' => true, 'outputParams' => array('data'=> array('orderID' => '1','msg'=> 'Generated successfully')));
        $data = json_encode($data);
        return $data;
    }
}