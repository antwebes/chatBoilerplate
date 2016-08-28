<?php

namespace Ant\Bundle\ParkingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ant\Bundle\ParkingBundle\Entity\Parking;
use Ant\Bundle\ParkingBundle\Form\ParkingType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Parking controller.
 *
 */
class ParkingController extends BaseController
{

    /**
     * Lists all Parking entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ParkingBundle:Parking')->findAll();

        return $this->render('ParkingBundle:Parking:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Lists all my Parking entities.
     *
     */
    public function myParkingsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUserOnline();

        if (is_null($user)){
            $entities = null;
        }else{
            $entities = $em->getRepository('ParkingBundle:Parking')->findBy(array('owner'=> $user->getId()));
        }

        return $this->render('ParkingBundle:ParkingTicket:my.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Parking entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Parking();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            if (is_null($user)){
                $entity->setOwner(666);
            }else{
                $entity->setOwner($user->getId());
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('parking_show', array('id' => $entity->getId())));
        }

        return $this->render('ParkingBundle:Parking:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Parking entity.
     *
     * @param Parking $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Parking $entity)
    {
        $form = $this->createForm(new ParkingType(), $entity, array(
            'action' => $this->generateUrl('parking_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Parking entity.
     *
     */
    public function newAction()
    {
        $entity = new Parking();
        $form   = $this->createCreateForm($entity);

        return $this->render('ParkingBundle:Parking:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Parking entity.
     *
     */
    public function showAction($id)
    {
        $entity = $this->findParkingByIdThrowExceptionIfNotExist($id);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ParkingBundle:Parking:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Parking entity.
     *
     */
    public function editAction($id)
    {
        $entity = $this->findParkingByIdThrowExceptionIfNotExistOrIfUserLoguedIsNotOwner($id);

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('ParkingBundle:Parking:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Parking entity.
    *
    * @param Parking $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Parking $entity)
    {
        $form = $this->createForm(new ParkingType(), $entity, array(
            'action' => $this->generateUrl('parking_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Parking entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->findParkingByIdThrowExceptionIfNotExistOrIfUserLoguedIsNotOwner($id);

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('parking_edit', array('id' => $id)));
        }

        return $this->render('ParkingBundle:Parking:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Parking entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $entity = $this->findParkingByIdThrowExceptionIfNotExistOrIfUserLoguedIsNotOwner($id);

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('parking'));
    }

    /**
     * Creates a form to delete a Parking entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parking_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @param Parking $parking
     * @return bool
     */
    private function checkIfUserIsOwnerParking(Parking $parking)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $user = $this->getUser();

        if ($user->getId() != $parking->getOwner()){
            throw new AccessDeniedException('No es un parking tuya.');
        }else{
            return true;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    private function findParkingByIdThrowExceptionIfNotExist($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ParkingBundle:Parking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Parking entity.');
        }

        return $entity;
    }

    /**
     * @param $id
     * @return mixed
     */
    private function findParkingByIdThrowExceptionIfNotExistOrIfUserLoguedIsNotOwner($id)
    {
        $entity = $this->findParkingByIdThrowExceptionIfNotExist($id);

        $this->checkIfUserIsOwnerParking($entity);

        return $entity;
    }
}
