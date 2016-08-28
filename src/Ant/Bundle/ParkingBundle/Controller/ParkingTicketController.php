<?php

namespace Ant\Bundle\ParkingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ant\Bundle\ParkingBundle\Entity\ParkingTicket;
use Ant\Bundle\ParkingBundle\Form\ParkingTicketType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * ParkingTicket controller.
 *
 */
class ParkingTicketController extends BaseController
{

    /**
     * Lists all ParkingTicket entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ParkingBundle:ParkingTicket')->findAllParkingTicketFromToday();

        return $this->render('ParkingBundle:ParkingTicket:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    /**
     * Lists all my ParkingTickets entities.
     *
     */
    public function myParkingTicketsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUserOnline();

        if (is_null($user)){
            $entities = null;
        }else{
            $entities = $em->getRepository('ParkingBundle:ParkingTicket')->findBy(array('creator'=> $user->getId()));
        }

        return $this->render('ParkingBundle:ParkingTicket:my.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    /**
     * Creates a new ParkingTicket entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new ParkingTicket();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            if (is_null($user)){
                $entity->setCreator(666);
            }else{
                $entity->setCreator($user->getId());
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('parkimetro_show', array('id' => $entity->getId())));
        }

        return $this->render('ParkingBundle:ParkingTicket:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ParkingTicket entity.
     *
     * @param ParkingTicket $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ParkingTicket $entity)
    {
        $form = $this->createForm(new ParkingTicketType(), $entity, array(
            'action' => $this->generateUrl('parkimetro_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new ParkingTicket entity.
     *
     */
    public function newAction()
    {
        $entity = new ParkingTicket();
        $form   = $this->createCreateForm($entity);

        return $this->render('ParkingBundle:ParkingTicket:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ParkingTicket entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ParkingBundle:ParkingTicket')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ParkingTicket entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ParkingBundle:ParkingTicket:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ParkingTicket entity.
     *
     */
    public function editAction($id)
    {
        $entity = $this->findOfferByIdThrowExceptionIfNotExistOrIfUserLoguedIsNotOwner($id);

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ParkingBundle:ParkingTicket:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ParkingTicket entity.
    *
    * @param ParkingTicket $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ParkingTicket $entity)
    {
        $form = $this->createForm(new ParkingTicketType(), $entity, array(
            'action' => $this->generateUrl('parkimetro_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing ParkingTicket entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->findParkingTicketByIdThrowExceptionIfNotExist();

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('parkimetro_edit', array('id' => $id)));
        }

        return $this->render('ParkingBundle:ParkingTicket:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a ParkingTicket entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity = $this->findOfferByIdThrowExceptionIfNotExistOrIfUserLoguedIsNotOwner($id);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('parkimetro'));
    }

    /**
     * Creates a form to delete a ParkingTicket entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parkimetro_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @param ParkingTicket $parkingTicket
     * @return bool
     */
    private function checkIfUserIsOwnerParkingTicket(ParkingTicket $parkingTicket)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $user = $this->getUser();

        if ($user->getId() != $parkingTicket->getCreator()){
            throw new AccessDeniedException('No es un ticket de parking tuyo.');
        }else{
            return true;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    private function findParkingTicketByIdThrowExceptionIfNotExist($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ParkingBundle:ParkingTicket')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ParkingTicket entity.');
        }

        return $entity;
    }

    /**
     * @param $id
     * @return mixed
     */
    private function findOfferByIdThrowExceptionIfNotExistOrIfUserLoguedIsNotOwner($id)
    {
        $entity = $this->findParkingTicketByIdThrowExceptionIfNotExist($id);

        $this->checkIfUserIsOwnerParkingTicket($entity);

        return $entity;
    }
}
