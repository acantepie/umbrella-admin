<?php

namespace Umbrella\AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Umbrella\AdminBundle\Model\AdminUserInterface;
use Umbrella\CoreBundle\Component\Search\Annotation\SearchableField;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Model\ActiveTrait;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Model\SearchTrait;
use Umbrella\CoreBundle\Model\TimestampTrait;

/**
 * Class User.
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
class BaseUser implements EquatableInterface, \Serializable, AdminUserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';

    use ActiveTrait;
    use IdTrait;
    use SearchTrait;
    use TimestampTrait;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @SearchableField
     */
    public $firstname;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @SearchableField
     */
    public $lastname;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    public $password;

    /**
     * Used only by form.
     *
     * @var string|null
     */
    public $plainPassword;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=32)
     */
    public $salt;

    /**
     * @var string
     * @ORM\Column(type="string", length=60, unique=true)
     *
     * @SearchableField
     */
    public $email;

    /**
     * Random string sent to the user email address to verify it.
     *
     * @var string|null
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     */
    public $confirmationToken;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $passwordRequestedAt;

    /**
     * @var array
     * @ORM\Column(type="simple_array", nullable=true)
     */
    public $roles = [];

    /**
     * @var UmbrellaFile|null
     */
    public $avatar;

    /**
     * @var ArrayCollection|BaseUserGroup[]
     */
    public $groups;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->salt = md5(uniqid('', true));
        $this->groups = new ArrayCollection();
    }

    public function addGroup(BaseUserGroup $group)
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
    }

    public function removeGroup(BaseUserGroup $group)
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }
    }

    // Equatable implementation

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->getUsername() !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    // Serializable implementation

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->password,
            $this->salt,
            $this->email,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->password,
            $this->salt,
            $this->email
            ) = unserialize($serialized);
    }

    // AdminUserInterface implementation

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;

        foreach ($this->groups as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_values(array_unique($roles));
    }

    /**
     * {@inheritdoc}
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * {@inheritdoc}
     */
    public function setPassword($password)
    {
        $this->password = $password;
        $this->passwordRequestedAt = null;
        $this->confirmationToken = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfirmationToken(?string $confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        if (null !== $confirmationToken) {
            $this->passwordRequestedAt = new \DateTime();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordRequestNonExpired(int $ttl): bool
    {
        return $this->passwordRequestedAt instanceof \DateTime &&
            $this->passwordRequestedAt->getTimestamp() + $ttl > time();
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->firstname, $this->lastname);
    }

    /**
     * {@inheritdoc}
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * {@inheritdoc}
     */
    public function setActive(bool $active)
    {
        return $active = $active;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->getUsername();
    }
}
