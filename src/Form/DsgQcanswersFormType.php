<?php

namespace App\Form;

use App\Entity\DsgQcanswers;
use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\Form\FormEvents,
    Symfony\Component\Form\FormEvent,
    Symfony\Component\OptionsResolver\OptionsResolver,
    Symfony\Component\Form\Extension\Core\Type\ChoiceType,
    Symfony\Component\Form\Extension\Core\Type\FormType,
    Symfony\Component\Form\Extension\Core\Type\TextareaType,
    Symfony\Component\Form\Extension\Core\Type\HiddenType,
    Symfony\Component\Form\Extension\Core\Type\NumberType,
    Symfony\Component\Validator\Constraints as Assert,
    Symfony\Component\Form\Extension\Core\Type\TextType;

class DsgQcanswersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $surveyQuestions = $builder->getForm()->getConfig()->getOption('surveyQuestions');

        foreach($surveyQuestions as $key => $question) {

            if ($question['answertype'] == '1-5') {
                $builder->add(
                    $builder->create($question['qcquestionid'], FormType::class, array('inherit_data' => true, 'label' => $question['question'],
                        'label_attr' => array('class' => 'h5'), 'attr' => array('class' => 'form-group')))
                        ->add('answer', ChoiceType::class, [
                            'label' => false,
                            'choices'  => [
                                '1' => 1,
                                '2' => 2,
                                '3' => 3,
                                '4' => 4,
                                '5' => 5
                            ],
                            'multiple' => false,
                            'expanded' => true,
                            'required' => false,
                            'placeholder' => false,
                        ])
                        ->add('comment', TextType::class, array(
                            'label' => false,
                            'required' => false,
                            'attr' => array('placeholder' => 'Other Remarks', 'class' => 'form-control col-sm-6')
                        ))
                        ->add('qcquestionid', HiddenType::class, [
                            'data' => $question['qcquestionid'],
                        ])


                );


            }


            elseif ($question['answertype'] == 'Num') {
                $builder->add(
                    $builder->create($question['qcquestionid'], FormType::class, array('attr' => array('class' => 'form-group'),'inherit_data' => true, 'label' => $question['question'],
                        'label_attr' => array('class' => 'h5')))
                        ->add('question', FormType::class,[
                            'label' => false,
                            'label_attr' => array('class' => 'col break'),
                            'required' => false,
                            'mapped' => false

                        ])
                        ->add('answer', NumberType::class, array(
                            'label' => false,
                            'required' => false,
                            'attr' => array('placeholder' => 'Optional' , 'class' => 'form-control col-sm-6'),
                            // Setting to true so I have more control where the error message displays in the template
                            'error_bubbling' => true,
                            'invalid_message' => 'The value entered for ' . $question['question'] . ' is invalid.',
                            'constraints' => array(
                                new Assert\Range(array(
                                    'min' => 0,
                                    'max' => 99,
                                    'maxMessage' => 'The value entered for ' . $question['question'] . ' should be {{ limit }} or less.'

                                ))
                            )
                        ))

                        ->add('comment', HiddenType::class, array(
                            'label' => false,
                            'required' => false
                        ))

                        ->add('qcquestionid', HiddenType::class, [
                            'data' => $question['qcquestionid']

                        ])


                );

            }


            elseif ($question['answertype'] == 'Y-N') {
                $builder->add(
                    $builder->create($question['qcquestionid'], FormType::class, array('inherit_data' => true, 'label' => $question['question'],
                        'label_attr' => array('class' => 'h5'), 'attr' => array('class' => 'form-group')))
                        ->add('answer', ChoiceType::class, [
                            'label' => false,
                            // Setting to true so I have more control where the error message displays in the template
                            'error_bubbling' => true,
                            'choices'  => [
                                'Yes' => 'Y',
                                'No' => 'N',
                            ],
                            'multiple' => false,
                            'expanded' => true,
                            'required' => false,
                            'placeholder' => false
                        ])
                        ->add('comment', TextType::class, array(
                            'label' => false,
                            'required' => false,
                            'attr' => array('placeholder' => 'Other Remarks' , 'class' => 'form-control col-sm-6')
                        ))
                        ->add('qcquestionid', HiddenType::class, [
                            'data' => $question['qcquestionid'],
                        ])


                );

            }

            else {
                $builder->add('comment', TextType::class, array(
                    'attr' => array('placeholder' => 'General Comments (optional)','class' => 'form-group col-sm-6 form-control'),
                    'label' => false,
                    'required' => false,
                    'label_attr' => array('class' => 'h5 general-comment')
                ))
                ->add('qcquestionid', HiddenType::class, [
                    'data' => $question['qcquestionid'],
                ])
                ->add('respondent', TextType::class, array(
                    'label' => false,
                    'required' => false,
                    'attr' => array('placeholder' => 'Submitted By (optional)', 'class' => 'form-group col-sm-6 form-control submitted-by')
                ));
            }



        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'surveyQuestions' => array(),
            'data_class' => DsgQcanswers::class,
            'allow_extra_fields' => true
        ));

        $resolver->setRequired(
            'surveyQuestions'
        );


    }
}