<?php echo "<?php\n"; ?>

namespace <?php echo $controller->getNamespace(); ?>;

use <?php echo $table->getClassName(); ?>;
use <?php echo $entity->getClassName(); ?>;
use <?php echo $form->getClassName(); ?>;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
<?php if ('page' === $view_type) { ?>
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
<?php } ?>
use Umbrella\CoreBundle\Controller\BaseController;


/**
 * @Route("/<?php echo $routepath; ?>")
 */
class <?php echo $controller->getShortClassName(); ?> extends BaseController
{
    /**
     * @Route("")
     */
    public function indexAction(Request $request)
    {
        $table = $this->createTable(<?php echo $table->getShortClassName(); ?>::class);
        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getCallbackResponse();
        }

        return $this->render('<?php echo $templatepath_index; ?>', [
            'table' => $table
        ]);
    }

    /**
     * @Route(path="/edit/{id}", requirements={"id"="\d+"})
     */
    public function editAction(Request $request, $id = null)
    {
<?php if ('page' === $view_type) { ?>
        $this->getMenu()->setCurrent('<?php echo $routename_prefix; ?>_index', Menu::BY_ROUTE);
        $this->getBreadcrumb()->addItem(['label' => $id ? 'action.edit_<?php echo $i18n_id; ?>' : 'action.add_<?php echo $i18n_id; ?>']);
<?php } ?>

        if ($id === null) {
            $entity = new <?php echo $entity->getShortClassName(); ?>();
<?php if ('tree' === $structure) { ?>
            $entity->parent = $this->getRepository(<?php echo $entity->getShortClassName(); ?>::class)->findRoot(true);
<?php } ?>
        } else {
            $entity = $this->findOrNotFound(<?php echo $entity->getShortClassName(); ?>::class, $id);
        }

        $form = $this->createForm(<?php echo $form->getShortClassName(); ?>::class, $entity);
        $form->handleRequest($request);

<?php if ('modal' === $view_type) { ?>
        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($entity);

            return $this->jsResponseBuilder()
                ->closeModal()
                ->reloadTable()
                ->toastSuccess('message.entity_updated');
        }

        return $this->jsResponseBuilder()
            ->openModalView('<?php echo $templatepath_edit; ?>', [
                'form' => $form->createView(),
                'entity' => $entity,
            ]);
<?php } else { ?>
        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($entity);
            $this->toastSuccess('message.entity_updated');
            return $this->redirectToRoute('<?php echo $routename_prefix; ?>_edit', [
                'id' => $entity->id
            ]);
        }

        return $this->render('<?php echo $templatepath_edit; ?>', [
            'form' => $form->createView(),
            'entity' => $entity,
        ]);
<?php } ?>
    }

    /**
     * @Route(path="/delete/{id}", requirements={"id"="\d+"})
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->findOrNotFound(<?php echo $entity->getShortClassName(); ?>::class, $id);
        $this->removeAndFlush($entity);

        return $this->jsResponseBuilder()
            ->reloadTable()
            ->toastSuccess('message.entity_deleted');
    }

}