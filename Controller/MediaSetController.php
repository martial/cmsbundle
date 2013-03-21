<?php

namespace scrclub\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use scrclub\CMSBundle\Entity\MediaSet;
use scrclub\CMSBundle\Form\MediaSetType;
use Symfony\Component\HttpFoundation;


class MediaSetController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('scrclubCMSBundle:MediaSet')->findAll();

        return $this->render('scrclubCMSBundle:cms:mediasets.html.twig', array(
            'mediasets' => $entities,
        ));
    }

    public function addAction ($id) {


        $mediaset  = new MediaSet();

        if (isset($id)) {

            $mediaset = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Mediaset')->find($id);
            if (!$mediaset) {
                throw $this->createNotFoundException('Unable to find Node entity.');
            }

        }


        $form = $this->createForm(new MediaSetType(), $mediaset);

        $request = $this->get('request');

        // update if needed
        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($mediaset);
                $em->flush();

                // echo $template->getFilename();

                return $this->redirect($this->generateUrl('scrclub_cms_mediaset'));
            }

        }

        return $this->render('scrclubCMSBundle:cms:mediaset_add.html.twig', array(
            'mediaset' => $mediaset,
            'form'   => $form->createView(),
        ));



    }


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

        return new Response('');

    }


    function deleteAction($id) {

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {

            $tree = $request->request->all();

            $em = $this->getDoctrine()->getManager();
            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\MediaSet');

            $mediaset = $repo->find($id);

            $em->remove($mediaset);
            $em->flush();

        }

        return new Response('');

    }





}
