<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Project;
use happy\CmsBundle\Entity\ProjectInvestor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * ProjectT controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     *  Lists all project entities.
     *
     * @Route("/{page}", name="cms_project_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 20;

        $searchEntity = new Project();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\ProjectSearchType', $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Project')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('n.name like :name')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
            }

            if ($searchEntity->getIsRemove() && $searchEntity->getIsRemove() != '') {
                $qb->andWhere('n.isRemove = :data')
                    ->setParameter('data', $searchEntity->getIsRemove());
            }

            if ($searchEntity->getIsSpecial() && $searchEntity->getIsSpecial() != '') {
                $qb->andWhere('n.isSpecial = :data')
                    ->setParameter('data', $searchEntity->getIsSpecial());
            }

            if ($searchForm->get('ehlehDate')->getData()) {
                $qb
                    ->andWhere('n.createdDate > :ehlehDate')
                    ->setParameter('ehlehDate', $searchEntity->getEhlehDate());
            }

            if ($searchForm->get('duusahDate')->getData()) {
                $qb
                    ->andWhere('n.createdDate < :duusahDate')
                    ->setParameter('duusahDate', $searchEntity->getDuusahDate());
            }
        }
        $countQueryBuilder = clone $qb;
        $count = $countQueryBuilder->select('count(n.id)')->getQuery()->getSingleScalarResult();
        /**@var Project[] $project */
        $project = $qb
            ->leftJoin('n.user', 'u')
            ->addSelect('u')
            ->leftJoin('n.projectType', 'p')
            ->addSelect('p')
            ->orderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();
        $currentRoute = $request->getUri();

        return $this->render('happyCmsBundle:Project:index.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'search' => $search,
            'project' => $project,
            'currentRoute' => $currentRoute,
            'searchform' => $searchForm->createView(),
        ));
    }


    /**
     *  Lists all project entities.
     *
     * @Route("/invest/{page}", name="cms_invest_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function investindexAction(Request $request, $page)
    {


        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:ProjectInvestor')->createQueryBuilder('n');


        /**@var ProjectInvestor[] $pi */
        $pi = $qb
            ->leftJoin('n.user', 'u')
            ->addSelect('u')
            ->leftJoin('n.project', 'p')
            ->addSelect('p')
            ->leftJoin('p.user', 'pu')
            ->addSelect('pu')
            ->orderBy('n.id', 'desc')
            ->getQuery()
            ->getArrayResult();

        return $this->render('happyCmsBundle:Project:investindex.html.twig', array(
            'pi' => $pi,

        ));
    }

    /**
     * Deletes a content entity.
     *
     * @Route("/hide/{id}", name="cms_project_hide", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function hideAction(Request $request, Project $project)
    {
        if ($project) {
            $em = $this->getDoctrine()->getManager();

            $project->setIsHide(1);
            $em->persist($project);
            $em->flush();

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project амжилттай өөрчлөгдлөө!');
        } else {
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project амжилттай олдсонгүй!');
        }
        return $this->redirectToRoute('cms_project_index');
    }

    /**
     * Deletes a content entity.
     *
     * @Route("/show/{id}", name="cms_project_shown", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, Project $project)
    {
        if ($project) {
            $em = $this->getDoctrine()->getManager();

            $project->setIsHide(0);
            $em->persist($project);
            $em->flush();

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project амжилттай өөрчлөгдлөө!');
        } else {
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project амжилттай олдсонгүй!');
        }
        return $this->redirectToRoute('cms_project_index');
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/special_yes/{id}", name="cms_project_special_yes", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function specialYesAction(Request $request, Project $project)
    {
        if ($project) {
            $em = $this->getDoctrine()->getManager();

            $project->setIsSpecial(1);
            $em->persist($project);
            $em->flush();

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project амжилттай өөрчлөгдлөө!');
        } else {
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project амжилттай олдсонгүй!');
        }
        return $this->redirectToRoute('cms_project_index');
    }

    /**
     * Deletes a content entity.
     *
     * @Route("/special_no/{id}", name="cms_project_special_no", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function specialNoAction(Request $request, Project $project)
    {
        if ($project) {
            $em = $this->getDoctrine()->getManager();

            $project->setIsSpecial(0);
            $em->persist($project);
            $em->flush();

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project амжилттай өөрчлөгдлөө!');
        } else {
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project амжилттай олдсонгүй!');
        }
        return $this->redirectToRoute('cms_project_index');
    }
}