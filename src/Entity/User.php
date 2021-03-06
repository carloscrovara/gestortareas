<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @ORM\Table(name="users")
 * @ORM\Entity
 */

class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
	 * @Assert\Email(
	 *    message = "El email '{{ value }}' no es valido",
     * )
     */
    private $email;

    /**
     * @var string|null
     * @ORM\Column(name="role", type="string", length=50, nullable=true)
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank
	 * @Assert\Regex("/[a-zA-Z ]+/")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank
	 * @Assert\Regex("/[a-zA-Z ]+/")
     */
    private $surname;

    /**
     * @var string|null The hashed password
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank
     */
    private $password;
    

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="user")
	 */
	private $tasks;
	
	public function __construct(){
		$this->tasks = new ArrayCollection();
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function setRoles(?string $role): self
    {
        $this->roles = $role;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
	 * @return Collection|Task[]
	 */
	public function getTasks(): Collection
	{
		return $this->tasks;
	}
}
