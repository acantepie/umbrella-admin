<?php echo "<?php\n"; ?>

namespace <?php echo $repository->getNamespace(); ?>;

use <?php echo $entity->getClassName(); ?>;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method <?php echo $entity->getShortClassName(); ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?php echo $entity->getShortClassName(); ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?php echo $entity->getShortClassName(); ?>[]    findAll()
 * @method <?php echo $entity->getShortClassName(); ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?php echo $repository->getShortClassName(); ?> extends NestedTreeRepository
{

    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(<?php echo $entity->getShortClassName(); ?>::class));
    }

    public function findRoot(bool $create = false) : ?<?php echo $entity->getShortClassName(); ?>
    {
        $root = $this->getRootNodesQueryBuilder()
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $root && $create) {
            $root = new <?php echo $entity->getShortClassName(); ?>();
            $this->_em->persist($root);
            $this->_em->flush();
        }

        return $root;
    }
}