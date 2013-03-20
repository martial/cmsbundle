<?php

namespace scrclub\CMSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use scrclub\CMSBundle\Entity\User;
use scrclub\CMSBundle\Form\UserType;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;

        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token' => $csrfToken,
        ));
    }

    protected function renderLogin(array $data)
    {
        return $this->container->get('templating')->renderResponse("scrclubCMSBundle:cms:login.html.twig", $data);
    }

    public function addUserAction ($id) {

        // get langs

        $em = $this->getDoctrine()->getManager();

        $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\User');


        $user = new User();

        $request = $this->getRequest();
        $locale = $request->getLocale();


        if (isset($id)) {

            $user =$repo->find($id);
            if (!$user) {
                throw $this->createNotFoundException('Unable to find User.');
            }


        }

        /*
         *  default roles
         */

        $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\User');
        $users = $em->getRepository('scrclubCMSBundle:User')->findAll();
        $roleHierarchy = new RoleHierarchy($this->container->getParameter('security.role_hierarchy.roles'));
        $userRoles = array(new Role('ROLE_ADMIN'));
        $roles = $roleHierarchy->getReachableRoles($userRoles);

        $form  = $this->createForm(new UserType($roles), $user);


        $request = $this->get('request');


        // update if needed
        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('scrclub_cms_adduser', array('id' => $$user->getId())));
            } else {
                var_dump($form->getErrors());
            }
        }


        return $this->render('scrclubCMSBundle:cms:adduser.html.twig', array('user' => $user,
                                                                             'form'=>$form->createView(),
                                                                             'locale' => $locale));

    }



}
