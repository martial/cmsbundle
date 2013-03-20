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


}
