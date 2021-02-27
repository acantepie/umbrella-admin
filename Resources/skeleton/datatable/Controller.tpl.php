<?= "<?php\n"; ?>

namespace <?= $controller->getNamespace(); ?>;

use <?= $table->getClassName(); ?>;
use <?= $entity->getClassName(); ?>;
use <?= $form->getClassName(); ?>;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
<?php if ('page' === $view_type) { ?>
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
<?php } ?>
use Umbrella\CoreBundle\Controller\BaseController;
use function Symfony\Component\Translation\t;

/**
 * @Route("/<?= $routepath; ?>")
 */
class <?= $controller->getShortClassName(); ?> extends BaseController
{
    /**
     * @Route("")
     */
    public function indexAction(Request $request)
    {
        $table = $this->createTable(<?= $table->getShortClassName(); ?>::class);
        $table->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getCallbackResponse();
        }

        return $this->render('<?= $templatepath_index; ?>', [
            'table' => $table
        ]);
    }

    /**
     * @Route(path="/edit/{id}", requirements={"id"="\d+"})
     */
    public function editAction(Request $request, $id = null)
    {
<?php if ('page' === $view_type) { ?>
        $this->getMenu()->setCurrent('<?= $routename_prefix; ?>_index', Menu::BY_ROUTE);
        $this->getBreadcrumb()->addItem(['label' => $id ? 'action.edit_<?= $i18n_id; ?>' : 'action.add_<?= $i18n_id; ?>']);
<?php } ?>

        if ($id === null) {
            $entity = new <?= $entity->getShortClassName(); ?>();
<?php if ('tree' === $structure) { ?>
            $entity->parent = $this->getRepository(<?= $entity->getShortClassName(); ?>::class)->findRoot(true);
<?php } ?>
        } else {
            $entity = $this->findOrNotFound(<?= $entity->getShortClassName(); ?>::class, $id);
        }

        $form = $this->createForm(<?= $form->getShortClassName(); ?>::class, $entity);
        $form->handleRequest($request);

<?php if ('modal' === $view_type) { ?>
        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($entity);

            return $this->jsResponseBuilder()
                ->closeModal()
                ->reloadTable()
                ->toastSuccess(t('message.entity_updated'));
        }

        return $this->jsResponseBuilder()
            ->openModalView('<?= $templatepath_edit; ?>', [
                'form' => $form->createView(),
                'entity' => $entity,
            ]);
<?php } else { ?>
        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($entity);
            $this->toastSuccess(t('message.entity_updated'));
            return $this->redirectToRoute('<?= $routename_prefix; ?>_edit', [
                'id' => $entity->id
            ]);
        }

        return $this->render('<?= $templatepath_edit; ?>', [
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
        $entity = $this->findOrNotFound(<?= $entity->getShortClassName(); ?>::class, $id);
        $this->removeAndFlush($entity);

        return $this->jsResponseBuilder()
            ->reloadTable()
            ->toastSuccess(t('message.entity_deleted'));
    }

}