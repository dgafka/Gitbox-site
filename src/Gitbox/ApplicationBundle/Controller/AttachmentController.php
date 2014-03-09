<?php

namespace Gitbox\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gitbox\ApplicationBundle\Entity\Attachment;
use Gitbox\ApplicationBundle\Form\AttachmentType;

/**
 * Attachment controller.
 *
 * @Route("/attachment")
 */
class AttachmentController extends Controller
{

    /**
     * Lists all Attachment entities.
     *
     * @Route("/", name="attachment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GitboxApplicationBundle:Attachment')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Attachment entity.
     *
     * @Route("/", name="attachment_create")
     * @Method("POST")
     * @Template("GitboxApplicationBundle:Attachment:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Attachment();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('attachment_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Attachment entity.
    *
    * @param Attachment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Attachment $entity)
    {
        $form = $this->createForm(new AttachmentType(), $entity, array(
            'action' => $this->generateUrl('attachment_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Attachment entity.
     *
     * @Route("/new", name="attachment_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Attachment();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Attachment entity.
     *
     * @Route("/{id}", name="attachment_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxApplicationBundle:Attachment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attachment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Attachment entity.
     *
     * @Route("/{id}/edit", name="attachment_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxApplicationBundle:Attachment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attachment entity.');
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
    * Creates a form to edit a Attachment entity.
    *
    * @param Attachment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Attachment $entity)
    {
        $form = $this->createForm(new AttachmentType(), $entity, array(
            'action' => $this->generateUrl('attachment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Attachment entity.
     *
     * @Route("/{id}", name="attachment_update")
     * @Method("PUT")
     * @Template("GitboxApplicationBundle:Attachment:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxApplicationBundle:Attachment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attachment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('attachment_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Attachment entity.
     *
     * @Route("/{id}", name="attachment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GitboxApplicationBundle:Attachment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Attachment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('attachment'));
    }

    /**
     * Creates a form to delete a Attachment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('attachment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
