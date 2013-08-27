<?php

    namespace scrclub\CMSBundle\Controller;

    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use scrclub\CMSBundle\Entity\Category;
    use scrclub\CMSBundle\Form\CategoryType;

    /**
     * Template controller.
     *
     */
    class DateController extends Controller
    {

        public function __construct() {



        }



        function deleteAction($id) {

            $request = $this->get('request');
            if ($request->getMethod() == 'POST') {

                $tree = $request->request->all();

                $em = $this->getDoctrine()->getManager();
                $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Date');

                $category = $repo->find($id);


                $em->remove($category);
                $em->flush();

            }

            return new Response('');

        }

    }
