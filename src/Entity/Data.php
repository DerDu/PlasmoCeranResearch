<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DataRepository")
 */
class Data extends AbstractEntity
{
    /**
     * @ORM\Column(type="integer")
     */
    protected $frame;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;
}
