<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DsgQcquestions
 *
 * @ORM\Table(name="DSG_QCQuestions", indexes={@ORM\Index(name="IX_DSG_QCQuestions_Category_ListOrder", columns={"Category", "ListOrder"})})
 * @ORM\Entity(repositoryClass="App\Repository\DsgQcquestionsRepository")
 */
class DsgQcquestions
{
    /**
     * @var int
     *
     * @ORM\Column(name="QCQuestionID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $qcquestionid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Category", type="string", length=15, nullable=true)
     */
    private $category;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Question", type="string", length=60, nullable=true)
     */
    private $question;

    /**
     * @var string|null
     *
     * @ORM\Column(name="AnswerType", type="string", length=10, nullable=true)
     */
    private $answertype;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ListOrder", type="integer", nullable=true)
     */
    private $listorder;

    public function getQcquestionid(): ?int
    {
        return $this->qcquestionid;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswertype(): ?string
    {
        return $this->answertype;
    }

    public function setAnswertype(?string $answertype): self
    {
        $this->answertype = $answertype;

        return $this;
    }

    public function getListorder(): ?int
    {
        return $this->listorder;
    }

    public function setListorder(?int $listorder): self
    {
        $this->listorder = $listorder;

        return $this;
    }


}
