<?php

namespace scrclub\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use scrclub\CMSBundle\Entity\Template;
use scrclub\CMSBundle\Form\TemplateType;

/**
 * Template controller.
 *
 */
class TemplateController extends Controller
{

   public function __construct() {



   }

    /**
     * Lists all Template entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $templates = $em->getRepository('scrclubCMSBundle:Template')->findAll();

        $this->types = array("node", "content");

        return $this->render('scrclubCMSBundle:Template:index.html.twig', array(
            'types'     => $this->types ,
            'templates' => $templates
        ));



    }


    /**
     * Finds and displays a Template entity.
     *
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();

        $templates = $em->getRepository('scrclubCMSBundle:Template')->findAll();

        $query = $em->getRepository('scrclubCMSBundle:Template')->createQueryBuilder('p')

            ->orderBy('p.name', 'ASC')
            ->getQuery();

        $templates = $query->getResult();


        // need to return types and templates

        $types = array("node", "content");



        return $this->render('scrclubCMSBundle:cms:config_templates.html.twig', array(
            'types'     => $types,
            'templates' => $templates
        ));


    }


    public function addAction($id) {

        $this->types = array("node", "content");

        $template  = new Template();

        if (isset($id)) {

            $template = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Template')->find($id);
            if (!$template) {
                throw $this->createNotFoundException('Unable to find Node entity.');
            }

        }


        $form = $this->createForm(new TemplateType($this->types), $template);

        $request = $this->get('request');

        // update if needed
        if ($request->getMethod() == 'POST') {

        $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($template);
                $em->flush();

               // echo $template->getFilename();

                return $this->redirect($this->generateUrl('scrclub_cms_templates'));
            }

        }



        return $this->render('scrclubCMSBundle:cms:addtemplate.html.twig', array(
            'types'     => $this->types,
            'template' => $template,
            'form'   => $form->createView(),
        ));


    }

    /**
     * Displays a form to create a new Template entity.
     *
     */
    public function newAction()
    {
        $entity = new Template();
        $form   = $this->createForm(new TemplateType(), $entity);

        return $this->render('scrclubCMSBundle:Template:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Template entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Template();
        $form = $this->createForm(new TemplateType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('template_show', array('id' => $entity->getId())));
        }

        return $this->render('scrclubCMSBundle:Template:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Template entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('scrclubCMSBundle:Template')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        $editForm = $this->createForm(new TemplateType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('scrclubCMSBundle:Template:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Template entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('scrclubCMSBundle:Template')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Template entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TemplateType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('template_edit', array('id' => $id)));
        }

        return $this->render('scrclubCMSBundle:Template:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Template entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('scrclubCMSBundle:Template')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Template entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('template'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
