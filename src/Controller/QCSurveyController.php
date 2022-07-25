<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController,
    Symfony\Component\Routing\Annotation\Route,
    App\Form\DsgQcanswersFormType,
    App\Entity\DsgQcquestions,
    App\Entity\DsgQcanswers,
    App\Entity\DsgQcreceived,
    Doctrine\DBAL\Driver\PDOConnection,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\Form\Extension\Core\Type\SubmitType,
    Symfony\Component\HttpFoundation\Request;


/**
 * Class QCSurveyController
 * @package App\Controller
 */
class QCSurveyController extends AbstractController
{
    /**
     *
     * @Route("/cfb/{code}/{caseId}/{category}/{caseDb}", name="qc_survey")
     */
    public function qcSurvey(Request $request)
    {
        // default entity manager
        $em = $this->getDoctrine()->getManager();

        // Get category from $request
        $category = $request->attributes->get('category');

        // Code from $request
        $code = $request->attributes->get('code');

        // Case Id from $request
        $caseId = $request->attributes->get('caseId');

        $alreadySubmitted = "SELECT TOP 1 c.CaseID
                  FROM Cases c
                  JOIN DSGDoctorRegistrationCodes drc ON drc.doctorid = c.doctorid
                  LEFT OUTER JOIN DSG_QCAnswers dqa ON dqa.CaseID = c.CaseID
                  WHERE dqa.CaseID IS NOT NULL 
                  AND c.CaseID=?
                  AND drc.ValidationCode=?";
        $statement = $em->getConnection()->prepare($alreadySubmitted);
        $statement->bindParam(1, $caseId);
        $statement->bindParam(2, $code);

        $statement->execute();

        $asResult = $statement->fetch();

        // if $asResult is not empty, already submitted message
        if(!empty($asResult)) {
            $this->addFlash('danger', 'Feedback for case # ' . $caseId . ' has already been submitted.');
            return $this->redirectToRoute('already_submitted');
        }


        // Someone might be playing around with the URL params
        $hacking = "SELECT TOP 1 c.CaseID
                  FROM Cases c
                  JOIN DSGDoctorRegistrationCodes drc ON drc.doctorid = c.doctorid
                  LEFT OUTER JOIN DSG_QCAnswers dqa ON dqa.CaseID = c.CaseID
                  WHERE dqa.CaseID IS NULL 
                  AND c.CaseID=?
                  AND drc.ValidationCode=?";
        $statement = $em->getConnection()->prepare($hacking);
        $statement->bindParam(1, $caseId);
        $statement->bindParam(2, $code);

        $statement->execute();

        $result = $statement->fetch();

        // redirect to DSG homepage

        // If it returns a CaseID, then you’re good to go.  No response or null response is a reject.
        if(empty($result)) {
            return new RedirectResponse('https://www.dentalservices.net');
        }

        // Find the questions by category
        $q = $this->getDoctrine()->getRepository(DsgQcquestions::class)->findByCategory($category);


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
                $answers->setCasedb($request->attributes->get('caseDb'));
                $answers->setCaseid($request->attributes->get('caseId'));
                $answers->setQcquestionid($surveyForm->get('qcquestionid')->getData());
                $answers->setAnswer('T');
                $answers->setComment($surveyForm->get('comment')->getData());
                $em->persist($answers);
                $em->flush();

            }


            foreach ($surveyForm as $key => $child) {


                foreach ($surveyForm->get($key)->all() as $c ) {
                   $answers = new DsgQcanswers();

                  //  $data = $c;
                   // dump($data->get('answer')->getData());
                   // dump($surveyForm->get($key)->get('answer')->getData());
                   // dump($surveyForm->get($key)->get('qcquestionid')->getData());
                   // dump($surveyForm->get($key)->get('comment')->getData());



                    $answers->setWhenentered(new \DateTime('America/Chicago'));
                    $answers->setRespondent($surveyForm->get('respondent')->getData());
                    $answers->setCasedb($request->attributes->get('caseDb'));
                    $answers->setCaseid($request->attributes->get('caseId'));
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
            $qcReceived->setCasedb($request->attributes->get('caseDb'));
            $qcReceived->setCaseid($request->attributes->get('caseId'));
            $qcReceived->setNotificationProcessed(0);

            $em->persist($qcReceived);



            $em->flush();

            return $this->redirectToRoute('thank_you');

        }


        return $this->render('survey.html.twig', [
            'category' => $category,
            'case_id' => $caseId,
            'form' => $surveyForm->createView(),
        ]);



    }


}