<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 01/12/18
 * Time: 22:18
 */

namespace App\Form;


use App\Entity\UploadTask;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadTaskType
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
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'csrf_token_id'   => 'upload_task',
        ));
    }
}