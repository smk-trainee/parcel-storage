<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\FullNameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FullNameRepository::class)]
class FullName
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $firstName = null;

    #[ORM\Column(length: 64)]
    private ?string $lastName = null;

    #[ORM\Column(length: 64)]
    private ?string $middleName = null;

    #[ORM\OneToMany(mappedBy: 'fullName', targetEntity: Recipient::class)]
    private Collection $recipients;

    #[ORM\OneToMany(mappedBy: 'fullName', targetEntity: Sender::class)]
    private Collection $senders;

    public function __construct()
    {
        $this->recipients = new ArrayCollection();
        $this->senders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName): static
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * @return Collection<int, Recipient>
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    public function addRecipient(Recipient $recipient): static
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients->add($recipient);
            $recipient->setFullName($this);
        }

        return $this;
    }

    public function removeRecipient(Recipient $recipient): static
    {
        if ($this->recipients->removeElement($recipient)) {
            // set the owning side to null (unless already changed)
            if ($recipient->getFullName() === $this) {
                $recipient->setFullName(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sender>
     */
    public function getSenders(): Collection
    {
        return $this->senders;
    }

    public function addSender(Sender $sender): static
    {
        if (!$this->senders->contains($sender)) {
            $this->senders->add($sender);
            $sender->setFullName($this);
        }

        return $this;
    }

    public function removeSender(Sender $sender): static
    {
        if ($this->senders->removeElement($sender)) {
            // set the owning side to null (unless already changed)
            if ($sender->getFullName() === $this) {
                $sender->setFullName(null);
            }
        }

        return $this;
    }

    public function getFullNameArray(): array
    {
        return [
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'middleName' => $this->getMiddleName(),
        ];
    }
}
