<?php

namespace Gitbox\ApplicationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gitbox\ApplicationBundle\Entity\BannedIp;
use Gitbox\ApplicationBundle\Form\BannedIpType;

/**
 * BannedIp controller.
 *
 * @Route("/bans")
 */
class BannedIpController extends Controller
{

    /**
     * Lists all BannedIp entities.
     *
     * @Route("/", name="bans")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GitboxApplicationBundle:BannedIp')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new BannedIp entity.
     *
     * @Route("/", name="bans_create")
     * @Method("POST")
     * @Template("GitboxApplicationBundle:BannedIp:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new BannedIp();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bans_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a BannedIp entity.
    *
    * @param BannedIp $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(BannedIp $entity)
    {
        $form = $this->createForm(new BannedIpType(), $entity, array(
            'action' => $this->generateUrl('bans_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new BannedIp entity.
     *
     * @Route("/new", name="bans_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new BannedIp();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a BannedIp entity.
     *
     * @Route("/{id}", name="bans_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxApplicationBundle:BannedIp')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BannedIp entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing BannedIp entity.
     *
     * @Route("/{id}/edit", name="bans_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxApplicationBundle:BannedIp')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BannedIp entity.');
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
    * Creates a form to edit a BannedIp entity.
    *
    * @param BannedIp $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(BannedIp $entity)
    {
        $form = $this->createForm(new BannedIpType(), $entity, array(
            'action' => $this->generateUrl('bans_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing BannedIp entity.
     *
     * @Route("/{id}", name="bans_update")
     * @Method("PUT")
     * @Template("GitboxApplicationBundle:BannedIp:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GitboxApplicationBundle:BannedIp')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BannedIp entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('bans_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a BannedIp entity.
     *
     * @Route("/{id}", name="bans_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GitboxApplicationBundle:BannedIp')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BannedIp entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bans'));
    }

    /**
     * Creates a form to delete a BannedIp entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bans_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
