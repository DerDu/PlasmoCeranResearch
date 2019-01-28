<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article extends AbstractEntity
{
    /**
     * @ApiSubresource(maxDepth=1)
     * @Assert\Collection()
     * @var Collection $configList
     * @ORM\OneToMany(targetEntity="App\Entity\Config", mappedBy="article")
     */
    protected $configList;
    /**
     * @ApiSubresource(maxDepth=1)
     * @Assert\Collection()
     * @var Collection $processList
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
    public function getConfigList(): Collection
    {
        return $this->configList;
    }

    /**
     * @param Collection $configList
     * @return Article
     */
    public function setConfigList(Collection $configList): Article
    {
        $this->configList = $configList;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getProcessList(): Collection
    {
        return $this->processList;
    }

    /**
     * @param Collection $processList
     * @return Article
     */
    public function setProcessList(Collection $processList): Article
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
