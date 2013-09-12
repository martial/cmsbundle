<?php

    namespace scrclub\CMSBundle\Controller;

    use scrclub\CMSBundle\Entity\Config;
    use scrclub\CMSBundle\Entity\ContentTypeConfig;
    use scrclub\CMSBundle\Form\ContentConfigType;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use scrclub\CMSBundle\Entity\Langs;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Locale\Locale;

    use Symfony\Component\Security\Core\Role\Role;
    use Symfony\Component\Security\Core\Role\RoleHierarchy;

    class CMSConfigController extends Controller {

        /*
        *
        * Display list of the nodes
        *
        */

        public function indexAction() {




        }

        public function showLangsAction () {

            /*
            *
            *  LANGS
            *
            */

            $em = $this->getDoctrine()->getManager();

            // get langs
            $locales = Locale::getDisplayLocales('fr');
            $localeCodes = Locale::getLocales();

            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Langs');

            $langs = $repo->findAll();

            //format langs for default
            $default_langs = array();
            foreach($langs as $lang) {
                array_push($default_langs, $lang->getLocale());
            }

            return $this->render('scrclubCMSBundle:cms:config_lang.html.twig', array(
                "langs" => $langs,
                "default_lang" => $default_langs,
                "locales" => $locales

            ));


        }

        public function showUsersAction() {

            $em = $this->getDoctrine()->getManager();
            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\User');
            $users = $em->getRepository('scrclubCMSBundle:User')->findAll();
            $roleHierarchy = new RoleHierarchy($this->container->getParameter('security.role_hierarchy.roles'));
            $userRoles = array(new Role('ROLE_ADMIN'));
            $roles = $roleHierarchy->getReachableRoles($userRoles);

            return $this->render('scrclubCMSBundle:cms:config_users.html.twig', array(
                "users" => $users,
                "roles" => $roles
            ));

        }

        public function updateLangsAction() {

            $request = $this->get('request');

            $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Langs');
            $em = $this->getDoctrine()->getManager();

            if ($request->getMethod() == 'POST') {

                $data = $request->request->all();





                foreach ($data['data'] as $locale ) {

                    $l = $repo->findByLocale($locale);

                    if (empty($l)) {

                        $lang = new Langs();
                        $lang->setLocale($locale);
                        $lang->setDefault(false);
                        $em->persist($lang);

                    }

                }

                $em->flush();

                // check if need to remove or add
                $langs = $repo->findAll();


                foreach($langs as $l) {

                    $haslocale = false;
                    foreach ($data['data'] as $locale ) {
                    if ( $l->getLocale() == $locale)
                        $haslocale = true;
                    }

                    if(!$haslocale) {
                        $em->remove($l);
                        $em->flush();
                    }

                }

            }


            // set default

            $i=0;
            $langs = $repo->findAll();
            foreach($langs as $lang) {

                if($i==0)
                    $lang->setDefault(true);
                else
                    $lang->setDefault(false);

                $em->persist($lang);

                $i++;

            }

            $em->flush();

            return new Response('');

        }

        public function contentTypeAction () {

            $em = $this->getDoctrine()->getManager();

            $config = new Config();


            // as there's one config only
            // if exists - take the first one always
            // if not that will create

            $configs = $em->getRepository('scrclubCMSBundle:Config')->findAll();
            if (isset($configs[0])) {
                $config = $configs[0];
            }


            $form = $this->createFormBuilder($config)->add('contentTypeConfigs', 'collection', array(
                'type' => new ContentConfigType(),
                'allow_add' => true,
                'by_reference' => false,
            ))->getForm();


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

                    var_dump($form->getErrorsAsString());
                }

            }

            return $this->render('scrclubCMSBundle:cms:config_content_types.html.twig', array(
                'config' => $config, 'form' => $form->createView()

            ));


        }



    }
