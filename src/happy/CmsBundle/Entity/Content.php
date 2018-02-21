<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Banner
 *
 * @ORM\Table(name="Content")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Content
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_en", type="string", length=100, nullable=true)
     */
    private $nameEN;

    /**
     * @var string
     *
     * @ORM\Column(name="headline", type="text",  nullable=true)
     */
    private $headline;


    /**
     * @var string
     *
     * @ORM\Column(name="headline_en", type="text",  nullable=true)
     */
    private $headlineEn;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="text", nullable=true)
     */
    private $describe;

    /**
     * @var string
     *
     * @ORM\Column(name="descr_en", type="text", nullable=true)
     */
    private $describeEN;

    /**
     * @ORM\ManyToOne(targetEntity="ContentPosition" )
     * @ORM\JoinColumn(name="contentbairlal", referencedColumnName="id", nullable=true)
     */
    private $contentbairlal;


    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=100, nullable=true)
     */
    private $img;

    /**
     * @var int
     * @ORM\Column(name="like_count", type="integer", nullable=false)
     */
    private $likeCount;

    /**
     *
     * @Assert\Image(
     *        mimeTypesMessage = "Зурган файл биш байна!"
     * )
     *
     */
    public $imagefile;

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
        $this->setLikeCount(0);
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
     * Set name
     *
     * @param string $name
     *
     * @return Content
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set describe
     *
     * @param string $describe
     *
     * @return Content
     */
    public function setDescribe($describe)
    {
        $this->describe = $describe;

        return $this;
    }

    /**
     * Get describe
     *
     * @return string
     */
    public function getDescribe()
    {
        return $this->describe;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Content
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
     * @return Content
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
     * Set contentbairlal
     *
     * @param \happy\CmsBundle\Entity\ContentPosition $contentbairlal
     *
     * @return Content
     */
    public function setContentbairlal(\happy\CmsBundle\Entity\ContentPosition $contentbairlal = null)
    {
        $this->contentbairlal = $contentbairlal;

        return $this;
    }

    /**
     * Get contentbairlal
     *
     * @return \happy\CmsBundle\Entity\ContentPosition
     */
    public function getContentbairlal()
    {
        return $this->contentbairlal;
    }



    /**
     * Get Image
     *
     * @return UploadedFile
     */
    public function getImageFile()
    {
        return $this->imagefile;
    }

    /**
     * Set Image
     *
     * @param UploadedFile $file
     */
    public function setImageFile(UploadedFile $file = null)
    {
        $this->imagefile = $file;
    }

    /**
     *
     * @param $container
     */
    public function uploadImage(Container $container)
    {

        if (null === $this->getImageFile()) {
            return;
        }

        $resources = $container->getParameter('statfolder');

        $dir = 'content/img';
        $filename = $this->getImageFile()->getFilename() . '.' . $this->getImageFile()->guessExtension();
        $this->getImageFile()->move(
            $resources . '/' . $dir, $filename
        );
        $path = $dir . "/" . $filename;
        $this->img = $path;

        $imageGod = $container->get('imagegod');
        $imageGod->resizeImageToMaxOnlyWidth($resources . $path, $resources . $path, 300);


        $this->imagefile = null;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return Content
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set headline
     *
     * @param string $headline
     *
     * @return Content
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;

        return $this;
    }

    /**
     * Get headline
     *
     * @return string
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * Set likeCount
     *
     * @param integer $likeCount
     *
     * @return Content
     */
    public function setLikeCount($likeCount)
    {
        $this->likeCount = $likeCount;

        return $this;
    }

    /**
     * Get likeCount
     *
     * @return integer
     */
    public function getLikeCount()
    {
        return $this->likeCount;
    }

    /**
     * Set nameEN
     *
     * @param string $nameEN
     *
     * @return Content
     */
    public function setNameEN($nameEN)
    {
        $this->nameEN = $nameEN;

        return $this;
    }

    /**
     * Get nameEN
     *
     * @return string
     */
    public function getNameEN()
    {
        return $this->nameEN;
    }

    /**
     * Set headlineEn
     *
     * @param string $headlineEn
     *
     * @return Content
     */
    public function setHeadlineEn($headlineEn)
    {
        $this->headlineEn = $headlineEn;

        return $this;
    }

    /**
     * Get headlineEn
     *
     * @return string
     */
    public function getHeadlineEn()
    {
        return $this->headlineEn;
    }

    /**
     * Set describeEN
     *
     * @param string $describeEN
     *
     * @return Content
     */
    public function setDescribeEN($describeEN)
    {
        $this->describeEN = $describeEN;

        return $this;
    }

    /**
     * Get describeEN
     *
     * @return string
     */
    public function getDescribeEN()
    {
        return $this->describeEN;
    }
}
