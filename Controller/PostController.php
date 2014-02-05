<?php

namespace scrclub\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use scrclub\CMSBundle\Entity\Post;
use scrclub\CMSBundle\Form\PostType;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{


    /**
     * Finds and displays a Post entity.
     *
     */
    public function showAction($parent_id)
    {


        // get nodes
        $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');
        $em = $this->getDoctrine()->getManager();

        $node = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->findOneById($parent_id);

        if (!$node) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $result = $node->getChildren();



        $rootTree = $repo->getRootNodes();

        return $this->render('scrclubCMSBundle:cms:posts.html.twig', array(
            "nodeParent" => $node,
            "type" => "post",
            "tree" => $result,
            "rootTree" => $rootTree
        ));



    }

}
