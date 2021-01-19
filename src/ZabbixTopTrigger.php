<?php


namespace ZabbixComponent;


class ZabbixTopTrigger extends ZabbixComponent
{

    public function getComponent($componentId = null)
    {
        $this->login();

        return $this->httpClient->get('toptriggers.php', [
            'query' => [
                'from' => $this->startTime->format('Y-m-d H:i:s'),
                'to' => $this->endTime->format('Y-m-d H:i:s'),
                'profileIdx' => 'web.toptriggers.filter',
            ],
        ])->getBody()->getContents();
    }
}