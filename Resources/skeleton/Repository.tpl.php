<?php echo "<?php\n"; ?>

namespace <?php echo $repository->getNamespace(); ?>;

use <?php echo $entity->getClassName(); ?>;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method <?php echo $entity->getShortClassName(); ?>|null find($id, $lockMode = null, $lockVersion = null)
 * @method <?php echo $entity->getShortClassName(); ?>|null findOneBy(array $criteria, array $orderBy = null)
 * @method <?php echo $entity->getShortClassName(); ?>[]    findAll()
 * @method <?php echo $entity->getShortClassName(); ?>[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class <?php echo $repository->getShortClassName(); ?> extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, <?php echo $entity->getShortClassName(); ?>::class);
    }

}