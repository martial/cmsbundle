<?php

namespace scrclub\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use scrclub\CMSBundle\Entity\Node;
use scrclub\CMSBundle\Form\NodeType;

use scrclub\CMSBundle\Entity\Langs;

/**
 * Node controller.
 *
 */
class NodeController extends Controller
{
    /**
     * Lists all Node entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('scrclubCMSBundle:Node')->findAll();

        return $this->render('scrclubCMSBundle:Node:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Node entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('scrclubCMSBundle:Node')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('scrclubCMSBundle:Node:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }







}
