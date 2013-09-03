<?php

    namespace scrclub\CMSBundle\Controller;

    use Doctrine\ORM\Query;
    use scrclub\CMSBundle\Entity\Node;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;



    class SiteController extends Controller
    {


        public function indexAction() {

            return $this->render(new \Symfony\Component\HttpFoundation\Response(''));

        }



        public function getRootNodes($addPosts = false) {

            // get nodes
            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Node');
            $em = $this->getDoctrine()->getManager();

            $query = $repo->getRootNodesQueryBuilder();
            if(!$addPosts ) $query->andWhere("node.type = 'node'");
            $query->andWhere("node.active = 1");

            $result = $query->getQuery()->getResult();

            return $result;


        }


        public function getLastPosts($limit = 10) {

            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Post');

            $em = $this->getDoctrine()->getManager();

            $query = $repo->createQueryBuilder('p');
            $query->setMaxResults($limit);
            $query->andWhere("node.active = '1'");
            $result = $repo->findAll();

            return $result;


        }

        /*
         *
         *  Get nodes related by categories
         *
         */

        public function getRelatedByCategory(Node $node) {


            $result = array();
            foreach ($node->getCategories() as  $category ) {


                foreach ($category->getNodes() as $n ) {
                    if( $n != $node AND !in_array($n, $result))
                        array_push($result, $n);
                }

            }

            return $result;

        }

        public function getLangs () {

            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Langs');
            $langs = $repo->findAll();
            return $langs;
        }



        public function getAnalytics() {

            $em = $this->getDoctrine()->getManager();
            $configs = $em->getRepository('scrclubCMSBundle:Config')->findAll();
            if (isset($configs[0])) {
                $config = $configs[0];
                $embed = "<script type='text/javascript'>

                      var _gaq = _gaq || [];
                      _gaq.push(['_setAccount', ".$config->getGgAnalyticsid()."]);
                      _gaq.push(['_trackPageview']);

                      (function() {
                        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                      })();

                    </script>";

                return $embed;
            }



            return "";


        }




    }
