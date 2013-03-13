<?php

namespace scrclub\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use scrclub\CMSBundle\Entity\MediaSet;
use scrclub\CMSBundle\Form\MediaSetType;

/**
 * MediaSet controller.
 *
 * @Route("/mediaset")
 */
class MediaSetController extends Controller
{



    public function addMediaAction($mediasetId, $mediaId) {


        $em = $this->getDoctrine()->getManager();
        $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\MediaSet');
        $mediaset = $noderepo->find($mediasetId);

        $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\Document');
        $media = $noderepo->find($mediaId);

        $medias = $mediaset->getMedias();

        if(!$medias->contains($media)) {
            $mediaset->addMedia($media);
            $em->persist($mediaset);
            $em->flush();
        }

        return new \Symfony\Component\HttpFoundation\Response('');

    }

    /**
     * Lists all MediaSet entities.
     *
     * @Route("/", name="mediaset")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('scrclubCMSBundle:MediaSet')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a MediaSet entity.
     *
     * @Route("/{id}/show", name="mediaset_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('scrclubCMSBundle:MediaSet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaSet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new MediaSet entity.
     *
     * @Route("/new", name="mediaset_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MediaSet();
        $form   = $this->createForm(new MediaSetType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new MediaSet entity.
     *
     * @Route("/create", name="mediaset_create")
     * @Method("POST")
     * @Template("scrclubCMSBundle:MediaSet:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new MediaSet();
        $form = $this->createForm(new MediaSetType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mediaset_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing MediaSet entity.
     *
     * @Route("/{id}/edit", name="mediaset_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('scrclubCMSBundle:MediaSet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaSet entity.');
        }

        $editForm = $this->createForm(new MediaSetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing MediaSet entity.
     *
     * @Route("/{id}/update", name="mediaset_update")
     * @Method("POST")
     * @Template("scrclubCMSBundle:MediaSet:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('scrclubCMSBundle:MediaSet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MediaSet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new MediaSetType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mediaset_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a MediaSet entity.
     *
     * @Route("/{id}/delete", name="mediaset_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('scrclubCMSBundle:MediaSet')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find MediaSet entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('mediaset'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
