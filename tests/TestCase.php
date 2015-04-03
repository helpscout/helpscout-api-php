<?php
use HelpScout\ApiClient;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public function getTestClient($fixture, $method = 'get')
    {
        $client = ApiClient::getInstance();
        $client->setCurl($this->getCurlMock($fixture, $method));
        $client->setKey('X');
        return $client;
    }

    public function getCurlMock($fixture, $method)
    {
        $path = __DIR__ . '/fixtures/' . $fixture . '.json';
        $data = json_decode(file_get_contents($path), true);

        $curl = $this->getMock('\Curl');
        $curl
            ->expects($this->any())
            ->method($method)
            ->will($this->returnValue((object) array(
                'headers' => $data['headers'],
                'body'    => $data['body']
            )));
        return $curl;
    }
}

/* End of file TestCase.php */
