<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DsgQcanswers
 *
 * @ORM\Table(name="DSG_QCAnswers", indexes={@ORM\Index(name="IX_DSG_QCAnswers_CaseID", columns={"CaseID"})})
 * @ORM\Entity(repositoryClass="App\Repository\DsgQcanswersRepository")
 */
class DsgQcanswers
{
    /**
     * @var int
     *
     * @ORM\Column(name="QCAnswerID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $qcanswerid;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="WhenEntered", type="datetime", nullable=true)
     */
    private $whenentered;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Respondent", type="string", length=50, nullable=true)
     */
    private $respondent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="CaseDB", type="string", length=3, nullable=true)
     */
    private $casedb;

    /**
     * @var int|null
     *
     * @ORM\Column(name="CaseID", type="integer", nullable=true)
     */
    private $caseid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="QCQuestionID", type="integer", nullable=true)
     */
    private $qcquestionid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Answer", type="string", length=2, nullable=true)
     */
    private $answer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Comment", type="string", length=256, nullable=true)
     */
    private $comment;

    public function getQcanswerid(): ?int
    {
        return $this->qcanswerid;
    }

    public function getWhenentered(): ?\DateTimeInterface
    {
        return $this->whenentered;
    }

    public function setWhenentered(?\DateTimeInterface $whenentered): self
    {
        $this->whenentered = $whenentered;

        return $this;
    }

    public function getRespondent(): ?string
    {
        return $this->respondent;
    }

    public function setRespondent(?string $respondent): self
    {
        $this->respondent = $respondent;

        return $this;
    }

    public function getCasedb(): ?string
    {
        return $this->casedb;
    }

    public function setCasedb(?string $casedb): self
    {
        $this->casedb = $casedb;

        return $this;
    }

    public function getCaseid(): ?int
    {
        return $this->caseid;
    }

    public function setCaseid(?int $caseid): self
    {
        $this->caseid = $caseid;

        return $this;
    }

    public function getQcquestionid(): ?int
    {
        return $this->qcquestionid;
    }

    public function setQcquestionid(?int $qcquestionid): self
    {
        $this->qcquestionid = $qcquestionid;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }


}
