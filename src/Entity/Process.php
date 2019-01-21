<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProcessRepository")
 */
class Process extends AbstractEntity
{
    /**
     * @var Article $article
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="processList")
     */
    protected $article;

    /**
     * @var integer $frame
     * @ORM\Column(type="integer")
     */
    protected $frame = null;

    /**
     * @var \DateTime $timestamp
     * @ORM\Column(type="datetime")
     */
    protected $timestamp = null;
}
