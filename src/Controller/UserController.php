<?php

namespace Umbrella\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Umbrella\AdminBundle\Services\UserManager;
use Umbrella\CoreBundle\Controller\BaseController;
use function Symfony\Component\Translation\t;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends BaseController
{
    /**
     * @Route("")
     */
    public function indexAction(Request $request)
    {
        $table = $this->createTable($this->getParameter('umbrella_admin.user.user.table'));
        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getCallbackResponse();
        }

        return $this->render('@UmbrellaAdmin/DataTable/index.html.twig', [
            'table' => $table,
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"})
     *
     * @param mixed|null $id
     */
    public function editAction(UserManager $manager, Request $request, $id = null)
    {
        if (null === $id) {
            $entity = $manager->createUser();
        } else {
            $entity = $manager->find($id);
            $this->throwNotFoundExceptionIfNull($entity);
        }

        $form = $this->createForm($this->getParameter('umbrella_admin.user.user.form'), $entity, [
            'password_required' => null === $id,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->update($entity);

            return $this->jsResponseBuilder()
                ->closeModal()
                ->reloadTable()
                ->toastSuccess(t('message.entity_updated'));
        }

        return $this->jsResponseBuilder()
            ->openModalView('@UmbrellaAdmin/User/edit.html.twig', [
                'form' => $form->createView(),
                'title' => null !== $id ? $this->trans('action.edit_user') : $this->trans('action.add_user'),
                'entity' => $entity,
            ]);
    }

    /**
     * @Route("/toggle-active/{id}/{value}", requirements={"id": "\d+"})
     */
    public function toggleActiveAction(UserManager $manager, $id, $value)
    {
        $entity = $manager->find($id);
        $this->throwNotFoundExceptionIfNull($entity);

        $entity->setActive(1 == $value);
        $manager->update($entity);

        return $this->jsResponseBuilder();
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"})
     */
    public function deleteAction(UserManager $manager, Request $request, $id)
    {
        $entity = $manager->find($id);
        $this->throwNotFoundExceptionIfNull($entity);
        $manager->remove($entity);

        return $this->jsResponseBuilder()
            ->closeModal()
            ->reloadTable()
            ->toastSuccess(t('message.entity_deleted'));
    }
}
