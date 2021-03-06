<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Project;
use happy\CmsBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * ProjectT controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     *  Lists all project entities.
     *
     * @Route("/{page}", name="cms_user_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 20;

        $searchEntity = new User();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\UserSearchType', $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:User')->createQueryBuilder('n');

        if ($search) {
            if ($searchEntity->getName() && $searchEntity->getName() != '') {
                $qb->andWhere('n.name like :name')
                    ->setParameter('name', '%' . $searchEntity->getName() . '%');
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
        /**@var User[] $user */
        $user = $qb
            ->orderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();

        return $this->render('happyCmsBundle:User:index.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'search' => $search,
            'user' => $user,
            'searchform' => $searchForm->createView(),
        ));
    }


    /**
     * Show user entity.
     *
     * @Route("/show/{id}", name="cms_user_show" , requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function usershowAction(Request $request, User $user)
    {
        $currentRoute = $request->get('currentRoute');
        return array(
            'user' => $user,
            'currentRoute' => $currentRoute,
        );
    }

    /**
     * Deletes a content entity.
     *
     * @Route("/hide/{id}", name="cms_user_hide", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function hideAction(Request $request, User $user)
    {
        if ($user) {
            $em = $this->getDoctrine()->getManager();

            $user->setEnabled(0);
            $em->persist($user);
            $em->flush();

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'User амжилттай өөрчлөгдлөө!');
        } else {
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'User амжилттай олдсонгүй!');
        }
        return $this->redirectToRoute('cms_user_index');
    }

    /**
     * Deletes a content entity.
     *
     * @Route("/show/{id}", name="cms_user_show", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request, User $user)
    {
        if ($user) {
            $em = $this->getDoctrine()->getManager();

            $user->setEnabled(1);
            $em->persist($user);
            $em->flush();

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'User амжилттай өөрчлөгдлөө!');
        } else {
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'User амжилттай олдсонгүй!');
        }
        return $this->redirectToRoute('cms_user_index');
    }
}