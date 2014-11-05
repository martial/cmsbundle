<?php

    namespace scrclub\CMSBundle\Controller;

    use Doctrine\Common\Collections\ArrayCollection;
    use scrclub\CMSBundle\Entity\BooleanContentType;
    use scrclub\CMSBundle\Entity\Config;
    use scrclub\CMSBundle\Entity\DateContentType;
    use scrclub\CMSBundle\Entity\GMapData;
    use scrclub\CMSBundle\Entity\Media;
    use scrclub\CMSBundle\Entity\MediaContentType;
    use scrclub\CMSBundle\Entity\TextContentType;
    use scrclub\CMSBundle\Form\TextContentTypeType;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use scrclub\CMSBundle\Entity\Node;
    use scrclub\CMSBundle\Entity\Post;

    use scrclub\CMSBundle\Form\NodeType;
    use scrclub\CMSBundle\Form\PostType;

    use Symfony\Component\HttpFoundation\Response;

    use Symfony\Component\Validator\Constraints as Assert;


    class NodesController extends Controller {

        /*
        *
        * Display list of the nodes
        *
        */

        public function indexAction() {


            // get nodes
            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');
            $em = $this->getDoctrine()->getManager();

            $query = $repo->getRootNodesQueryBuilder();
            //$query->andWhere("node.type = 'node'");
            $result = $query->getQuery()->getResult();

            // add config

            $config = NULL;
            $configs = $em->getRepository('scrclubCMSBundle:Config')->findAll();
            if (isset($configs[0])) {
                $config = $configs[0];
            }


            return $this->render('scrclubCMSBundle:cms:index.html.twig', array("type" => "node", "tree" => $result, "config" => $config));

        }

        public function preAddNodeAction ($type, $parent_id) {

            $em = $this->getDoctrine()->getManager();

            $lang_repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Langs');
            $langs = $lang_repo->findAll();
            $defaultLocale = $lang_repo->getDefaultLocale($langs);

            if($type == "node") {
                $node = new Node();
                $node->setType('node');
                $node_parent = NULL;
            }

            if($type == "post") {
                $node = new Post();
                $node->setType('post');

                $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\Node');
                $node_parent = $noderepo->find($parent_id);
                $node->setParent($node_parent);
            }

            $node->setTranslatableLocale($defaultLocale->getLocale());
            //$this->checkContentTypes($node);






            $request = $this->getRequest();

            $query = $em->getRepository('scrclubCMSBundle:Template')->createQueryBuilder('p')//->where('type', 'node')
                ->orderBy('p.name', 'ASC')->getQuery();

            $templates = $query->getResult();

            $defaultTemplate = null;
            $parent = $node->getParent();
            if ( isset( $parent ) ){
                $defaultTemplate = $parent->getTemplateDefaultChild();
            };

            $form = $this->createForm(new NodeType($lang_repo, $templates, $defaultTemplate), $node);

            // update if needed
            if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {


                    $node->setSlug('');
                    $em->persist($node);
                    $em->flush();

                    if($node_parent) {
                        $noderepo->moveUp($node, $node_parent->getChildren()->count());
                    }

                    $node->setFullSlug( $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->generateFullSlug($node));
                    $em->persist($node);
                    $em->flush();


                    if ($node->getType() == "post")
                        return $this->redirect($this->generateUrl('scrclub_cms_addpost', array('id' => $node->getId(), 'parent_id' => $parent->getId())));

                    return $this->redirect($this->generateUrl('scrclub_cms_addnode', array('id' => $node->getId())));
                    // success
                    // return $this->redirect($this->generateUrl('scrclub_cms_addnode', array('id' => $node->getId())));
                }
            }


            //return $this->redirect($this->generateUrl('scrclub_cms_addnode', array('id' => $node->getId())));
            return $this->render('scrclubCMSBundle:cms:preaddnode.html.twig', array('node' => $node, 'form'=> $form->createView(), 'langs' => $langs,  'templates' => $templates, 'parent' => $node_parent));


        }

        public function preAddPostAction () {

        }


        public function addNodeAction($id) {

            // get langs

            $em = $this->getDoctrine()->getManager();

            $lang_repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Langs');
            $langs = $lang_repo->findAll();

            $node = new Node();
            $node->setType('node');
            $node->setGMapData(new GMapData());

            $request = $this->getRequest();
            $locale = $request->getLocale();


            //$templates = $em->getRepository('scrclubCMSBundle:Template')->findAll();

            $query = $em->getRepository('scrclubCMSBundle:Template')->createQueryBuilder('p')//->where('type', 'node')
                ->orderBy('p.name', 'ASC')->getQuery();

            $templates = $query->getResult();

            if (isset($id)) {

                $node = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->find($id);

                if (!$node) throw $this->createNotFoundException('Unable to find Node entity.');
                $defaultLocale = $lang_repo->getDefaultLocale($langs);

                $node->setTranslatableLocale($defaultLocale->getLocale());


                //$node->setTranslatableLocale($locale);

            }

            $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->getMediaSetsRecursive($node);

            $defaultTemplate = null;
            $parent = $node->getParent();
            if ( isset( $parent ) ){
                $defaultTemplate = $parent->getTemplateDefaultChild();
            };


            $this->checkContentTypes($node);
            $contentTypes = $this->setContentTypeOrder($node);



            $form = $this->createForm(new NodeType($lang_repo, $templates, $defaultTemplate), $node);


            $request = $this->get('request');

            // update if needed
            if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {


                    $node->setSlug('');
                    $em->persist($node);
                    $em->flush();

                    // we must re-update to get correct slug. wtf ?

                    $node->setFullSlug( $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->generateFullSlug($node));
                    $em->persist($node);
                    $em->flush();


                    // update categories ?




                    foreach ( $node->getCategories() as $category ) {

                        $category->addNode($node);
                        $em->persist($node);
                        $em->flush();

                    }

                    // and reverse
                   // return $this->redirect($this->generateUrl('scrclub_cms_addnode', array('id' => $node->getId())));
                }
            }


            return $this->render('scrclubCMSBundle:cms:addnode.html.twig', array('node' => $node, 'form'=> $form->createView(), 'langs' => $langs, 'locale' => $locale, 'templates' => $templates, 'contentTypes' => $contentTypes));

        }

        public function updateTreeAction($depth) {

            $request = $this->get('request');

            $coucou ="yo";

            if ($request->getMethod() == 'POST') {

                $tree = $request->request->all();

                $em = $this->getDoctrine()->getManager();
                $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');


                foreach ($tree['data'] as $branch) {

                    if ($branch['item_id'] != 'null' AND $branch["depth"] >= $depth) {

                        $node       = $repo->findOneById($branch['item_id']);
                        $nodeParent = $repo->findOneById($branch['parent_id']);

                        if ($node) {

                            if ($nodeParent) $node->setParent($nodeParent);
                                else
                            $node->setParent(null);

                            $node->setLft($branch['left']);
                            $node->setRgt($branch['right']);
                            $node->setSlug(NULL);
                            $em->persist($node);

                            $coucou ="yes";

                        }
                    }
                }

                $em->flush();

            }

            $this->regenerateFullSlugs();

            return new Response($coucou);

        }

        function regenerateFullSlugs() {

            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository('scrclub\CMSBundle\Entity\Node');
            $nodes = $repo->findAll();

            foreach ($nodes as $node) {

                $node->setFullSlug($repo->generateFullSlug($node));
                $em->persist($node);
            }

            $em->flush();

        }


        function deleteAction() {

            $request = $this->get('request');
            if ($request->getMethod() == 'POST') {

                $tree = $request->request->all();

                $em = $this->getDoctrine()->getManager();
                $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');

                $node = $repo->find($tree['data']);

                $em->remove($node);
                $em->flush();

            }

            return new Response('');

        }


        function updateActiveAction() {

            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {

                $tree = $request->request->all();

                $em = $this->getDoctrine()->getManager();
                $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');

                $node = $repo->find($tree['id']);

                $node->setActive($tree['active'] === 'true' ? 1 : 0);
                $em->persist($node);
                $em->flush();

            }

            return new Response('');


        }

        /*
         *
         *
         */

        public function addPostAction($parent_id, $id) {

            // get langs

            //$translatableListener = $this->get('stof_doctrine_extensions.listener.translatable');
            //$translatableListener->setTranslatableLocale($translatableListener->getDefaultLocale());

            $em = $this->getDoctrine()->getManager();
            $lang_repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Langs');
            $langs = $lang_repo->findAll();

            $post = new Post();
            $post->setType('post');
            $post->setGMapData(new GMapData());

            // if post is new add automatically text contents
            $config = NULL;
            $configs = $em->getRepository('scrclubCMSBundle:Config')->findAll();
            if ( isset($configs[0]) ) {
                $config = $configs[0];
            }



            $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\Node');
            $node_parent = $noderepo->find($parent_id);
            $post->setParent($node_parent);

            //$post->setTemplate($node_parent->getTemplate());

            $request = $this->getRequest();
            $locale = $request->getLocale();

            $originalCategories = array();

            if (isset($id)) {

                $post = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Post')->find($id);
                $defaultLocale = $lang_repo->getDefaultLocale($langs);

                $post->setTranslatableLocale($defaultLocale->getLocale());

                // get categories before
                $originalCategories = $post->getCategories();

            }

            $noderepo->getMediaSetsRecursive($post);

            $noderepo->getFieldsRecursive($post);



            // content types

            $this->checkContentTypes($post);
            $contentTypes = $this->setContentTypeOrder($post);

            foreach($post->getTextContent() as $textContent) {
                $textContent->setTranslatableLocale($defaultLocale->getLocale());
            }


            // text contents, old..
            // to be removed at some point

            /*

            if($config) {

                foreach ($config->getContentTypeConfigs() as $contentConfig) {

                    $exists = false;
                    foreach( $post->getTextContent() as $textContent ) {
                       if ($textContent->getType() ==  $contentConfig->getType() AND $textContent->getName() == $contentConfig->getName())
                           $exists = true;
                    }

                    if(!$exists) {
                        if($contentConfig->getType() == "text") {
                            $newTextContent = new TextContentType();
                            $newTextContent->setName($contentConfig->getName());
                            $newTextContent->setType($contentConfig->getType());
                            $post->addTextContent($newTextContent);
                        }
                    }
                }
            }

            */




            $form = $this->createForm(new PostType($lang_repo, array(), $node_parent->getTemplateDefaultChild() ), $post);


            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $post->setSlug('');
                    $post->setType('post');
                    $em->persist($post);

                    // -------------------------------------------------- manage categories

                    $submittedCategories = $post->getCategories();

                    if(count($submittedCategories) == 0 ) {
                        foreach($originalCategories as $category) {
                            $category->removeNode($post);
                        }
                    }

                    foreach($originalCategories as $category) {
                        if(!$submittedCategories->contains($category)) {
                            $category->removeNode($post);
                            $em->persist($category);
                        }
                    }

                    // finally add
                    if(!empty($submittedCategories)) {
                        foreach ($submittedCategories as $category ) {
                            $category->addNode($post);
                            $em->persist($category);
                        }
                    }

                    // clean nodes for real
                    foreach( $submittedCategories as $category ) {

                        foreach($category->getNodes() as $node) {

                            if(!$node->getCategories()->contains($category)) {
                                $category->removeNode($node);
                                $em->persist($category);
                            }

                        }

                    }



                    $em->flush();

                    return $this->redirect($this->generateUrl('scrclub_cms_addpost', array('parent_id' => $parent_id, 'id' => $post->getId())));

                } else {

                    echo "Error";

                }
            }


            // tree

            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');
            $result = $repo->getRootNodes();


            return $this->render('scrclubCMSBundle:cms:addnode.html.twig', array('node' => $post,
                                                                                 'form'=> $form->createView(),
                                                                                 'langs' => $langs,
                                                                                 'locale' => $locale,
                                                                                 'parent_id' => $parent_id,
                                                                                 'tree' => $result,
                                                                                    'contentTypes' => $contentTypes
                                                                                ));

        }


        private function setContentTypeOrder (Node &$node) {

            $result = new ArrayCollection();

            foreach ($node->getContentTypeConfigs() as $contentConfig) {

                $name = $contentConfig->getName();
                $type = $contentConfig->getType();

                foreach( $node->getTextContent() as $content ) {
                    if($name == $content->getName() AND $type == $content->getType()) {
                        $content->setOrder($contentConfig->getOrder());
                        $result->add($content);
                    }
                }

                foreach( $node->getMediaContent() as $content ) {
                    if($name == $content->getName() AND $type == $content->getType()) {
                        $content->setOrder($contentConfig->getOrder());
                        $result->add($content);
                    }
                }


                foreach( $node->getBooleanContent() as $content ) {
                    if($name == $content->getName() AND $type == $content->getType()) {
                        $content->setOrder($contentConfig->getOrder());
                        $result->add($content);
                    }

                }

                foreach( $node->getDateContent() as $content ) {
                    if($name == $content->getName() AND $type == $content->getType()) {
                        $content->setOrder($contentConfig->getOrder());
                        $result->add($content);
                    }

                }

            }

            // and then sort by order
            $iterator = $result->getIterator();

        // define ordering closure, using preferred comparison method/field
            $iterator->uasort(function ($first, $second) {
                return (int) $first->getOrder() > (int) $second->getOrder() ? 1 : -1;
            });

            return $iterator;





        }


        private function checkContentTypes (Node &$node) {

            foreach ($node->getContentTypeConfigs() as $contentConfig) {



                // here we need to check
                // (a) do the contentConfig has category ?
                // (b) if yes - check if the node has a category in common
                // (c) if yes - check if exists as usual - if not skip because you'll not need it

                $bIsCheckable = true;

                $contentConfigCategories = $contentConfig->getCategories();
                if(!$contentConfigCategories->count() == 0 ) {
                    $bIsCheckable = false;
                    foreach($contentConfigCategories as $cat) {
                        if ($node->getCategories()->contains($cat))
                            $bIsCheckable = true;
                        // ok
                    }
                }

                if($bIsCheckable) {

                    $exists = false;
                    foreach( $node->getTextContent() as $textContent ) {


                        if (  $textContent->getType() ==  $contentConfig->getType() AND $textContent->getName() == $contentConfig->getName()) {
                            $exists = true;
                        }

                    }

                    if(!$exists) {
                        if($contentConfig->getType() == "text") {
                            $newTextContent = new TextContentType();

                            $newTextContent->setName($contentConfig->getName());
                            $newTextContent->setType($contentConfig->getType());
                            $node->addTextContent($newTextContent);
                        }



                    }


                    $exists = false;
                    foreach( $node->getBooleanContent() as $booleanContent ) {


                        if (  $booleanContent->getType() ==  $contentConfig->getType() AND $booleanContent->getName() == $contentConfig->getName()) {
                            $exists = true;
                        }

                    }

                    if(!$exists) {
                        if($contentConfig->getType() == "bool") {
                            $newBoolContent = new BooleanContentType();
                            $newBoolContent->setName($contentConfig->getName());
                            $newBoolContent->setType($contentConfig->getType());
                            $node->addBooleanContent($newBoolContent);
                        }



                    }

                    $exists = false;
                    foreach( $node->getDateContent() as $dateContent ) {


                        if (  $dateContent->getType() ==  $contentConfig->getType() AND $dateContent->getName() == $contentConfig->getName()) {
                            $exists = true;
                        }

                    }

                    if(!$exists) {
                        if($contentConfig->getType() == "date") {
                            $newDateContent = new DateContentType();
                            $newDateContent->setName($contentConfig->getName());
                            $newDateContent->setType($contentConfig->getType());
                            $node->addDateContent($newDateContent);
                        }

                    }


                    $exists = false;
                    foreach( $node->getMediaContent() as $mediaContent ) {


                        if (  $mediaContent->getType() ==  $contentConfig->getType() AND $mediaContent->getName() == $contentConfig->getName()) {
                            $exists = true;
                        }

                    }

                    if(!$exists) {
                        if($contentConfig->getType() == "media") {

                            $newBoolContent = new MediaContentType();
                            $newBoolContent->setName($contentConfig->getName());
                            $newBoolContent->setDescription("hello");
                            $newBoolContent->setType($contentConfig->getType());
                            $node->addMediaContent($newBoolContent);

                            $em = $this->getDoctrine()->getManager();

                            $em->persist($newBoolContent);
                            $em->flush();
                        }



                    }

                }

            }

            // check for removes

            // if there's no category in common, delete the wrong ones
            foreach ($node->getContentTypeConfigs() as $contentConfig) {
                $contentConfigCategories = $contentConfig->getCategories();
                if(!$contentConfigCategories->count() == 0 ) {
                    foreach($contentConfigCategories as $cat) {
                        if (!$node->getCategories()->contains($cat)) {

                            // post des not contains category in common !
                            // remove incriminated ones !
                            // kill !

                            foreach( $node->getTextContent() as $textContent ) {
                                if (  $textContent->getType() ==  $contentConfig->getType()) {
                                    // bang
                                    $node->removeTextContent($textContent);
                                }
                            }
                        }
                    }
                }
            }



            // here just check if there's no name cohesion
            foreach( $node->getTextContent() as $textContent ) {

                $name = $textContent->getName();
                $exists = false;
                foreach ($node->getContentTypeConfigs() as $contentConfig) {
                    if($name == $contentConfig->getName()  AND $textContent->getType() == $contentConfig->getType()  )$exists = true;
                }


                if(!$exists) {
                    $node->removeTextContent($textContent);
                }


            }

            foreach( $node->getBooleanContent() as $booleanContent ) {

                $name = $booleanContent->getName();
                $exists = false;
                foreach ($node->getContentTypeConfigs() as $contentConfig) {
                    if($name == $contentConfig->getName() AND $booleanContent->getType() == $contentConfig->getType() )$exists = true;
                }


                if(!$exists) {
                    $node->removeBooleanContent($booleanContent);
                }


            }



            foreach( $node->getDateContent() as $dateContent ) {

                $name = $dateContent->getName();
                $exists = false;
                foreach ($node->getContentTypeConfigs() as $contentConfig) {
                    if($name == $contentConfig->getName() AND $dateContent->getType() == $contentConfig->getType())$exists = true;


                }


                if(!$exists) {
                    $node->removeDateContent($dateContent);
                }


            }

            foreach( $node->getMediaContent() as $mediaContent ) {

                $name = $mediaContent->getName();
                $exists = false;
                foreach ($node->getContentTypeConfigs() as $contentConfig) {
                    if($name == $contentConfig->getName() AND $mediaContent->getType() == $contentConfig->getType())$exists = true;

                }


                if(!$exists) {
                    $node->removeMediaContent($mediaContent);
                }


            }





        }

        public function setGmapDataAction ($id) {

            $node = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->find($id);


            $gMapData = $node->getGMapData();
            if(!$gMapData) {

                $node->setGMapData(new GMapData());
                $gMapData = $node->getGMapData();
            }


            $data = json_decode($_POST['data']);

            foreach ($data->address_components as $comp) {

                switch ($comp->types[0]) {

                    case "street_number":

                        break;

                    case "route":

                        break;

                    // type arrondissement.. etc..
                    case "sublocality":
                        break;

                    case "locality":
                        $gMapData->setCity($comp->long_name);
                        //$comp->setRegionShort($comp->short_name);
                        break;

                    // state
                    case "administrative_area_level_2":
                        $gMapData->setRegion($comp->long_name);
                        $gMapData->setRegionShort($comp->short_name);
                        break;

                    // region
                    case "administrative_area_level_1":
                        $gMapData->setState($comp->long_name);
                        $gMapData->setStateShort($comp->short_name);
                        break;

                    case "country":
                        $gMapData->setCountry($comp->long_name);
                        $gMapData->setCountryShort($comp->short_name);
                        break;

                    case "postal_code":
                        break;


                }


            }

            $node->setFormattedAddress($data->formatted_address);
            $node->setLatitude($data->latitude);
            $node->setLongitude($data->longitude);

            // update
            $em = $this->getDoctrine()->getManager();
            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');
            $em->persist($node);
            $em->flush();

            return new Response(var_dump($data));


        }



    }
