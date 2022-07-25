<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
    Symfony\Component\Routing\Annotation\Route,
    App\Form\DsgQcanswersFormType,
    App\Entity\DsgQcquestions,
    App\Entity\DsgQcanswers,
    App\Entity\DsgQcreceived,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\Form\Extension\Core\Type\SubmitType,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\Extension\Core\Type\HiddenType,
    Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * Class CFBController
 * @package App\Controller
 */
class CFBController extends AbstractController
{

    /**
     *
     * @Route("/cfb/A9B8C7D6E5F/9999999/{caseDb}", name="start_cfb")
     * @param Request $request
     * @return mixed
     *
     */
    public function startCFB(Request $request)
    {

        $caseIdForm = $this->createFormBuilder()
            ->add('case_id', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Enter a Case ID']
            ])
            ->add('caseIdSubmit', SubmitType::class,
                array('attr' => array(
                    'class' => 'btn btn-primary'),
                    'icon' => 'far fa-play-circle',
                    'label' => 'START'
                ))
            ->getForm();

        $caseIdForm->handleRequest($request);

        if ($caseIdForm->isSubmitted() && $caseIdForm->isValid()) {

            $formData = $caseIdForm->getData();

            return new RedirectResponse($this->generateUrl('cfb_survey',
                array(
                    'case_db' =>  $request->attributes->get('caseDb'),
                    'case_id' => $formData['case_id']
                )));

        }

        return $this->render('enter-caseid.html.twig', [
            'form' => $caseIdForm->createView(),
        ]);




    }


    /**
     *
     * @Route("/cfb/survey", name="cfb_survey")
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function CFBSurvey(Request $request)
    {

        // default entity manager
        $em = $this->getDoctrine()->getManager();

        // Case DB, Case Id from $request
        $caseId = $request->query->get('case_id');

        $caseDB =$request->query->get('case_db');


        $getCase = "SELECT * FROM DSG_CFBCases cfb WHERE cfb.caseid=? AND cfb.CaseDB=?";
        $statement = $em->getConnection()->prepare($getCase);
        $statement->bindParam(1, $caseId);
        $statement->bindParam(2, $caseDB);

        $statement->execute();

        $cfbResult = $statement->fetch();

       // dd($cfbResult);

        if($cfbResult === false) {
            return new RedirectResponse('https://www.dentalservices.net');
        }

        $alreadySubmitted = "SELECT TOP 1 c.CaseID
          FROM DSGConsolidatedCases c
          LEFT OUTER JOIN DSG_QCReceived dqa ON dqa.casedb=c.db and dqa.CaseID = c.CaseID
          JOIN DSGConsolidatedDoctors dr ON dr.db=c.db and dr.doctorid = c.doctorid
          WHERE dqa.CaseID IS NOT NULL
          AND c.CaseID=? AND c.db=?";
        $statement = $em->getConnection()->prepare($alreadySubmitted);
        $statement->bindParam(1, $caseId);
        $statement->bindParam(2, $caseDB);

        $statement->execute();

        $asResult = $statement->fetch();

        // if $asResult is not empty, already submitted message
        if(!empty($asResult)) {
            $this->addFlash('danger', 'Feedback for case # ' . $caseId . ' has already been submitted.');
            return $this->redirectToRoute('already_submitted');
        }

        $patientName = $cfbResult['PatientFirst'][0].'****' . ' ' . $cfbResult['PatientLast'][0].'****';

        $drName = $cfbResult['PrintOnInvoice'];

        // Find the questions by category
        $q = $this->getDoctrine()->getRepository(DsgQcquestions::class)->findByCategory($cfbResult['SurveyType']);


        $surveyForm =  $this->createForm(DsgQcanswersFormType::class, null,
            array(
                'surveyQuestions' => $q,
                'method' => 'POST',

            ));

        $surveyForm->add('surveySubmit', SubmitType::class,
            array('attr' => array(
                'class' => 'btn btn-primary'),
                'icon' => 'far fa-play-circle',
                'label' => 'SUBMIT'
            ));

        $surveyForm->handleRequest($request);


        // Submitted Form
        if ($surveyForm->isSubmitted() && $surveyForm->isValid()) {


            // General Comment
            if(!empty($surveyForm->get('comment')->getData())) {
                $answers = new DsgQcanswers();

                $answers->setWhenentered(new \DateTime('America/Chicago'));
                $answers->setRespondent($surveyForm->get('respondent')->getData());
                $answers->setCasedb($caseDB);
                $answers->setCaseid($caseId);
                $answers->setQcquestionid($surveyForm->get('qcquestionid')->getData());
                $answers->setAnswer('T');
                $answers->setComment($surveyForm->get('comment')->getData());
                $em->persist($answers);
                $em->flush();

            }


            foreach ($surveyForm as $key => $child) {


                foreach ($surveyForm->get($key)->all() as $c ) {
                    $answers = new DsgQcanswers();


                    $answers->setWhenentered(new \DateTime('America/Chicago'));
                    $answers->setRespondent($surveyForm->get('respondent')->getData());
                    $answers->setCasedb($caseDB);
                    $answers->setCaseid($caseId);
                    $answers->setQcquestionid($surveyForm->get($key)->get('qcquestionid')->getData());
                    $answers->setAnswer($surveyForm->get($key)->get('answer')->getData());
                    $answers->setComment($surveyForm->get($key)->get('comment')->getData());

                }

                $em->persist($answers);
            }

            // QcReceived

            $qcReceived = new DsgQcreceived();

            $qcReceived->setWhenentered(date("Y-m-d H:i:s"));
            $qcReceived->setRespondent($surveyForm->get('respondent')->getData());
            $qcReceived->setCasedb($caseDB);
            $qcReceived->setCaseid($caseId);
            $qcReceived->setNotificationProcessed(0);

            $em->persist($qcReceived);



            $em->flush();


            return $this->redirectToRoute('thank_you');

        }


        return $this->render('survey.html.twig', [
            'category' => $cfbResult['SurveyType'],
            'case_id' => $caseId,
            'doctor_name' => $drName,
            'patient_name' => $patientName,
            'form' => $surveyForm->createView(),
        ]);



    }




}