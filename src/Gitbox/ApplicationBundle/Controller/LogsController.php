<?php

namespace Gitbox\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gitbox\ApplicationBundle\Entity\Logs;
use Gitbox\ApplicationBundle\Form\LogsType;

/**
 * Logs controller.
 *
 * @Route("/logs")
 */
class LogsController extends Controller
{

    /**
     * Lists all Logs entities.
     *
     * @Route("/", name="logs")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GitboxApplicationBundle:Logs')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Logs entity.
     *
     * @Route("/", name="logs_create")
     * @Method("POST")
     * @Template("GitboxApplicationBundle:Logs:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Logs();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('logs_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Logs entity.
    *
    * @param Logs $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Logs $entity)
    {
        $form = $this->createForm(new LogsType(), $entity, array(
            'action' => $this->generateUrl('logs_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Logs entity.
     *
     * @Route("/new", name="logs_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Logs();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Logs entity.
     *
     * @Route("/{id}", name="logs_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxApplicationBundle:Logs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Logs entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Logs entity.
     *
     * @Route("/{id}/edit", name="logs_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxApplicationBundle:Logs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Logs entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Logs entity.
    *
    * @param Logs $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Logs $entity)
    {
        $form = $this->createForm(new LogsType(), $entity, array(
            'action' => $this->generateUrl('logs_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Logs entity.
     *
     * @Route("/{id}", name="logs_update")
     * @Method("PUT")
     * @Template("GitboxApplicationBundle:Logs:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxApplicationBundle:Logs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Logs entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('logs_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Logs entity.
     *
     * @Route("/{id}", name="logs_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GitboxApplicationBundle:Logs')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Logs entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('logs'));
    }

    /**
     * Creates a form to delete a Logs entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('logs_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
