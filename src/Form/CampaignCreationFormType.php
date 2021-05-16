<?php


namespace App\Form;


use App\Entity\Campaign;
use App\Repository\SubjectMatterRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CampaignCreationFormType extends AbstractType
{
    private SubjectMatterRepository $subjectMatterRepository;

    public function __construct(SubjectMatterRepository $subjectMatterRepository)
    {
        $this->subjectMatterRepository = $subjectMatterRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subject_matters = $this->subjectMatterRepository->findAll();
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('subjectMatter', ChoiceType::class, array(
                'choices' => array($subject_matters),
                'choice_label' => function($subjectMatters) { return $subjectMatters->getName(); }
            ))
            ->add('moneyAmount', IntegerType::class)
            ->add('startedAt', DateTimeType::class)
            ->add('finishedAt', DateTimeType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Campaign::class,
        ]);
    }
}
