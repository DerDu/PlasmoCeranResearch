<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ProcessRepository")
 */
class Process extends AbstractEntity
{
    const PROPERTY_ARTICLE = 'article';
    const PROPERTY_CONFIG = 'config';
    const PROPERTY_PROCESS = 'process';
    const PROPERTY_TIMESTAMP = 'timestamp';
    /**
     * @var Article $article
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="processList")
     */
    protected $article;
    /**
     * @var Config $config
     * @ORM\ManyToOne(targetEntity="App\Entity\Config", inversedBy="processList")
     */
    protected $config;
    /**
     * @var string $process
     * @ORM\Column(type="string", length=255)
     */
    protected $process;
    /**
     * @var \DateTime $timestamp
     * @ORM\Column(type="datetime")
     */
    protected $timestamp = null;
    /**
     * @var integer $counterFrame
     * @ORM\Column(type="integer")
     */
    protected $counterFrame = null;
    /**
     * @var integer $thresholdPixel
     * @ORM\Column(type="integer")
     */
    protected $thresholdPixel = null;
    /**
     * @var float $currentValue
     * @ORM\Column(type="float")
     */
    protected $currentValue = null;
    /**
     * @var float $voltageValue
     * @ORM\Column(type="float")
     */
    protected $voltageValue = null;
    /**
     * @var float $temperatureValue
     * @ORM\Column(type="float")
     */
    protected $temperatureValue = null;
    /**
     * @var string $remarkValue
     * @ORM\Column(type="string")
     */
    protected $remarkValue = null;

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param Config $config
     * @return Process
     */
    public function setConfig(Config $config): Process
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     * @return Process
     */
    public function setTimestamp(\DateTime $timestamp): Process
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return int
     */
    public function getCounterFrame(): int
    {
        return $this->counterFrame;
    }

    /**
     * @param int $counterFrame
     * @return Process
     */
    public function setCounterFrame(int $counterFrame): Process
    {
        $this->counterFrame = $counterFrame;
        return $this;
    }

    /**
     * @return int
     */
    public function getThresholdPixel(): int
    {
        return $this->thresholdPixel;
    }

    /**
     * @param int $thresholdPixel
     * @return Process
     */
    public function setThresholdPixel(int $thresholdPixel): Process
    {
        $this->thresholdPixel = $thresholdPixel;
        return $this;
    }

    /**
     * @return float
     */
    public function getCurrentValue(): float
    {
        return $this->currentValue;
    }

    /**
     * @param float $currentValue
     * @return Process
     */
    public function setCurrentValue(float $currentValue): Process
    {
        $this->currentValue = $currentValue;
        return $this;
    }

    /**
     * @return float
     */
    public function getVoltageValue(): float
    {
        return $this->voltageValue;
    }

    /**
     * @param float $voltageValue
     * @return Process
     */
    public function setVoltageValue(float $voltageValue): Process
    {
        $this->voltageValue = $voltageValue;
        return $this;
    }

    /**
     * @return float
     */
    public function getTemperatureValue(): float
    {
        return $this->temperatureValue;
    }

    /**
     * @param float $temperatureValue
     * @return Process
     */
    public function setTemperatureValue(float $temperatureValue): Process
    {
        $this->temperatureValue = $temperatureValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getRemarkValue(): string
    {
        return $this->remarkValue;
    }

    /**
     * @param string $remarkValue
     * @return Process
     */
    public function setRemarkValue(string $remarkValue): Process
    {
        $this->remarkValue = $remarkValue;
        return $this;
    }

    /**
     * @return Article
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return Process
     */
    public function setArticle(Article $article): Process
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProcess(): ?string
    {
        return $this->process;
    }

    /**
     * @param string $process
     * @return Process
     */
    public function setProcess(string $process): Process
    {
        $this->process = $process;

        return $this;
    }
}
