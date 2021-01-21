<?php

namespace ZabbixComponent;

class ZabbixTopTrigger extends ZabbixComponent
{
    public function getComponent($component = null)
    {
        $this->login();
        $query = [
            'from' => $this->startTime->format('Y-m-d H:i:s'),
            'to' => $this->endTime->format('Y-m-d H:i:s'),
            'profileIdx' => 'web.toptriggers.filter',
        ];

        if ($component !== null && is_array($component) === true) {
            $query = array_merge($query, $component);
        }

        $request = $this->httpClient->get('toptriggers.php', [
            'query' => $query,
        ])->getBody()->getContents();

        $dom = new \DOMDocument('1.0', 'UTF-8');
        @$dom->loadHTML($request);
        $rows = $dom->getElementsByTagName('tr');
        $topTriggers = [];
        foreach ($rows as $row) {
            $topTrigger = [];
            $index = 0;
            foreach ($row->childNodes as $node) {
                if ($node->nodeName === 'td') {
                    switch ($index) {
                        case 1:
                            $key = 'trigger';
                            break;
                        case 2:
                            $key = 'severity';
                            break;
                        case 3:
                            $key = 'change_status';
                            break;
                        default:
                            $key = 'host';
                            break;
                    }
                    $topTrigger[$key] = $node->nodeValue;
                    $index++;
                }
            }
            if (empty($topTrigger) === false) {
                $topTriggers[] = $topTrigger;
            }
        }

        return $topTriggers;
    }
}