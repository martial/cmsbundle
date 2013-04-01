<?php

    namespace scrclub\CMSBundle\Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use scrclub\CMSBundle\Entity\Config;
    use scrclub\CMSBundle\Form\ConfigType;

    /**
     * Config controller.
     *
     * @Route("/config")
     */
    class ConfigController extends Controller {


        public function siteAction() {

            $em = $this->getDoctrine()->getManager();

            $config = new Config();


            // as there's one config only
            // if exists - take the first one always
            // if not that will create

            $configs = $em->getRepository('scrclubCMSBundle:Config')->findAll();
            if (isset($configs[0])) {
                $config = $configs[0];
            }


            $form = $this->createFormBuilder($config)->add('sitename')->add('metakey')->add('metadescr')->getForm();


            $request = $this->get('request');

            // update if needed
            if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($config);
                    $em->flush();

                    //return $this->redirect($this->generateUrl('scrclub_cms_config'));
                } else {

                    //var_dump($form->getErrorsAsString());
                }

            }


            return $this->render('scrclubCMSBundle:cms:config_site.html.twig', array('config' => $config, 'form' => $form->createView(),));


        }

        public function editAction() {

            $em = $this->getDoctrine()->getManager();

            $config = new Config();


            // as there's one config only
            // if exists - take the first one always
            // if not that will create

            $configs = $em->getRepository('scrclubCMSBundle:Config')->findAll();
            if (isset($configs[0])) {
                $config = $configs[0];

                $originalPassword =  $config->getGgPassword();
            }

            $form = $this->createFormBuilder($config)->add('gg_email', 'email')->add('gg_password', 'password', array("required" => false))->add('gg_analyticsid')->add('gs_apikey')->add('gs_sitetoken')->getForm();
            ;


            //$form = $this->createForm(new ConfigType(), $config);

            $request = $this->get('request');

            // update if needed
            if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();

                    if(isset($originalPassword) AND isset($config)) {

                        $pass = $form->get("gg_password")->getData();
                        if(empty($pass))
                            $config->setGgPassword($originalPassword);

                    }

                    $em->persist($config);
                    $em->flush();

                    return $this->redirect($this->generateUrl('scrclub_cms_analytics'));
                } else {

                    //var_dump($form->getErrorsAsString());
                }

            }


            return $this->render('scrclubCMSBundle:cms:config_analytics.html.twig', array('config' => $config, 'form' => $form->createView(),));


        }


    }
