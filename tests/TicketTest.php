<?php
/**
 * User: karan.tuteja26@gmail.com
 */

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Request;

class TicketTest extends TestCase
{
    private $client;

    /**
     * This is set up method for setting the primary need to tests
     */
    protected function setUp()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://127.0.0.1'
        ]);
    }

    /**
     * type of destructor
     */
    protected function tearDown()
    {
        $this->client = null;
    }

    /**
     * test checkout api
     */
    public function testCheckout()
    {
        $response = $this->client->request('POST', '/checkout/3',
        [
            'form_params' => [
                "quantity" => "1",
                "date" => "2018-04-30"
            ]
        ]);


        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody(), true);
        $this->assertEquals(true, $body['status']);
        $this->assertArrayHasKey('msg',$body['outputParams']['data']);
    }
}