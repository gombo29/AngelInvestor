<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Project
 *
 * @ORM\Table(name="Project")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Project
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
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=100, nullable=true)
     */
    private $company_name;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=100, nullable=true)
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=100, nullable=true)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=100, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="short_summary", type="text",  nullable=true)
     */
    private $short_summary;

    /**
     * @var string
     *
     * @ORM\Column(name="business", type="text",  nullable=true)
     */
    private $business;

    /**
     * @var string
     *
     * @ORM\Column(name="market", type="text",  nullable=true)
     */
    private $market;


    /**
     * @var string
     *
     * @ORM\Column(name="progress", type="text",  nullable=true)
     */
    private $progress;

    /**
     * @var string
     *
     * @ORM\Column(name="future", type="text",  nullable=true)
     */
    private $future;

    /**
     * @var string
     *
     * @ORM\Column(name="deal", type="text",  nullable=true)
     */
    private $deal;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=100, nullable=true)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_img", type="string", length=100, nullable=true)
     */
    private $profileImg;


    /**
     * @var string
     *
     * @ORM\Column(name="document", type="string", length=100, nullable=true)
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity="ProjectT" )
     * @ORM\JoinColumn(name="project_type", referencedColumnName="id", nullable=true)
     */
    private $projectType;

    /**
     * @ORM\ManyToOne(targetEntity="ProjectT" )
     * @ORM\JoinColumn(name="project_type_add", referencedColumnName="id", nullable=true)
     */
    private $projectTypeAdd;


    /**
     * @ORM\ManyToOne(targetEntity="User" )
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true)
     */
    private $user;




    /**
     * @var int
     * @ORM\Column(name="like_count", type="integer", nullable=false)
     */
    private $likeCount;

    /**
     * @var int
     * @ORM\Column(name="finish_price", type="integer", nullable=false)
     */
    private $finishPrice;

    /**
     * @var int
     * @ORM\Column(name="total_price", type="integer", nullable=false)
     */
    private $totalPrice;

    /**
     * @var int
     * @ORM\Column(name="stage", type="integer", nullable=true)
     */
    private $stage; // 1- prestartup 2- MVP finished product 3- Achieving Sales 4- Breaking even 5- profitable 5- other

    /**
     * @var int
     * @ORM\Column(name="role", type="integer", nullable=true)
     */
    private $role; // 1- Investor role 2- Silent 3- Daily Involvement 4- weekly Involvement 5- weekly Involvement 6- Any


    /**
     * @var int
     * @ORM\Column(name="huvi", type="integer", nullable=true)
     */
    private $huvi;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_hide", type="boolean")
     */
    private $isHide;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_remove", type="boolean")
     */
    private $isRemove;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_special", type="boolean")
     */
    private $isSpecial;

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
     * @var int
     *
     */
    public $startPrice;

    /**
     * @var int
     */
    public $endPrice;

    /**
     *
     * @Assert\Image(
     *        mimeTypesMessage = "Зурган файл биш байна!"
     * )
     *
     */
    public $imagefile;

    /**
     *
     * @Assert\Image(
     *        mimeTypesMessage = "Зурган файл биш байна!"
     * )
     *
     */
    public $profileimagefile;

    /**
     *
     * @Assert\File(
     *        mimeTypesMessage = "Зурган файл биш байна!"
     * )
     *
     */
    public $documentfile;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreatedDate(new \DateTime("now"));
        $this->setIsRemove(0);
        $this->setLikeCount(0);
        $this->setIsSpecial(0);
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
    public function setProfileImageFile(UploadedFile $file = null)
    {
        $this->profileimagefile = $file;
    }

    /**
     * Get Image
     *
     * @return UploadedFile
     */
    public function getProfileImageFile()
    {
        return $this->profileimagefile;
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
     * Get file
     *
     * @return UploadedFile
     */
    public function getDocumentFile()
    {
        return $this->documentfile;
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     */
    public function setDocumentFile(UploadedFile $file = null)
    {
        $this->documentfile = $file;
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

        $resources = $container->getParameter('localstatfolder');

        $dir = 'project/img';
        $filename = $this->getImageFile()->getFilename() . '.' . $this->getImageFile()->guessExtension();
        $this->getImageFile()->move(
            $resources . '/' . $dir, $filename
        );
        $path = $dir . "/" . $filename;
        $this->img = $path;

        $imageGod = $container->get('imagegod');
        $imageGod->resizeImageToMaxOnlyHeight($resources . $path, $resources . $path, 350);


        $this->imagefile = null;
    }

    /**
     *
     * @param $container
     */
    public function uploadProfileImage(Container $container)
    {

        if (null === $this->getProfileImageFile()) {
            return;
        }

        $resources = $container->getParameter('localstatfolder');

        $dir = 'project/img';
        $filename = $this->getProfileImageFile()->getFilename() . '.' . $this->getProfileImageFile()->guessExtension();
        $this->getProfileImageFile()->move(
            $resources . '/' . $dir, $filename
        );
        $path = $dir . "/" . $filename;
        $this->profileImg = $path;

        $imageGod = $container->get('imagegod');
        $imageGod->resizeImageToMaxOnlyWidth($resources . $path, $resources . $path, 300);

        $this->profileimagefile = null;
    }


    /**
     *
     * @param $container
     */
    public function uploadFile(Container $container)
    {

        if (null === $this->getDocumentFile()) {
            return;
        }

        $resources = $container->getParameter('localstatfolder');

        $dir = 'project/document';
        $filename = $this->getDocumentFile()->getFilename() . '.' . $this->getDocumentFile()->guessExtension();
        $this->getDocumentFile()->move(
            $resources . '/' . $dir, $filename
        );
        $path = $dir . "/" . $filename;
        $this->document = $path;
        $this->documentfile = null;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Project
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
     * Set img
     *
     * @param string $img
     *
     * @return Project
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
     * Set finishPrice
     *
     * @param integer $finishPrice
     *
     * @return Project
     */
    public function setFinishPrice($finishPrice)
    {
        $this->finishPrice = $finishPrice;

        return $this;
    }

    /**
     * Get finishPrice
     *
     * @return integer
     */
    public function getFinishPrice()
    {
        return $this->finishPrice;
    }

    /**
     * Set totalPrice
     *
     * @param integer $totalPrice
     *
     * @return Project
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return integer
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Project
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
     * @return Project
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
     * Set projectType
     *
     * @param \happy\CmsBundle\Entity\ProjectT $projectType
     *
     * @return Project
     */
    public function setProjectType(\happy\CmsBundle\Entity\ProjectT $projectType = null)
    {
        $this->projectType = $projectType;

        return $this;
    }

    /**
     * Get projectType
     *
     * @return \happy\CmsBundle\Entity\ProjectT
     */
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * Set user
     *
     * @param \happy\CmsBundle\Entity\User $user
     *
     * @return Project
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

    /**
     * Set huvi
     *
     * @param integer $huvi
     *
     * @return Project
     */
    public function setHuvi($huvi)
    {
        $this->huvi = $huvi;

        return $this;
    }

    /**
     * Get huvi
     *
     * @return integer
     */
    public function getHuvi()
    {
        return $this->huvi;
    }

    /**
     * Set isHide
     *
     * @param boolean $isHide
     *
     * @return Project
     */
    public function setIsHide($isHide)
    {
        $this->isHide = $isHide;

        return $this;
    }

    /**
     * Get isHide
     *
     * @return boolean
     */
    public function getIsHide()
    {
        return $this->isHide;
    }

    /**
     * Set video
     *
     * @param string $video
     *
     * @return Project
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Project
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Project
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set shortSummary
     *
     * @param string $shortSummary
     *
     * @return Project
     */
    public function setShortSummary($shortSummary)
    {
        $this->short_summary = $shortSummary;

        return $this;
    }

    /**
     * Get shortSummary
     *
     * @return string
     */
    public function getShortSummary()
    {
        return $this->short_summary;
    }

    /**
     * Set business
     *
     * @param string $business
     *
     * @return Project
     */
    public function setBusiness($business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * Get business
     *
     * @return string
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Set market
     *
     * @param string $market
     *
     * @return Project
     */
    public function setMarket($market)
    {
        $this->market = $market;

        return $this;
    }

    /**
     * Get market
     *
     * @return string
     */
    public function getMarket()
    {
        return $this->market;
    }

    /**
     * Set progress
     *
     * @param string $progress
     *
     * @return Project
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return string
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set future
     *
     * @param string $future
     *
     * @return Project
     */
    public function setFuture($future)
    {
        $this->future = $future;

        return $this;
    }

    /**
     * Get future
     *
     * @return string
     */
    public function getFuture()
    {
        return $this->future;
    }

    /**
     * Set deal
     *
     * @param string $deal
     *
     * @return Project
     */
    public function setDeal($deal)
    {
        $this->deal = $deal;

        return $this;
    }

    /**
     * Get deal
     *
     * @return string
     */
    public function getDeal()
    {
        return $this->deal;
    }

    /**
     * Set document
     *
     * @param string $document
     *
     * @return Project
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set stage
     *
     * @param integer $stage
     *
     * @return Project
     */
    public function setStage($stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * Get stage
     *
     * @return integer
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * Set role
     *
     * @param integer $role
     *
     * @return Project
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return integer
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set projectTypeAdd
     *
     * @param \happy\CmsBundle\Entity\ProjectT $projectTypeAdd
     *
     * @return Project
     */
    public function setProjectTypeAdd(\happy\CmsBundle\Entity\ProjectT $projectTypeAdd = null)
    {
        $this->projectTypeAdd = $projectTypeAdd;

        return $this;
    }

    /**
     * Get projectTypeAdd
     *
     * @return \happy\CmsBundle\Entity\ProjectT
     */
    public function getProjectTypeAdd()
    {
        return $this->projectTypeAdd;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Project
     */
    public function setCompanyName($companyName)
    {
        $this->company_name = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * Set isRemove
     *
     * @param boolean $isRemove
     *
     * @return Project
     */
    public function setIsRemove($isRemove)
    {
        $this->isRemove = $isRemove;

        return $this;
    }

    /**
     * Get isRemove
     *
     * @return boolean
     */
    public function getIsRemove()
    {
        return $this->isRemove;
    }

    /**
     * Set profileImg
     *
     * @param string $profileImg
     *
     * @return Project
     */
    public function setProfileImg($profileImg)
    {
        $this->profileImg = $profileImg;

        return $this;
    }

    /**
     * Get profileImg
     *
     * @return string
     */
    public function getProfileImg()
    {
        return $this->profileImg;
    }

    /**
     * Set likeCount
     *
     * @param integer $likeCount
     *
     * @return Project
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
     * Set isSpecial
     *
     * @param boolean $isSpecial
     *
     * @return Project
     */
    public function setIsSpecial($isSpecial)
    {
        $this->isSpecial = $isSpecial;

        return $this;
    }

    /**
     * Get isSpecial
     *
     * @return boolean
     */
    public function getIsSpecial()
    {
        return $this->isSpecial;
    }
}
