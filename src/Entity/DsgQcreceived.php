<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DsgQcReceived
 *
 * @ORM\Table(name="DSG_QCReceived")
 * @ORM\Entity
 */
class DsgQcreceived
{

    /**
     * @var int
     *
     * @ORM\Column(name="QCReceovedID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $qcreceivedid;

    /**
     * @var string
     *
     * @ORM\Column(name="WhenEntered", type="string", nullable=false)
     */
    private $whenentered;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Respondent", type="string", length=50, nullable=true)
     */
    private $respondent;

    /**
     * @var string
     *
     * @ORM\Column(name="CaseDB", type="string", length=3, nullable=false)
     */
    private $casedb;

    /**
     * @var int
     *
     * @ORM\Column(name="CaseID", type="integer", nullable=false)
     */
    private $caseid;

    /**
     * @var int
     *
     * @ORM\Column(name="NotificationProcessed", type="integer", nullable=false)
     */
    private $notificationprocessed;


    public function getQcreceivedId(): ?int
    {
        return $this->qcreceivedid;
    }

    public function getWhenentered(): ?string
    {
        return $this->whenentered;
    }

    public function setWhenentered(?string $whenentered): self
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

    public function getNotificationProcessed(): ?int
    {
        return $this->notificationprocessed;
    }

    public function setNotificationProcessed(?int $notificationprocessed): self
    {
        $this->notificationprocessed = $notificationprocessed;

        return $this;
    }

}