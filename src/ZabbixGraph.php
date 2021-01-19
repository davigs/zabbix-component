<?php

namespace ZabbixComponent;

class ZabbixGraph extends ZabbixComponent
{
    private $width;
    private $height;

    /**
     * Request graph from Zabbix and return a raw image. If an error
     * occurred Zabbix will output this as an image.
     *
     * @param  int  $graphId  ID of the graph to be requested
     * @return string
     */
    public function getComponent($graphId = null)
    {
        if ($graphId !== null) {
            $this->login();

            return $this->httpClient->get('chart2.php', [
                'query' => [
                    'graphid' => $graphId,
                    'width' => $this->width,
                    'height' => $this->height,
                    'from' => $this->startTime->format('Y-m-d H:i:s'),
                    'to' => $this->endTime->format('Y-m-d H:i:s'),
                    'profileIdx' => 'web.charts.filter',
                    'profileIdx2' => $graphId
                ],
            ])->getBody()->getContents();
        }
        throw new \Exception('Nenhum gráfico foi informado');
    }

    /**
     * Specify width of the graph in pixels, by default 400.
     *
     * @param  int  $width  Width in pixels
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Specify height of the graph in pixels, by default 400.
     *
     * @param  int  $height  Height in pixels
     * @return $this
     */
    public function height($height)
    {
        $this->height = $height;
        return $this;
    }
}