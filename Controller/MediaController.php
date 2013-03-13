<?php

    namespace scrclub\CMSBundle\Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use scrclub\CMSBundle\Entity\Document;
    use scrclub\CMSBundle\Entity\Media;
    use scrclub\CMSBundle\Form\DocumentType;
    use scrclub\CMSBundle\Form\MediaType;
    use scrclub\CMSBundle\Form\ImageType;

    use Symfony\Component\HttpFoundation\Response;

    /**
     * Document controller.
     *
     */


    class MediaController extends Controller
    {


        public function updateAction($id) {

            $request = $this->get('request');

            $image  = new Media();

            if (isset($id)) {

                $image = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Media')->find($id);

                if (!$image) {
                    throw $this->createNotFoundException('Unable to find Node entity.');
                }

            }

            if($image->getType() == "image")
                $mediaType = new ImageType();
            else if($image->getType() == "embedded")
                $mediaType = new \scrclub\CMSBundle\Form\EmbeddedDocumentType();
            else
                $mediaType = new DocumentType();

            $form = $this->createForm($mediaType, $image);



            // update if needed
            if ($request->getMethod() == 'POST') {



                $form->bind($request);

                if ($form->isValid()) {

                    if($image->getType() != "embedded") {

                        // here we need to check if the type differs


                        $image->upload();

                    }

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($image);
                    $em->flush();


                    if($request->isXmlHttpRequest()) {


                        $result['mediaId'] =  $id;
                        $result['status'] =  "success";



                        $response = new Response(json_encode($result));
                        $response->headers->set('Content-Type', 'application/json');


                    }


                } else {



                }

            }



            $view = "scrclubCMSBundle:cms:updateImagePopover.html.twig";


            if($request->isXmlHttpRequest()) {

                if(empty($result['html'])) {

                    $result['html'] =  $this->renderView($view, array(
                        //'mediaset' => $mediaset,
                        'image' => $image,
                        'form' => $form->createView()));

                }


                $response = new Response(json_encode($result));
                $response->headers->set('Content-Type', 'application/json');


            } else {

                $response =  $this->render($view, array(
                    'image' => $image,
                    'form' => $form->createView()));

            }

            // handle ajax stuff


            return $response;


        }


        public function addNodeToDocumentAction ($nodeId, $mediaId) {


            $em = $this->getDoctrine()->getManager();
            $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\Node');
            $node = $noderepo->find($nodeId);

            $mediarepo = $em->getRepository('scrclub\CMSBundle\Entity\Media');
            $media = $mediarepo->find($mediaId);

            //

            $mediaNode = new \scrclub\CMSBundle\Entity\MediaNode();
            $mediaNode->setNode($node);
            $mediaNode->setMedia($media);
            $mediaNode->setLevel(0);
            $em->persist($mediaNode);

            $node->addMediaNode($mediaNode);
            $media->addMediaNode($mediaNode);

            $em->persist($node);
            $em->persist($media);

            $em->flush();

            return $this->render('scrclubCMSBundle:cms:list_image.html.twig', array('mediaNode' => $mediaNode));

        }

        /*

        public function uploadAction($mediaset_id)
        {


            // find media set

            $em = $this->getDoctrine()->getManager();
            $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\MediaSet');
            $mediaset = $noderepo->find($mediaset_id);


            $document = new Document();
            $form = $this->createFormBuilder($document)
                ->add('name')
                ->add('file')
                ->getForm()
            ;

            if ($this->getRequest()->isMethod('POST')) {
                $form->bind($this->getRequest());
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();

                    $em->persist($document);
                    $em->flush();

                    //$this->redirect($this->generateUrl(...));
                }
            }

            return array('form' => $form->createView());
        }

        */


        /**
         * Deletes a media entity.
         *
         */
        public function deleteAction( $id)
        {

            $request = $this->get('request');


            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('scrclubCMSBundle:Media')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Document entity.');
            }

            if($entity->getType() != "embedded")
                $entity->removeUpload();

            $em->remove($entity);
            $em->flush();


            return new Response('');
        }


        //TODO put in medianode controller or media controller
        public function removeNodeToDocumentAction ($mediaNodeId) {

            $em = $this->getDoctrine()->getManager();
            $mediaNodeRepo = $em->getRepository('scrclub\CMSBundle\Entity\MediaNode');
            $mediaNode = $mediaNodeRepo->find($mediaNodeId);

            $em->remove($mediaNode);

            $em->flush();

            return new Response('');

        }



        public function updateMediaOrderAction() {

            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {

                $data = $request->request->all();

                $em = $this->getDoctrine()->getManager();

                $level = 0;
                foreach ($data['data'] as $nodeMediaId) {

                    $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\MediaNode');
                    $mediaNode = $repo->findOneById($nodeMediaId);
                    $mediaNode->setLevel($level);
                    $em->persist($mediaNode);
                    $level++;

                }

                $em->flush();

            }

            return new Response('');

        }


        public function getMediaNodeAjaxAction($mediaNodeId) {

            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\MediaNode');
            $mediaNode = $repo->findOneById($mediaNodeId);

            return $this->render('scrclubCMSBundle:cms:list_image.html.twig', array('mediaNode' => $mediaNode));

        }

        public function getMediaAjaxAction($mediaId) {

            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Media');
            $media = $repo->findOneById($mediaId);


            return $this->render('scrclubCMSBundle:cms:list_image_mediaset.html.twig', array('media' => $media));

        }







    }
