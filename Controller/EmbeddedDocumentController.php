<?php

    namespace scrclub\CMSBundle\Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use scrclub\CMSBundle\Entity\EmbeddedDocument;
    use scrclub\CMSBundle\Form\EmbeddedDocumentType;
    use scrClub\CMSBundle\Service\EmbeddedUtils;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Form\FormError;


    class EmbeddedDocumentController extends Controller {



        /*
        public function testAction () {


            $embedUtils = new \scrClub\CMSBundle\Service\EmbeddedUtils();
            //$type = $embedUtils->guessService("http://vimeo.com/31977188");

            $doc = new EmbeddedDocument();
            $doc->setUrl("https://soundcloud.com/compost");

            $embedUtils = new \scrClub\CMSBundle\Service\EmbeddedUtils();
            $type = $embedUtils->guessService($doc->getUrl());

            if($type != 'file' AND $type != 'image') {
                $embedId = $embedUtils->getIdFromUrl($doc->getUrl(), $type);
                $doc->setEmbedId($embedId);
            }

            $doc->setEmbedType($type);

            $json =  file_get_contents("http://soundcloud.com/oembed?format=json&url=".$doc->getUrl()."&iframe=true");
            $result = json_decode($json);



            return $this->render('scrclubCMSBundle:cms:test.html.twig', array(
                'document' => $doc,
                ));

        }

        */




        public function updateAction($mediasetId, $id) {

            $response = null;


            $em = $this->getDoctrine()->getManager();

            $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\MediaSet');
            $mediaset = $noderepo->find($mediasetId);

            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\EmbeddedDocument');

            $document = new EmbeddedDocument();

            if (isset($id)) {

                $document = $repo->find($id);
                if (!$document) {
                    throw $this->createNotFoundException('Unable to find Document.');
                }


            }

            if (!$mediaset) {
                throw $this->createNotFoundException('Unable to find mediaset.');
            }

            $form = $this->createForm(new EmbeddedDocumentType(), $document);
            $request = $this->get('request');

            $view = 'scrclubCMSBundle:cms:updateEmbedPopover.html.twig';


            // update if needed
            if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {

                    // parse URLs and all that jazz

                    $url = $form["url"]->getData();

                    $embedUtils = new EmbeddedUtils();
                    $type = $embedUtils->guessService($url);

                    // if we did not guess the type go back to the add view

                    if(!$type) {

                        $form->get('url')->addError(new FormError('Could not guess file type'));


                        if($request->isXmlHttpRequest()) {

                            $result['html'] =  $this->renderView($view, array(
                                'mediaset' => $mediaset,
                                'document' => $document,
                                'form' => $form->createView()));

                        } else {

                            $response =  $this->render($view, array(
                                'mediaset' => $mediaset,
                                'document' => $document,
                                'form' => $form->createView()));

                        }


                    }

                    if($type != 'file' AND $type != 'image') {
                        $embedId = $embedUtils->getIdFromUrl($url, $type);
                        $document->setEmbedId($embedId);
                    }

                    $document->setEmbedType($type);
                    $document->setType('embedded');

                    //if(!$document->getMediaSets()->contains($mediaset))
                        $document->addMediaSet($mediaset);

                    $em->persist($document);

                    if(!$mediaset->getMedias()->contains($document))
                        $mediaset->addMedia($document);

                    $em->persist($mediaset);
                    $em->flush();

                    // add html for cms in the json
                    if($request->isXmlHttpRequest())
                        $result['media_html'] = $this->renderView('scrclubCMSBundle:cms:list_image_mediaset.html.twig', array('media' => $document, 'mediaset' => $mediaset));


                } else {
                    //var_dump($form->getErrors());
                }
            }




            if($request->isXmlHttpRequest()) {

                $result['media_html'] = $this->renderView('scrclubCMSBundle:cms:list_image_mediaset.html.twig', array('media' => $document, 'mediaset' => $mediaset));


                if(empty($result['html'])) {

                $result['html'] =  $this->renderView($view, array(
                    'mediaset' => $mediaset,
                    'document' => $document,
                    'form' => $form->createView()));

                }

                //documentId
                $result['documentId'] = $document->getId();
                $result['mediasetId'] = $mediaset->getId();


                $response = new Response(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');


            } else {

                if(!$response) {

                $response =  $this->render($view, array(
                    'mediaset' => $mediaset,
                    'document' => $document,
                    'form' => $form->createView()));

                }





            }

            // handle ajax stuff


            return $response;

        }




    }