<?php

    namespace scrclub\CMSBundle\Controller;

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
            $query->andWhere("node.type = 'node'");
            $result = $query->getQuery()->getResult();

            // add config

            $config = NULL;
            $configs = $em->getRepository('scrclubCMSBundle:Config')->findAll();
            if (isset($configs[0])) {
                $config = $configs[0];
            }

            return $this->render('scrclubCMSBundle:cms:index.html.twig', array("type" => "node", "tree" => $result, "config" => $config));

        }


        public function addNodeAction($id) {

            // get langs

            $em = $this->getDoctrine()->getManager();

            $lang_repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Langs');
            $langs = $lang_repo->findAll();

            $node = new Node();
            $node->setType('node');

            $request = $this->getRequest();
            $locale = $request->getLocale();


            //$templates = $em->getRepository('scrclubCMSBundle:Template')->findAll();

            $query = $em->getRepository('scrclubCMSBundle:Template')->createQueryBuilder('p')//->where('type', 'node')
                ->orderBy('p.name', 'ASC')->getQuery();

            $templates = $query->getResult();

            if (isset($id)) {

                $node = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->find($id);

                if (!$node) throw $this->createNotFoundException('Unable to find Node entity.');

                //$node->setTranslatableLocale($locale);

            }

            $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node')->getMediaSetsRecursive($node);

            $defaultTemplate = null;
            $parent = $node->getParent();
            if ( isset( $parent ) ){
                $defaultTemplate = $parent->getTemplateDefaultChild();
            };

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




                   // return $this->redirect($this->generateUrl('scrclub_cms_addnode', array('id' => $node->getId())));
                }
            }


            return $this->render('scrclubCMSBundle:cms:addnode.html.twig', array('node' => $node, 'form'=> $form->createView(), 'langs' => $langs, 'locale' => $locale, 'templates' => $templates));

        }

        public function updateTreeAction() {

            $request = $this->get('request');

            if ($request->getMethod() == 'POST') {

                $tree = $request->request->all();

                $em = $this->getDoctrine()->getManager();
                $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');

                foreach ($tree['data'] as $branch) {

                    if ($branch['item_id'] != 'null') {

                        $node = $em->getRepository('scrclub\CMSBundle\Entity\Node')->findOneById($branch['item_id']);
                        $nodeParent = $em->getRepository('scrclub\CMSBundle\Entity\Node')->findOneById($branch['parent_id']);

                        if ($node) {

                            if ($nodeParent) $node->setParent($nodeParent); else
                                $node->setParent(null);

                            $node->setLft($branch['left']);
                            $node->setRgt($branch['right']);
                            $node->setSlug(NULL);
                            $em->persist($node);

                        }

                    }

                }

                $em->flush();

            }

            $this->regenerateFullSlugs();

            return new Response('');

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

            $em = $this->getDoctrine()->getManager();
            $lang_repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Langs');
            $langs = $lang_repo->findAll();

            $post = new Post();
            $post->setType('post');

            $noderepo = $em->getRepository('scrclub\CMSBundle\Entity\Node');
            $node_parent = $noderepo->find($parent_id);
            $post->setParent($node_parent);

            //$post->setTemplate($node_parent->getTemplate());


            $request = $this->getRequest();
            $locale = $request->getLocale();

            $originalCategories = array();

            if (isset($id)) {

                $post = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Post')->find($id);

                // get categories before
                $originalCategories = $post->getCategories();

            }


            $noderepo->getMediaSetsRecursive($post);




            $form = $this->createForm(new PostType($lang_repo, array(), $node_parent->getTemplateDefaultChild() ), $post);


            $request = $this->get('request');
            // update if needed
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

                    $em->flush();


                    return $this->redirect($this->generateUrl('scrclub_cms_addpost', array('parent_id' => $parent_id, 'id' => $post->getId())));
                }
            }


            // tree

            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');
            $result = $repo->getRootNodes();


            return $this->render('scrclubCMSBundle:cms:addnode.html.twig', array('node' => $post, 'form'=> $form->createView(), 'langs' => $langs, 'locale' => $locale, 'parent_id' => $parent_id, 'tree' => $result));

        }




    }
