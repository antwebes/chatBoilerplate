<?php

namespace Ant\Bundle\OfferBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ant\Bundle\OfferBundle\Entity\Offer;
use Ant\Bundle\OfferBundle\Form\OfferType;

/**
 * Offer controller.
 *
 */
class OfferController extends Controller
{

    private function assertPermissions()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }
    }

    private function getUserOnline()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return null;
        }

        $user = $this->getUser();

        return $user;
    }

    /**
     * Lists all Offer entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUserOnline();

        $entities = $em->getRepository('OfferBundle:Offer')->findAll();

        return $this->render('OfferBundle:Offer:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Lists all my Offer entities.
     *
     */
    public function myOffersAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUserOnline();

        if (is_null($user)){
            $entities = null;
        }else{
            $entities = $em->getRepository('OfferBundle:Offer')->findBy(array('owner'=> $user->getId()));
        }

        return $this->render('OfferBundle:Offer:index_dashboard.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Offer entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Offer();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('offer_show', array('id' => $entity->getId())));
        }

        return $this->render('OfferBundle:Offer:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Offer entity.
     *
     * @param Offer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Offer $entity)
    {
        $this->assignUserIdToOffer($entity);

        $form = $this->createForm(new OfferType(), $entity, array(
            'action' => $this->generateUrl('offer_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Offer entity.
     *
     */
    public function newAction()
    {
        $entity = new Offer();

        $this->assignUserIdToOffer($entity);

        $form   = $this->createCreateForm($entity);

        return $this->render('OfferBundle:Offer:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Insert userId to Offer
     * @param Offer $entity
     * @return Offer
     */
    private function assignUserIdToOffer(Offer $entity)
    {
        $user = $this->getUser();
        if (is_null($user)){
            $entity->setOwner(666);
        }else{
            $entity->setOwner($user->getId());
        }
        return $entity;
    }

    /**
     * Finds and displays a Offer entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OfferBundle:Offer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Offer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OfferBundle:Offer:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Offer entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OfferBundle:Offer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Offer entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('OfferBundle:Offer:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Offer entity.
    *
    * @param Offer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Offer $entity)
    {
        $form = $this->createForm(new OfferType(), $entity, array(
            'action' => $this->generateUrl('offer_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Offer entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OfferBundle:Offer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Offer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('offer_edit', array('id' => $id)));
        }

        return $this->render('OfferBundle:Offer:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Offer entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OfferBundle:Offer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Offer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('offer'));
    }

    /**
     * Creates a form to delete a Offer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('offer_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
