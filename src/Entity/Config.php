<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ConfigRepository")
 */
class Config extends AbstractEntity
{
    /**
     * @var Article $article
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="configList", cascade={"all"})
     */
    protected $article;
    /**
     * @ApiSubresource(maxDepth=1)
     * @Assert\Collection()
     * @var Collection $processList
     * @ORM\OneToMany(targetEntity="App\Entity\Process", mappedBy="config", orphanRemoval=true, cascade={"all"})
     */
    protected $processList;
    /**
     * Maximale Temperatur des Elektrolyts
     *
     * - Celsius
     *
     * @var float $temperatureMaximum
     * @ORM\Column(type="decimal",precision=10,scale=4)
     */
    protected $temperatureMaximum = 0.0;
    /**
     * Text für Einblendung in Videobild
     *
     * @var string $overlayText
     * @ORM\Column(type="string")
     */
    protected $overlayText = '';
    /**
     * Startwert Spannung
     *
     * @var int $voltageStart
     * @ORM\Column(type="integer")
     */
    protected $voltageStart = 0;
    /**
     * Obere Grenze für Spannung
     *
     * @var int $voltageLimit
     * @ORM\Column(type="integer")
     */
    protected $voltageLimit = 0;
    /**
     * Hysterese für Spannung
     *
     * @var float $voltageHysteresis
     * @ORM\Column(type="decimal",precision=10,scale=4)
     */
    protected $voltageHysteresis = 0.0;
    /**
     * Schrittweite für Spannungsänderung
     *
     * @var float $voltageStep
     * @ORM\Column(type="decimal",precision=10,scale=4)
     */
    protected $voltageStep = 0.0;
    /**
     * Untere Grenze für Stromstärke
     *
     * @var int $currentLimit
     * @ORM\Column(type="integer")
     */
    protected $currentLimit = 0;
    /**
     * Limit für Funkenintensität
     *
     * - Percent
     *
     * @var int $intensityLimit
     * @ORM\Column(type="integer")
     */
    protected $intensityLimit = 0;
    /**
     * Schwellwert für Funkenintensität
     *
     * - Percent
     *
     * @var int $intensityThreshold
     * @ORM\Column(type="integer")
     */
    protected $intensityThreshold = 0;
    /**
     * Hysterese für Funkenintensität
     *
     * - Percent
     *
     * @var int $intensityHysteresis
     * @ORM\Column(type="integer")
     */
    protected $intensityHysteresis = 0;

    /**
     * @return Article
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }

    /**
     * @param Article $article
     * @return Config
     */
    public function setArticle(Article $article): Config
    {
        $this->article = $article;
        return $this;
    }

    /**
     * @return float
     */
    public function getTemperatureMaximum(): float
    {
        return $this->temperatureMaximum;
    }

    /**
     * @param float $temperatureMaximum
     * @return Config
     */
    public function setTemperatureMaximum(float $temperatureMaximum): Config
    {
        $this->temperatureMaximum = $temperatureMaximum;
        return $this;
    }

    /**
     * @return string
     */
    public function getOverlayText(): string
    {
        return $this->overlayText;
    }

    /**
     * @param string $overlayText
     * @return Config
     */
    public function setOverlayText(string $overlayText): Config
    {
        $this->overlayText = $overlayText;
        return $this;
    }

    /**
     * @return int
     */
    public function getVoltageStart(): int
    {
        return $this->voltageStart;
    }

    /**
     * @param int $voltageStart
     * @return Config
     */
    public function setVoltageStart(int $voltageStart): Config
    {
        $this->voltageStart = $voltageStart;
        return $this;
    }

    /**
     * @return int
     */
    public function getVoltageLimit(): int
    {
        return $this->voltageLimit;
    }

    /**
     * @param int $voltageLimit
     * @return Config
     */
    public function setVoltageLimit(int $voltageLimit): Config
    {
        $this->voltageLimit = $voltageLimit;
        return $this;
    }

    /**
     * @return float
     */
    public function getVoltageHysteresis(): float
    {
        return $this->voltageHysteresis;
    }

    /**
     * @param float $voltageHysteresis
     * @return Config
     */
    public function setVoltageHysteresis(float $voltageHysteresis): Config
    {
        $this->voltageHysteresis = $voltageHysteresis;
        return $this;
    }

    /**
     * @return float
     */
    public function getVoltageStep(): float
    {
        return $this->voltageStep;
    }

    /**
     * @param float $voltageStep
     * @return Config
     */
    public function setVoltageStep(float $voltageStep): Config
    {
        $this->voltageStep = $voltageStep;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentLimit(): int
    {
        return $this->currentLimit;
    }

    /**
     * @param int $currentLimit
     * @return Config
     */
    public function setCurrentLimit(int $currentLimit): Config
    {
        $this->currentLimit = $currentLimit;
        return $this;
    }

    /**
     * @return int
     */
    public function getIntensityLimit(): int
    {
        return $this->intensityLimit;
    }

    /**
     * @param int $intensityLimit
     * @return Config
     */
    public function setIntensityLimit(int $intensityLimit): Config
    {
        $this->intensityLimit = $intensityLimit;
        return $this;
    }

    /**
     * @return int
     */
    public function getIntensityThreshold(): int
    {
        return $this->intensityThreshold;
    }

    /**
     * @param int $intensityThreshold
     * @return Config
     */
    public function setIntensityThreshold(int $intensityThreshold): Config
    {
        $this->intensityThreshold = $intensityThreshold;
        return $this;
    }

    /**
     * @return int
     */
    public function getIntensityHysteresis(): int
    {
        return $this->intensityHysteresis;
    }

    /**
     * @param int $intensityHysteresis
     * @return Config
     */
    public function setIntensityHysteresis(int $intensityHysteresis): Config
    {
        $this->intensityHysteresis = $intensityHysteresis;
        return $this;
    }

}
