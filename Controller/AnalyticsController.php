<?php

    namespace scrclub\CMSBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use scrclub\CMSBundle\Entity\Config;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Locale\Locale;

    use Symfony\Component\HttpFoundation\Cookie;


    class AnalyticsController extends Controller {


        public function loginGoogleAction() {

            $this->ga = $this->container->get('cms_bundle.googleanalytics');

            if(!$this->ga->connected) {

                $em = $this->getDoctrine()->getManager();

                $config  = new Config();

                $configs = $em->getRepository('scrclubCMSBundle:Config')->findAll();
                if (isset($configs[0])) {
                    $config = $configs[0];
                }


                $status = $this->ga->init($config->getGgEmail(), $config->getGgPassword(), $config->getGgAnalyticsid(), date('Y-m-d', time()));

            }


            $request = $this->get('request');

            if($request->isXmlHttpRequest()) {

                return new Response (($this->ga->connected) ? 'connected' : 'disconnected');
            } else {

                return $this->ga->connected;
            }




        }

        /*
        *
        * Display list of the nodes
        *
        */

        public function indexAction() {









            $request = $this->get('request');
            $cookie = $request->cookies->get('ga_views_hom');

            if(isset($cookie)) {

                //echo $cookie;
                //echo 'gogogo';
                $response = new Response($cookie);
                $response->headers->set('Content-Type', 'application/json');
                return $response;


            } else {
                $this->loginGoogleAction();
            }


            if( $this->ga->connected ) {

                $results = array();
                array_push($results, array('time', 'Weekly views'));

                for ($i=30; $i >= 0; $i --) {

                    $t = time() - ( $i * 86400);
                    $this->ga->setDate(date('Y-m-d',$t));
                    array_push($results, array( "", intval($this->ga->getMetric('pageviews'))));

                }

                $json = json_encode($results);

                $response = new Response($json);
                $cookie = new Cookie('ga_views_hom', $json, time() + (86400));
                $response->headers->setCookie($cookie);
                $response->headers->set('Content-Type', 'application/json');



                return $response;

            } else {

                return new Response ('Error');

            }





        }




    }
