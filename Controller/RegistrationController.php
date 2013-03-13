<?php

    namespace scrclub\CMSBundle\Controller;

    use Symfony\Component\HttpFoundation\RedirectResponse;
    use FOS\UserBundle\Controller\RegistrationController as BaseController;


    use FOS\UserBundle\FOSUserEvents;
    use FOS\UserBundle\Event\FormEvent;
    use FOS\UserBundle\Event\GetResponseUserEvent;
    use FOS\UserBundle\Event\UserEvent;
    use FOS\UserBundle\Event\FilterUserResponseEvent;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use Symfony\Component\Security\Core\Exception\AccessDeniedException;
    use FOS\UserBundle\Model\UserInterface;

    use Symfony\Component\Security\Core\Role\Role;
    use Symfony\Component\Security\Core\Role\RoleHierarchy;




    class RegistrationController extends BaseController
    {
        public function registerAction(Request $request)
        {
            /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
            $formFactory = $this->container->get('fos_user.registration.form.factory');
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->container->get('fos_user.user_manager');
            /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
            $dispatcher = $this->container->get('event_dispatcher');


            // check if update or add

            $user= NULL;

            $requestData = $request->request->all();


            $user = $userManager->findUserBy(array('id' => $request->get('id')));

            if(!$user) {

                $user = $userManager->createUser();
                $user->setEnabled(true);

                $image = new \scrclub\CMSBundle\Entity\Image();

            } else {

                $image = $user->getImage();
            }

            // get default roles

            $doctrine = $this->container->get('doctrine');
            $em = $doctrine->getManager();
            $repo = $doctrine->getRepository('scrclub\CMSBundle\Entity\User');
            $roleHierarchy = new RoleHierarchy($this->container->getParameter('security.role_hierarchy.roles'));
            $userRoles = array(new Role('ROLE_ADMIN'));
            $roles = $roleHierarchy->getReachableRoles($userRoles);

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, new UserEvent($user, $request));

            $form = $formFactory->createForm();
            $form->setData($user);



            if ('POST' === $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $event = new FormEvent($form, $request);
                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                    $requestData = $request->request->all();

                    // here we set role :)
                    $user->setRoles(array($requestData['role']));


                    // image

                    //$image =  new \scrclub\CMSBundle\Entity\Image();
                    $image = $form->get('image');

                    //ar_dump($image->getData());

                    $image->getData()->upload();






                    $userManager->updateUser($user);

                    if (null === $response = $event->getResponse()) {
                        $url = $this->container->get('router')->generate('scrclub_cms_users');
                        $response = new RedirectResponse($url);
                    }

                   // prevent user for being connected !
                   // $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                    return $response;
                } else {

                    echo $form->getErrorsAsString();


                }
            }

            return $this->container->get('templating')->renderResponse('scrclubCMSBundle:cms:adduser.html.'.$this->getEngine(), array(
                'user' => $user,
                'form' => $form->createView(),
                'roles' => $roles,
                'image' => $image
            ));
        }


        public function deleteAction($id) {

            $userManager = $this->container->get('fos_user.user_manager');

            if(!$id) {
                $request = $this->container->get('request');
                $requestData = $request->request->all();
                $id = $requestData['data'];
            }

            $user = $userManager->findUserBy(array('id' => $id));

            $userManager->deleteUser($user);

            $url = $this->container->get('router')->generate('scrclub_cms_users');
            $response = new RedirectResponse($url);
            return $response;

        }



    }