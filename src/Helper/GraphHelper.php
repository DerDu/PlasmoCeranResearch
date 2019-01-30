<?php

namespace App\Helper;


use ZingChart\PHPWrapper\ZC;

/**
 * Class GraphHelper
 * @package App\Helper
 */
class GraphHelper
{
    /**
     * @var array $colors
     */
    private $colors = [];
    /**
     * @var ZC|null $chart
     */
    private $chart = null;
    /**
     * @var array $series
     */
    private $series = [];

    /**
     * GraphHelper constructor.
     * @param string $title
     * @param string $id
     * @param int $minValue
     */
    public function __construct(string $title, string $id = 'myChart', $minValue = 1484870400000)
    {
        $this->chart = new ZC($id);

        $this->setConfig('utc', true);

        $this->chart->setChartType("line");
        $this->chart->setChartHeight(700);

        $this->setConfig('plot.aspect', 'spline');

        $this->setConfig('borderColor', 'transparent');
        $this->setConfig('borderWidth', 0);
        $this->setConfig('borderRadius', 0);
        $this->setConfig('backgroundColor', '#f0f0f0');

        $this->chart->setTitle($title);
        $this->setConfig('title.adjustLayout', true);
        $this->setConfig('title.align', 'left');
        $this->setConfig('title.marginLeft', '15%');

        $this->setConfig('scaleX.minValue', $minValue);
        $this->setConfig('scaleX.step', 'second');
        $this->setConfig('scaleX.maxItems', 60);

        $this->setConfig('scaleX.itemsOverlap', false);
        $this->setConfig('scaleX.zooming', true);
        $this->setConfig('scaleX.transform.type', 'date');
        $this->setConfig('scaleX.transform.all', '%dd.%mm.%Y<br/>%H:%i:%s');

        $this->setConfig('preview.adjustLayout', true);
        $this->setConfig('preview.live', true);

        $this->setConfig('tooltip.visible', false);

//        $this->chart->setLegendTitle($title);
        $this->setConfig('legend.draggable', false);
        $this->setConfig('legend.backgroundColor', '#f0f0f0');
        $this->setConfig('legend.header.backgroundColor', '#f0f0f0');
//        $this->setConfig('legend.marker.visible', false);

        $this->setConfig('legend.item.margin', '5 17 2 0');
        $this->setConfig('legend.item.padding', '3 3 3 3');
        $this->setConfig('legend.item.fontColor', '#333');
        $this->setConfig('legend.item.cursor', 'hand');

        $this->setConfig('legend.verticalAlign', 'middle');
        $this->setConfig('legend.borderWidth', '1');

    }

    /**
     * @param $k
     * @param $v
     */
    private function setConfig($k, $v)
    {
        $this->chart->setConfig($k, $v);
    }

    /**
     * @param string $name
     * @param array $data
     * @param string $color
     */
    public function setSeries(string $name, array $data = [], $color = ''): void
    {
        $this->colors[$name] = $color;
        $this->series[$name] = $data;
    }

    /**
     * @return string
     */
    public function getChart()
    {
        $scale = [
            'scaleY',
            'scale-y-2',
            'scale-y-3',
            'scale-y-4',
            'scale-y-5',
            'scale-y-6',
        ];

        $axis = [
            'scale-x,scale-y',
            'scale-x,scale-y-2',
            'scale-x,scale-y-3',
            'scale-x,scale-y-4',
            'scale-x,scale-y-5',
            'scale-x,scale-y-6',
        ];

        $id = 0;
        foreach ($this->series as $name => $data) {
            $this->configScale($scale[$id], $name);
            $this->configSeries($id, $axis[$id], $data, $name, $this->colors[$name] ?? '');
            $id++;
        }

        return $this->chart->getRenderScript();
    }

    /**
     * @param string $scale
     * @param string $name
     * @return void
     */
    private function configScale(string $scale, string $name): void
    {
//        $this->setConfig($scale . '.step', 10);
        $this->setConfig($scale . '.label.text', $name);
        $this->setConfig($scale . '.guide.lineStyle', 'solid');
    }

    /**
     * @param int $id
     * @param string $scale
     * @param array $values
     * @param string $text
     * @param string $color
     */
    private function configSeries(
        int $id = 0,
        string $scale = '',
        array $values = [],
        string $text = '',
        string $color = ''
    ) {
        $this->setConfig('series[' . $id . '].scales', $scale);
        $this->setConfig('series[' . $id . '].values', $values);
        $this->setConfig('series[' . $id . '].text', $text);
        $this->setConfig('series[' . $id . '].legendItem.borderRadius', 0);
        $this->setConfig('series[' . $id . '].marker.size', 2);
        $this->setConfig('series[' . $id . '].marker.border-width', 1);
        $this->setConfig('series[' . $id . '].line-width', 1);
        if ($color) {
            $this->setConfig('series[' . $id . '].legendItem.backgroundColor', $color);
            $this->setConfig('series[' . $id . '].line-color', $color);
        }
    }
}
