<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProjectInvestor
 *
 * @ORM\Table(name="ProjectInvestor")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ProjectInvestor
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Project" )
     * @ORM\JoinColumn(name="project", referencedColumnName="id", nullable=true)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="User" )
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_interest", type="boolean")
     */
    private $isInterest;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_investment", type="boolean")
     */
    private $isInvestment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @var \DateTime
     *
     */
    public $ehlehDate;

    /**
     * @var \DateTime
     */
    public $duusahDate;


    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreatedDate(new \DateTime("now"));
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->setUpdatedDate(new \DateTime("now"));
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isInterest
     *
     * @param boolean $isInterest
     *
     * @return ProjectInvestor
     */
    public function setIsInterest($isInterest)
    {
        $this->isInterest = $isInterest;

        return $this;
    }

    /**
     * Get isInterest
     *
     * @return boolean
     */
    public function getIsInterest()
    {
        return $this->isInterest;
    }

    /**
     * Set isInvestment
     *
     * @param boolean $isInvestment
     *
     * @return ProjectInvestor
     */
    public function setIsInvestment($isInvestment)
    {
        $this->isInvestment = $isInvestment;

        return $this;
    }

    /**
     * Get isInvestment
     *
     * @return boolean
     */
    public function getIsInvestment()
    {
        return $this->isInvestment;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return ProjectInvestor
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     *
     * @return ProjectInvestor
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set project
     *
     * @param \happy\CmsBundle\Entity\Project $project
     *
     * @return ProjectInvestor
     */
    public function setProject(\happy\CmsBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \happy\CmsBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set user
     *
     * @param \happy\CmsBundle\Entity\User $user
     *
     * @return ProjectInvestor
     */
    public function setUser(\happy\CmsBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \happy\CmsBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
