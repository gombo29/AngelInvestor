<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\Content;
use happy\CmsBundle\Entity\Notification;
use happy\CmsBundle\Entity\Project;
use happy\CmsBundle\Entity\ProjectInvestor;
use happy\CmsBundle\Entity\ProjectT;
use happy\WebBundle\Form\AdviceSearchType;
use happy\WebBundle\Form\ProjectSearchType;
use happy\WebBundle\Form\ProjectTSearchType;
use happy\WebBundle\Util\EmailBox;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\CssSelector\Parser\Reader;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/")
     */
    public function soonAction()
    {

        return $this->render('happyWebBundle:Default:comingsoon.html.twig', array());
    }

    /**
     * @Route("/home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Banner')->createQueryBuilder('n');
        $banner = $qb
            ->leftJoin('n.bannerbairlal', 'b')
            ->addSelect('b')
            ->where('n.bannerbairlal <= 2')
            ->andWhere('n.publishDate <= :now')
            ->andWhere('n.endDate >= :now')
            ->setParameter('now', new \DateTime('now'))
            ->getQuery()
            ->getArrayResult();

        $qb = $em->getRepository('happyCmsBundle:Project')->createQueryBuilder('n');
        $project = $qb
            ->leftJoin('n.user', 'u')
            ->addSelect('u')
            ->where('n.isHide = 0')
            ->andWhere('n.isRemove = 0')
            ->andWhere('n.isSpecial = 1')
            ->getQuery()
            ->getArrayResult();


        $qb = $em->getRepository('happyCmsBundle:ProjectT')->createQueryBuilder('n');
        $projectt = $qb
            ->where('n.isSpecial = 1')
            ->setMaxResults(6)
            ->getQuery()
            ->getArrayResult();


        $qb = $em->getRepository('happyCmsBundle:Content')->createQueryBuilder('n');

        $advice = $qb
            ->leftJoin('n.contentbairlal', 'c')
            ->addSelect('c')
            ->andWhere('n.contentbairlal <= 4')
            ->orderBy('n.id', 'desc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('happyWebBundle:Default:home.html.twig', array(
            'id' => 1,
            'banner' => $banner,
            'projectt' => $projectt,
            'project' => $project,
            'advice' => $advice,
            'helpmenu' => $this->menuDefault()
        ));
    }

    /**
     * @Route("/projects/{page}", name="projects", requirements={"page" = "\d+"}, defaults={"page" = 1} )
     * @Method({"GET", "POST"})
     * Төслүүд
     *
     */
    public function projectsAction(Request $request, $page)
    {
        $pagesize = 5;
        $searchEntity = new Project ();

        $lang = $this->get('request')->getLocale();

        $searchform = $this->createForm(new ProjectSearchType($lang), $searchEntity);
        $search = false;
        $em = $this->getDoctrine()->getManager();

        if ($request->get("submit") == 'submit') {
            $searchform->bind($request);
            $search = true;
        }
        $qb = $em->getRepository('happyCmsBundle:Project')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('LOWER(n.name) like LOWER(:name)')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
            }

            if ($searchEntity->startPrice && $searchEntity->endPrice != '') {
                $qb->andWhere('n.finishPrice >= :startPrice')
                    ->setParameter('startPrice', $searchEntity->startPrice);

                $qb->andWhere('n.finishPrice <= :endPrice')
                    ->setParameter('endPrice', $searchEntity->endPrice);
            }


            if ($searchEntity->getProjectType() && $searchEntity->getProjectType() != '') {
                $qb->andWhere('n.projectType = :idb')
                    ->setParameter('idb', $searchEntity->getProjectType());
            }
        }

        $qb
            ->andWhere('n.isHide = 0')
            ->andWhere('n.isRemove = 0');

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();

        $project = $qb
            ->leftJoin('n.user', 'u')
            ->addSelect('u')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->orderBy('n.isSpecial', 'desc')
            ->addOrderBy('n.id', 'desc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('happyWebBundle:Default:projects.html.twig',
            array(
                'id' => 2,
                'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
                'project' => $project,
                'search' => $search,
                'pagesize' => $pagesize,
                'page' => $page,
                'count' => $count,
                'searchform' => $searchform->createView(),
                'helpmenu' => $this->menuDefault()
            )
        );
    }


    /**
     * @Route("/advice/{page}/{menu}", name="advice", requirements={"page" = "\d+","menu" = "\d+"}, defaults={"page" = 1, "menu" = 5} )
     * @Method({"GET", "POST"})
     * Зөвлөгөөнүүд
     *
     */
    public function adviceAction(Request $request, $page, $menu)
    {
        $pagesize = 20;
        $searchEntity = new Content();
        $searchform = $this->createForm(new AdviceSearchType(), $searchEntity);
        $search = false;
        $em = $this->getDoctrine()->getManager();
        if ($request->get("submit") == 'submit') {
            $searchform->bind($request);
            $search = true;
        }
        /**@var Content[] $content */
        $qb = $em->getRepository('happyCmsBundle:Content')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('LOWER(n.name) like LOWER(:name)')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
            }
        }

        $qb
            ->andWhere('n.contentbairlal = :menu')
            ->setParameter('menu', $menu);

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();

        $advice = $qb
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->orderBy('n.id', 'desc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('happyWebBundle:Default:advice.html.twig',
            array(
                'id' => 3,
                'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
                'advice' => $advice,
                'search' => $search,
                'pagesize' => $pagesize,
                'page' => $page,
                'menu' => $menu,
                'count' => $count,
                'searchform' => $searchform->createView(),
                'helpmenu' => $this->menuDefault()
            )
        );
    }


    /**
     * Remove Project entity.
     *
     * @Route("/project_detail/{id}", name="project_detail" , requirements={"id" = "\d+"})
     *
     *
     */
    public function projectDetailAction(Project $project)
    {


        if ($this->getUser()) {
            $em = $this->getDoctrine()->getManager();

            $qb = $em->getRepository('happyCmsBundle:ProjectTeam')->createQueryBuilder('n');
            $project_team = $qb
                ->where('n.project = :id')
                ->setParameter('id', $project->getId())
                ->getQuery()
                ->getResult();


            $qb = $em->getRepository('happyCmsBundle:ProjectInvestor')->createQueryBuilder('n');
            $project_investor = $qb
                ->where('n.project = :id')
                ->andWhere('n.user = :uid')
                ->setParameter('id', $project->getId())
                ->setParameter('uid', $this->getUser()->getId())
                ->getQuery()
                ->getResult();

            $isInvestment = false;
            $isInterest = false;

            if ($project_investor) {

                $isInvestment = $project_investor[0]->getIsInvestment();
                $isInterest = $project_investor[0]->getIsInterest();
            }

//        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//        $project->setLikeCount($this->fblikecount($url));
//        $em->flush();
        } else {
            return $this->redirectToRoute('happy_web_default_index');
        }


        return $this->render('happyWebBundle:Default:project_detail.html.twig',
            array(
                'id' => 2,
                'project' => $project,
                'project_team' => $project_team,
                'isInvestment' => $isInvestment,
                'isInterest' => $isInterest,
                'helpmenu' => $this->menuDefault()
            )
        );
    }


    /**
     * Remove ProjectInvestor entity.
     *
     * @Route("/project_investment/{id}", name="project_investment" , requirements={"id" = "\d+"})
     *
     *
     */
    public function projectInvestmentAction(Project $project)
    {


        if ($this->getUser()) {
            $em = $this->getDoctrine()->getManager();


            $qb = $em->getRepository('happyCmsBundle:ProjectInvestor')->createQueryBuilder('n');
            $project_investor = $qb
                ->where('n.project = :id')
                ->andWhere('n.user = :uid')
                ->setParameter('id', $project->getId())
                ->setParameter('uid', $this->getUser()->getId())
                ->getQuery()
                ->getResult();
            if ($project_investor) {
                $pi = $em->getRepository('happyCmsBundle:ProjectInvestor')->findBy(
                    array(
                        'user' => $this->getUser()->getId(),
                        'project' => $project->getId()
                    )
                );

                $pi[0]->setIsInvestment(1);

            } else {
                $pi = new  ProjectInvestor();
                $pi->setProject($project);
                $pi->setUser($this->getUser());
                $pi->setIsInvestment(1);
                $pi->setIsInterest(0);
                $em->persist($pi);
            }

            $not = new  Notification();
            $not->setUser($project->getUser());
            $not->setHeader('Таны төсөлд хөрөнгө оруулахаар санал өгсөн байна.');
            $not->setDescribe($this->getUser()->getUsername() . ' нэртэй хөрөнгө оруулагч таны ' . $project->getName() . ' төсөлд хөрөнгө оруулахаар санал өгсөн байна. Бид хөрөнгө оруулагчтай холбогдон таньд хариу мэдэгдэх болно.');
            $em->persist($not);

            $u = $em->getRepository('happyCmsBundle:User')->find($project->getUser());
            $u->setIsNoti(1);

            $em->flush();
            return $this->redirectToRoute('project_detail', array('id' => $project->getId()));
        } else {
            return $this->redirectToRoute('happy_web_default_index');
        }
    }

    /**
     * Remove ProjectInvestor entity.
     *
     * @Route("/project_interest/{id}", name="project_interest" , requirements={"id" = "\d+"})
     *
     *
     */
    public function projectInterestAction(Project $project)
    {
        if ($this->getUser()) {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->getRepository('happyCmsBundle:ProjectInvestor')->createQueryBuilder('n');
            $project_investor = $qb
                ->where('n.project = :id')
                ->andWhere('n.user = :uid')
                ->setParameter('id', $project->getId())
                ->setParameter('uid', $this->getUser()->getId())
                ->getQuery()
                ->getResult();

            if ($project_investor) {
                $pi = $em->getRepository('happyCmsBundle:ProjectInvestor')->findBy(
                    array(
                        'user' => $this->getUser()->getId(),
                        'project' => $project->getId()
                    )
                );
                $pi[0]->setIsInterest(1);

            } else {
                $pi = new  ProjectInvestor();
                $pi->setProject($project);
                $pi->setUser($this->getUser());
                $pi->setIsInvestment(0);
                $pi->setIsInterest(1);
                $em->persist($pi);
            }

            $not = new  Notification();
            $not->setUser($project->getUser());
            $not->setHeader('Таны төслийг сонирхсон байна.');
            $not->setDescribe($this->getUser()->getUsername() . ' нэртэй хөрөнгө оруулагч таны ' . $project->getName() . ' төслийг сонирхсон байна.');
            $em->persist($not);

            $u = $em->getRepository('happyCmsBundle:User')->find($project->getUser());
            $u->setIsNoti(1);

            $em->flush();

            return $this->redirectToRoute('project_detail', array('id' => $project->getId()));
        } else {
            return $this->redirectToRoute('happy_web_default_index');
        }
    }


    protected function fblikecount($url)
    {
        $count = 0;
        $is_share = false;
        $is_comment_count = false;
        $is_share_count = false;
        $json = json_decode(file_get_contents('http://graph.facebook.com/' . $url));
        foreach ($json as $key => $val) {
            if ($key == "share") {
                $is_share = true;
                foreach ($val as $k => $v) {
                    if ($k == "comment_count") {
                        $is_comment_count = true;
                    } elseif ($k == "share_count") {
                        $is_share_count = true;
                    }
                }
            }
        }
        if ($is_share && $is_comment_count && $is_share_count) {
            $count = $json->share->share_count;
        }
        return $count;
    }


    /**
     * Remove Advice entity.
     *
     * @Route("/advice_detail/{id}", name="advice_detail" , requirements={"id" = "\d+"})
     *
     *
     */
    public function adviceDetailAction(Content $content)
    {
        $em = $this->getDoctrine()->getManager();

//        $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//        $content->setLikeCount($this->fblikecount($url));
//        $em->flush();

        return $this->render('happyWebBundle:Default:advice_detail.html.twig',
            array(
                'id' => 3,
                'advice' => $content,
                'helpmenu' => $this->menuDefault()
            )
        );
    }


    /**
     * @Route("/project-types/{page}", name="project-types", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method({"GET", "POST"})
     * Төслүүд
     *
     */
    public function projecttypesAction(Request $request, $page)
    {

        $em = $this->getDoctrine()->getManager();
        $pagesize = 20;
        $searchEntity = new ProjectT();
        $searchform = $this->createForm(new ProjectTSearchType(), $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchform->bind($request);
            $search = true;
        }
        /**@var Content[] $content */
        $qb = $em->getRepository('happyCmsBundle:ProjectT')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('LOWER(n.name) like LOWER(:name)')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
            }
        }

        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();

        $projectt = $qb
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->orderBy('n.order', 'asc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('happyWebBundle:Default:projects-types.html.twig',
            array(
                'id' => 2,
                'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
                'projectt' => $projectt,
                'search' => $search,
                'pagesize' => $pagesize,
                'page' => $page,
                'count' => $count,
                'searchform' => $searchform->createView(),
                'helpmenu' => $this->menuDefault()
            )
        );
    }

    /**
     * @Route("/email", name="email")
     * @Method({"GET", "POST"})
     * Төслүүд
     *
     */
    public function emailsAction(Request $request)
    {
        $email = new EmailBox($this->container);
        $email->sendEmail('Hi', 'gombo29@gmail.com', 'hi');

        var_dump('success');
        exit();
    }


    private function menuDefault()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:ContentPosition')->createQueryBuilder('n');
        $types = $qb
            ->where('n.id > 4')
            ->getQuery()
            ->getArrayResult();

        return $types;
    }


}
