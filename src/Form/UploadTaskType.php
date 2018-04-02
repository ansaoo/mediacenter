<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 10/02/18
 * Time: 23:48
 */

namespace App\Form;

use App\Entity\UploadTask;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file',FileType::class, array(
                'multiple' => true,
            ))
//            ->add('send',SubmitType::class, array('label' => 'Send'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UploadTask::class,
        ));
    }
}