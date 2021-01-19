<?php

namespace ZabbixComponent;

use DateTime;
use GuzzleHttp\Client as HttpClient;

abstract class ZabbixComponent
{
    protected $httpClient;
    protected $oldZabbix;
    protected $username;
    protected $password;

    protected $startTime;
    protected $endTime;

    /**
     *
     */
    abstract public function getComponent($componentId = null);

    /**
     * Construct and initalize ZabbixComponent.
     *
     * @param  string  $url        Full url of Zabbix location
     * @param  string  $username   Zabbix username
     * @param  string  $password   Zabbix password
     * @param  bool    $oldZabbix  Set to true if using Zabbix 1.8 or older, by default set to false
     */
    public function __construct($url, $username, $password, $oldZabbix = false)
    {
        $this->httpClient = $this->createHttpClient($url);
        $this->oldZabbix = $oldZabbix;
        $this->username = $username;
        $this->password = $password;

        $this->startTime = (new DateTime())->modify('-1 hour');
        $this->endTime = new DateTime();
    }

    /**
     * Create HTTP client for requesting the graph. The HTTP client should preserve cookies.
     *
     * @param  string  $url  Full url of Zabbix location
     * @return HttpClient
     */
    protected function createHttpClient($url)
    {
        return new HttpClient([
            'base_uri' => $url,
            'cookies' => true,
        ]);
    }

    /**
     * Login to Zabbix to acquire the needed session for requesting the graph.
     */
    protected function login()
    {
        $this->httpClient->post('index.php', [
            'form_params' => [
                'name' => $this->username,
                'password' => $this->password,
                'enter' => 'Sign in',
            ],
        ]);
    }

    /**
     * Specify start date and time of the data displayed in the graph.
     *
     * @param  DateTime  $startTime  Start date and time
     * @return $this
     */
    public function startTime(DateTime $startTime)
    {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * Specify end date and time of the data displayed in the graph.
     *
     * @param  DateTime  $endTime  End date and time
     * @return $this
     */
    public function endTime(DateTime $endTime)
    {
        $this->endTime = $endTime;
        return $this;
    }
}