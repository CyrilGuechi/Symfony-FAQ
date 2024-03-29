<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("question")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups("question")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Groups("question")
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("question")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     * @Groups("question")
     */
    private $votes;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBlocked;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question", orphanRemoval=true)
     * @ORM\OrderBy({"isValidated" = "DESC", "createdAt" = "ASC"})
     * @Groups("answer")
     */
    private $answers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="questions", cascade={"persist"})
     * @Groups("question")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("question")
     */
    private $isSolved;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("question")
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("question")
     */
    private $active = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("question")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->createdAt = new \DateTime;
        // On n'estp as forcé d'initialiser la propriété updatedAt
        // car on a autorisé à ce que cette valeur puisse être nulle en base de données
        // $this->updatedAt = new \DateTime;
        $this->votes = 0;
        $this->isBlocked = false;
        $this->isSolved = false;
        // $this->active = true;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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

    public function getVotes(): ?int
    {
        return $this->votes;
    }

    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        // if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        //  }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getIsSolved(): ?bool
    {
        return $this->isSolved;
    }

    public function setIsSolved(bool $isSolved): self
    {
        $this->isSolved = $isSolved;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Fournit le nombre de réponses pour l'API
     * 
     * @Groups("question")
     */
    public function getAnswersNumber()
    {
        return count($this->answers);
    }

    /**
     * Fournit le nombre de réponses pour l'API
     * 
     * @Groups("question")
     */
    public function getAuthor()
    {
        return [
            'id' => $this->user->getId(),
            'username' => $this->user->getUsername(),
        ];
    }
}
