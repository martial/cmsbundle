<?php

    namespace scrclub\CMSBundle\Controller;

    use Doctrine\ORM\Query;
    use scrclub\CMSBundle\Entity\Node;
    use Symfony\Component\HttpFoundation\Cookie;
    use Symfony\Component\HttpFoundation\RedirectResponse;
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

        public function hasLocaleChanged($locale) {

            /*
            //
            $cookie = new Cookie('auto_locale', "off", time() + 3600 * 24 * 7);
            $responseHeaders->setCookie($cookie);



            return new RedirectResponse()

            */
        }

        public function setCountryCookie(&$responseHeaders) {

            $request = $this->get('request');
            $locale = $request->cookies->get('auto_locale');

            // check if locale has changed !
            // if yes - we sure are off

            $hasChanged = false;

            $prevLocale = $this->get("session")->get('prev-locale');
            if ($prevLocale != "" and $prevLocale != $this->get('request')->getLocale() ) {

                $cookie = new Cookie('auto_locale', "off", time() + 3600 * 24 * 7);
                $responseHeaders->setCookie($cookie);

                $hasChanged = true;
            }



            if(!isset($locale) || empty($locale) && $locale != "off") {
                $geoip = $this->get("cms_bundle.freegeoip");
                $locale = $geoip->isInFrance() ? "fr" : "en";

                $cookie = new Cookie('auto_locale', $locale, time() + 3600 * 24 * 7);
                $responseHeaders->setCookie($cookie);

                // $this->setTranslatableLocale("en");
                $this->get('request')->setLocale($locale);
                $this->get('request')->getSession()->set('_locale', $locale);
                $this->get('request')->setDefaultLocale($locale);
                $this->get('stof_doctrine_extensions.listener.translatable')->setTranslatableLocale($locale);



            } else if($locale != "off" and !$hasChanged) {

               // $this->setTranslatableLocale("en");
                $this->get('request')->setLocale($locale);
                $this->get('request')->getSession()->set('_locale', $locale);
                $this->get('request')->setDefaultLocale($locale);
                $this->get('stof_doctrine_extensions.listener.translatable')->setTranslatableLocale($locale);
            }

            // here we set locale
            $this->get("session")->set('prev-locale', $locale);

            return $responseHeaders;

        }



    }
