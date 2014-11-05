<?php

namespace scrclub\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use scrclub\CMSBundle\Entity\Document;
use scrclub\CMSBundle\Entity\Image;
use scrclub\CMSBundle\Form\DocumentType;

/**
 * Document controller.
 *
 */
class DocumentController extends Controller
{



    /*
     *
     * this is used for media type
     *
     */



    public function setOrderAction () {


        $request = $this->get('request');

        $coucou ="yo";

        if ($request->getMethod() == 'POST') {

            $tree = $request->request->all();

            $em = $this->getDoctrine()->getManager();
            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Media');


            $i = 0;
            foreach ($tree['data'] as $branch) {

                if ($branch['item_id'] != 'null' AND $branch["depth"] >= 1) {

                    $media          = $repo->findOneById($branch['item_id']);

                    if ($media) {


                        $media->setLevel($i);

                        $coucou ="yes";

                        $i++;

                    }
                }
            }

            $em->flush();

        }

        return new Response($coucou);

    }

    public function multiUploadMediaTypeAction ($mediaContentTypeId ) {


        $request        = $this->get('request');
        $files = $request->files;

        foreach ($files as $uploadedFile) {

            $dummyDoc       = new Document();
            $dummyDoc->setFile($uploadedFile);

            $ext            = $dummyDoc->getFile()->guessExtension();
            $filesize       = $dummyDoc->getFile()->getClientSize();
            $originalName   = $dummyDoc->getFile()->getClientOriginalName();


            if($ext == "pdf") {
                $encrypted = $originalName;
            } else {
                $encrypted = md5(mt_rand());
            }

            $em = $this->getDoctrine()->getManager();
            $file = $uploadedFile->move("uploads/documents", $encrypted.'.'.$ext);

            // check if is image
            // create a dummy doc to check if image is valid

            $dummyDoc = new Document();
            $dummyDoc->setPath($encrypted.'.'.$ext);

            $sizes = getimagesize($dummyDoc->getWebPath());


            if ($sizes !== false) {

                $media = new Image();
                $media->setWidth($sizes[0]);
                $media->setHeight($sizes[1]);
                $media->setType('image');

            } else {
                $media = new Document();
                $media->setType('document');

            }

            $media->setExtension($ext);
            $media->setName($originalName);
            $media->setOriginalName($originalName);
            $media->setFileSize($filesize);
            $media->setLevel(0);


            $media->setPath($encrypted.'.'.$ext);
            $em->persist($media);

            // add to mediaContentType

            $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\MediaContentType');
            $mediaContentType = $noderepo->find($mediaContentTypeId);

            $mediaContentType->addMedia($media);
            $mediaContentType->setDescription("");
            $em->persist($mediaContentType);
            $em->flush();


            //add media id to json
            $result['mediacontent_id'] = $mediaContentType->getId();
            $result['media_id'] = $media->getId();
            $result["mediacontenttype_id"] = $mediaContentTypeId;
            $result["media_name"] = $media->getName();
            $result["webpath"] = $media->getWebPath();
            //$result['media_html'] = $this->renderView('scrclubCMSBundle:cms:list_image_mediaset.html.twig', array('media' => $media, 'addPostLink' => true));

            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;

        }


        }

    public function multiUploadAction($mediaset_id) {

        $request = $this->get('request');

        $dummyDoc = new Document();
        $dummyDoc->setFile($request->files->get('qqfile'));

        $ext            = $dummyDoc->getFile()->guessExtension();
        $filesize       = $dummyDoc->getFile()->getClientSize();
        $originalName   = $dummyDoc->getFile()->getClientOriginalName();


        $uploader = $this->container->get('cms_bundle.fineupload');
        $uploader->allowedExtensions = array();
        $uploader->sizeLimit = 10 * 1024 * 1024;
        $uploader->inputName = 'qqfile';

        // If you want to use resume feature for uploader, specify the folder to save parts.
        $uploader->chunksFolder = 'chunks';

        // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
        $result = $uploader->handleUpload(md5(mt_rand()).'.'.$ext);

        // To save the upload with a specified name, set the second parameter.
        // $result = $uploader->handleUpload('uploads/', md5(mt_rand()).'_'.$uploader->getName());
        // To return a name used for uploaded file you can use the following line.
        $result['uploadName'] = $uploader->getUploadName();

        // we need to convert this to an Image

        $em = $this->getDoctrine()->getManager();
        $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\MediaSet');
        $mediaset = $noderepo->find($mediaset_id);


        // check if is image
        // create a dummy doc to check if image is valid

        $dummyDoc = new Document();
        $dummyDoc->setPath($result['uploadName']);

        $sizes = getimagesize($dummyDoc->getWebPath());


        if ($sizes !== false) {

            $media = new Image();
            $media->setWidth($sizes[0]);
            $media->setHeight($sizes[1]);
            $media->setType('image');

        } else {
            $media = new Document();
            $media->setType('document');

        }

        $media->setExtension($ext);
        $media->setName($originalName);
        $media->setOriginalName($originalName);
        $media->setFileSize($filesize);


        $media->setPath($result['uploadName']);

        $media->addMediaSet($mediaset);
        $em->persist($media);

        $mediaset->addMedia($media);

        $em->persist($mediaset);

        $em->flush();


        //add media id to json
        $result['mediaset_id'] = $mediaset->getId();
        $result['media_id'] = $media->getId();


        $result['media_html'] = $this->renderView('scrclubCMSBundle:cms:list_image_mediaset.html.twig', array('media' => $media, 'addPostLink' => true));

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;



    }

    //public function


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


        return new \Symfony\Component\HttpFoundation\Response('');
    }






}
