<?php echo "<?php\n"; ?>

namespace <?php echo $entity->getNamespace(); ?>;

use Doctrine\ORM\Mapping as ORM;
use <?php echo $repository->getClassName(); ?>;
use Umbrella\CoreBundle\Component\Search\Annotation\Searchable;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\SearchTrait;
use Umbrella\CoreBundle\Model\TimestampTrait;

/**
 * Class <?php echo $entity->getShortClassName(); ?>.
 *
 * @ORM\Entity(<?php echo $repository->getShortClassName(); ?>::class)
 * @ORM\HasLifecycleCallbacks
 * @Searchable
 */
class <?php echo $entity->getShortClassName() . "\n"; ?>
{
    use IdTrait;
    use TimestampTrait;
    use SearchTrait;
}