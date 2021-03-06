<?php

namespace happy\CmsBundle\Controller;

use happy\CmsBundle\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Content controller.
 *
 * @Route("/content")
 */
class ContentController extends Controller
{
    /**
     *  Lists all content entities.
     *
     * @Route("/{page}", name="cms_content_index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request, $page)
    {
        $pagesize = 20;

        $searchEntity = new Content();
        $searchForm = $this->createForm('happy\CmsBundle\Form\SearchForm\ContentSearchType', $searchEntity);
        $search = false;
        if ($request->get("submit") == 'submit') {
            $searchForm->bind($request);
            $search = true;
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:Content')->createQueryBuilder('n');

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
        /**@var Content[] $content */
        $content = $qb
            ->leftJoin('n.contentbairlal', 'b')
            ->addSelect('b')
            ->orderBy('n.createdDate', 'desc')
            ->setFirstResult(($page - 1) * $pagesize)
            ->setMaxResults($pagesize)
            ->getQuery()
            ->getArrayResult();
        $currentRoute = $request->getUri();

        return $this->render('happyCmsBundle:Content:index.html.twig', array(
            'pagecount' => ($count % $pagesize) > 0 ? intval($count / $pagesize) + 1 : intval($count / $pagesize),
            'count' => $count,
            'page' => $page,
            'search' => $search,
            'content' => $content,
            'currentRoute' => $currentRoute,
            'searchform' => $searchForm->createView(),
        ));
    }


    /**
     * Creates a new content entity.
     *
     * @Route("/new", name="cms_content_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $currentRoute = $request->get('currentRoute');
        $content = new Content();
        $form = $this->createForm('happy\CmsBundle\Form\ContentType', $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $content->uploadImage($this->container);
            $em->persist($content);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Агуулга амжилттай үүлслээ!');

            return $this->redirectToRoute('cms_content_show', array('id' => $content->getId(), 'currentRoute' => $currentRoute));
        }

        return array(
            'content' => $content,
            'form' => $form->createView(),
        );
    }

    /**
     * Updates content entity.
     *
     * @Route("/update/{id}", name="cms_content_update" , requirements={"id" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function updateAction(Request $request, Content $content)
    {
        $currentRoute = $request->get('currentRoute');
        $deleteForm = $this->createDeleteForm($content);
        $editForm = $this->createForm('happy\CmsBundle\Form\ContentType', $content);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();
            $content->uploadImage($this->container);
            $em->flush();
            $request
                ->getSession()
                ->getFlashBag()
                ->add('success', 'Мэдээ амжилттай засагдлаа!');

            return $this->redirectToRoute('cms_content_update', array('id' => $content->getId(), 'currentRoute' => $currentRoute));
        }

        return array(
            'id' => $content->getId(),
            'banner' => $content,
            'currentRoute' => $currentRoute,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Show content entity.
     *
     * @Route("/show/{id}", name="cms_content_show" , requirements={"id" = "\d+"})
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request, Content $content)
    {
        $deleteForm = $this->createDeleteForm($content);
        $currentRoute = $request->get('currentRoute');
        return array(
            'content' => $content,
            'currentRoute' => $currentRoute,
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Deletes a content entity.
     *
     * @Route("/delete/{id}", name="cms_content_delete", requirements={"id" = "\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Content $content)
    {
        $form = $this->createDeleteForm($content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($content);
            $em->flush();
        }
        $request
            ->getSession()
            ->getFlashBag()
            ->add('success', 'Мэдээ амжилттай устлаа!');
        return $this->redirectToRoute('cms_content_index');
    }


    /**
     * Creates a form to delete a banner entity.
     *
     * @param Content $banner The banner entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Content $banner)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cms_content_delete', array('id' => $banner->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Creates a upload news image with ck.
     *
     * @Route("/new/image/ck", name="cms_news_new_ck_image",requirements={"type" = "\d+"})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function ckimageAction(Request $request)
    {
        $file = null;
        $ckpath = 'News/img/' . date('Y') . '/' . date('m') . '/' . date('d');
        $dir = $this->container->getParameter('statfolder') . $ckpath;
        $staturl = $this->container->getParameter('resources');

        if ($request->files && count($request->files) == 1) {
            $uploadedFile = $request->files->get('upload');
            $name = 'ckimgage' . '-' . date('His') . '-' . rand();
            if (in_array($uploadedFile->getMimeType(), array('image/jpeg', 'image/gif', 'image/png'))) {
                $name = $name . '.' . $uploadedFile->guessExtension();
                $file = $uploadedFile->move($dir, $name);
                $image = '/' . $dir . '/' . $name;

                return array(
                    'image' => $image,
                    'funcnum' => $request->get('CKEditorFuncNum'),
                    'files' => count($request->files)
                );
            }
        }
        throw $this->createNotFoundException('Wrong request.');
    }


}
