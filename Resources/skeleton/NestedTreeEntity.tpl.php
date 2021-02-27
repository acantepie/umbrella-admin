<?= "<?php\n"; ?>

namespace <?= $entity->getNamespace(); ?>;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use <?= $repository->getClassName(); ?>;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\TimestampTrait;
use Umbrella\CoreBundle\Model\NestedTreeEntityInterface;
use Umbrella\CoreBundle\Model\NestedTreeEntityTrait;

/**
 * Class <?= $entity->getClassName(); ?>.
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(<?= $repository->getShortClassName(); ?>::class)
 * @ORM\HasLifecycleCallbacks
 */
class <?= $entity->getShortClassName(); ?> implements NestedTreeEntityInterface
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
     * @var <?= $entity->getShortClassName(); ?>
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="<?= $entity->getShortClassName(); ?>")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    public $root;

    /**
     * @var <?= $entity->getShortClassName(); ?>|null
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="<?= $entity->getShortClassName(); ?>", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    public $parent;

    /**
     * @var <?= $entity->getShortClassName(); ?>[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="<?= $entity->getShortClassName(); ?>", mappedBy="parent", cascade={"ALL"})
     * @ORM\OrderBy({"left": "ASC"})
     */
    public $children;

    /**
     * <?= $entity->getShortClassName(); ?> constructor.
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