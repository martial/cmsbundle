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
    class CategoryController extends Controller
    {

        public function __construct() {



        }

        /**
         * Lists all Template entities.
         *
         */
        public function indexAction()
        {
            $em = $this->getDoctrine()->getManager();

            $categories = $em->getRepository('scrclubCMSBundle:Category')->findAll();




            return $this->render('scrclubCMSBundle:cms:categories.html.twig', array(
                'categories' => $categories
            ));



        }




        public function updateAction($id) {


            $lang_repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Langs');
            $langs = $lang_repo->findAll();


            $request = $this->getRequest();
            $locale = $request->getLocale();

            $category  = new Category();

            if (isset($id)) {

                $category = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Category')->find($id);
                if (!$category) {
                    throw $this->createNotFoundException('Unable to find Category entity.');
                }
                $defaultLocale = $lang_repo->getDefaultLocale($langs);
                $category->setTranslatableLocale($defaultLocale->getLocale());

            }


            $form = $this->createForm(new CategoryType($lang_repo), $category);

            $request = $this->get('request');

            // update if needed
            if ($request->getMethod() == 'POST') {

                $form->bind($request);

                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($category);
                    $em->flush();

                    return $this->redirect($this->generateUrl('scrclub_cms_categories_add', array('id' => $category->getId())));


                } else {

                    echo $form->getErrorsAsString();

                }

            }



            return $this->render('scrclubCMSBundle:cms:categories_add.html.twig', array(
                'category' => $category,
                'form'   => $form->createView(),
                'langs' => $langs,
                'locale' => $locale
            ));


        }

        /**
         * Displays a form to create a new Template entity.
         *
         */
        public function newAction()
        {
            $entity = new Template();
            $form   = $this->createForm(new TemplateType(), $entity);

            return $this->render('scrclubCMSBundle:Template:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }

        /**
         * Creates a new Template entity.
         *
         */
        public function createAction(Request $request)
        {
            $entity  = new Template();
            $form = $this->createForm(new TemplateType(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                return $this->redirect($this->generateUrl('template_show', array('id' => $entity->getId())));
            }

            return $this->render('scrclubCMSBundle:Template:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ));
        }

        /**
         * Displays a form to edit an existing Template entity.
         *
         */
        public function editAction($id)
        {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('scrclubCMSBundle:Template')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Template entity.');
            }

            $editForm = $this->createForm(new TemplateType(), $entity);
            $deleteForm = $this->createDeleteForm($id);

            return $this->render('scrclubCMSBundle:Template:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }



        function deleteAction($id) {

            $request = $this->get('request');
            if ($request->getMethod() == 'POST') {

                $tree = $request->request->all();

                $em = $this->getDoctrine()->getManager();
                $repo = $this->getDoctrine()->getRepository('scrclub\CMSBundle\Entity\Category');

                $category = $repo->find($id);


                $em->remove($category);
                $em->flush();

            }

            return new Response('');

        }

        private function createDeleteForm($id)
        {
            return $this->createFormBuilder(array('id' => $id))
                ->add('id', 'hidden')
                ->getForm()
                ;
        }
    }
