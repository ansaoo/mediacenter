<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 09/04/18
 * Time: 17:25
 */

namespace App\Form;


use App\Entity\ImgSearchTask;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImgSearchTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', DateType::class)
            ->add('send', SubmitType::class, array(
                'label' => 'Search',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ImgSearchTask::class,
        ));
    }
}