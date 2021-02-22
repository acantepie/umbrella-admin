<?php echo "<?php\n"; ?>

namespace <?php echo $entity->getNamespace(); ?>;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use <?php echo $repository->getClassName(); ?>;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\TimestampTrait;
use Umbrella\CoreBundle\Model\NestedTreeEntityInterface;
use Umbrella\CoreBundle\Model\NestedTreeEntityTrait;

/**
 * Class <?php echo $entity->getClassName(); ?>.
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(<?php echo $repository->getShortClassName(); ?>::class)
 * @ORM\HasLifecycleCallbacks
 */
class <?php echo $entity->getShortClassName(); ?> implements NestedTreeEntityInterface
{
    use IdTrait;
    use TimestampTrait;
    use NestedTreeEntityTrait;

    /**
     * @var int
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    public $level;

    /**
     * @var int
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer", name="`left`")
     */
    public $left;

    /**
     * @var int
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer", name="`right`")
     */
    public $right;

    /**
     * @var <?php echo $entity->getShortClassName(); ?>
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="<?php echo $entity->getShortClassName(); ?>")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    public $root;

    /**
     * @var <?php echo $entity->getShortClassName(); ?>|null
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="<?php echo $entity->getShortClassName(); ?>", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    public $parent;

    /**
     * @var <?php echo $entity->getShortClassName(); ?>[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="<?php echo $entity->getShortClassName(); ?>", mappedBy="parent", cascade={"ALL"})
     * @ORM\OrderBy({"left": "ASC"})
     */
    public $children;

    /**
     * <?php echo $entity->getShortClassName(); ?> constructor.
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this === $this->root ? '/' : (string) $this->id;
    }

}