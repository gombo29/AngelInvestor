<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\ProjectT;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * ProjectT controller.
 *
 * @Route("/projecttype")
 */
class ProjectTController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/", name="cms_project_type_index")
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:ProjectT')->createQueryBuilder('n');

        /**@var ProjectT[] $content */
        
        $projt = $qb
            ->where('n.isDelete = 0')
            ->orderBy('n.order', 'asc')
            ->getQuery()
            ->getArrayResult();
        $currentRoute = $request->getUri();

        return $this->render('happyCmsBundle:ProjectT:index.html.twig', array(
            'projt' => $projt,
            'currentRoute' => $currentRoute,
        ));
    }


    /**
     * Creates a new banner entity.
     *
     * @Route("/new", name="cms_project_type_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $currentRoute = $request->get('currentRoute');
        $banner = new ProjectT();
        $form = $this->createForm('happy\CmsBundle\Form\ProjectTType', $banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $banner->uploadImage($this->container);
            $em->persist($banner);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Төслийн төрөл амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_project_type_show', array('id' => $banner->getId(), 'currentRoute' => $currentRoute));
        }

        return array(
            'content' => $banner,
            'form' => $form->createView(),
        );
    }


    /**
     * Updates banner entity.
     *
     * @Route("/update/{id}", name="cms_project_type_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, ProjectT $banner)
    {
        $currentRoute = $request->get('currentRoute');
        $deleteForm = $this->createDeleteForm($banner);
        $editForm = $this->createForm('happy\CmsBundle\Form\ProjectTType', $banner);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $banner->uploadImage($this->container);
            $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();

            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Төслийн төрөл амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_project_type_update', array('id' => $banner->getId(), 'currentRoute' => $currentRoute));
        }

        return array(
            'img' => $banner->getImg(),
            'id' => $banner->getId(),
            'banner' => $banner,
            'currentRoute' => $currentRoute,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Show banner entity.
     *
     * @Route("/show/{id}", name="cms_project_type_show" , requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request, ProjectT $banner)
    {
        $deleteForm = $this->createDeleteForm($banner);
        $currentRoute = $request->get('currentRoute');
        return array(
            'banner' => $banner,
            'currentRoute' => $currentRoute,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to delete a banner entity.
     *
     * @param ProjectT $banner The banner entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProjectT $banner)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cms_project_type_delete', array('id' => $banner->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Deletes a content entity.
     *
     * @Route("/delete/{id}", name="cms_project_type_delete", requirements={"id" = "\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProjectT $banner)
    {
        $form = $this->createDeleteForm($banner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($banner);
            $em->flush();
        }
        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Төслийн төрөл амжилттай устлаа!');
        return $this->redirectToRoute('cms_project_type_index');
    }

    /**
     * Updates banner entity.
     *
     * @Route("/changeorder", name="cms_project_type_order" )
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function changeorderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ids = $request->request->get('ids');
        foreach ($ids as $index => $alauid) {
            $qb = $em->getRepository('happyCmsBundle:ProjectT')->createQueryBuilder('p');
            $entity = $qb
                ->where('p.id = :id')
                ->setParameter('id', $alauid)
                ->getQuery()
                ->getSingleResult();
            if (!$entity) continue;
            $entity->setOrder($index);
            $em->persist($entity);
        }
        $em->flush();
        return new JsonResponse(array(
            'status' => 'success',
        ));
    }

    /**
     * Deletes a content entity.
     *
     * @Route("/special_yes/{id}", name="cms_projectt_special_yes", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function specialYesAction(Request $request, ProjectT $project)
    {
        if ($project) {
            $em = $this->getDoctrine()->getManager();

            $project->setIsSpecial(1);
            $em->persist($project);
            $em->flush();

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project type амжилттай өөрчлөгдлөө!');
        } else {
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project type амжилттай олдсонгүй!');
        }
        return $this->redirectToRoute('cms_project_type_index');
    }

    /**
     * Deletes a content entity.
     *
     * @Route("/special_no/{id}", name="cms_projectt_special_no", requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     */
    public function specialNoAction(Request $request, ProjectT $project)
    {
        if ($project) {
            $em = $this->getDoctrine()->getManager();

            $project->setIsSpecial(0);
            $em->persist($project);
            $em->flush();

            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project type амжилттай өөрчлөгдлөө!');
        } else {
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Project type амжилттай олдсонгүй!');
        }
        return $this->redirectToRoute('cms_project_type_index');
    }
}
