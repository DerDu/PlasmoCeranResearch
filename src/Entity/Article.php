<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     */
    protected $id;

    /**
     * @var ArrayCollection $configList
     * @ORM\OneToMany(targetEntity="App\Entity\Config", mappedBy="article")
     */
    protected $configList;
    /**
     * @var ArrayCollection $processList
     * @ORM\OneToMany(targetEntity="App\Entity\Process", mappedBy="article")
     */
    protected $processList;
    /**
     * @var string $name
     * @ORM\Column(type="string", length=255)
     */
    protected $name = null;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->configList = new ArrayCollection();
        $this->processList = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getConfigList(): ArrayCollection
    {
        return $this->configList;
    }

    /**
     * @param ArrayCollection $configList
     * @return Article
     */
    public function setConfigList(ArrayCollection $configList): Article
    {
        $this->configList = $configList;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProcessList(): ArrayCollection
    {
        return $this->processList;
    }

    /**
     * @param ArrayCollection $processList
     * @return Article
     */
    public function setProcessList(ArrayCollection $processList): Article
    {
        $this->processList = $processList;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Article
     */
    public function setName(string $name): Article
    {
        $this->name = $name;
        return $this;
    }

}
