<?php echo "<?php\n"; ?>

namespace <?php echo $entity_notification->getNamespace(); ?>;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\AdminBundle\Entity\BaseNotification;

/**
 * Class <?php echo $entity_notification->getClassName(); ?>.
 * @ORM\Entity
 */
class <?php echo $entity_notification->getShortClassName(); ?> extends BaseNotification
{

}