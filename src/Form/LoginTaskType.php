<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 23/03/18
 * Time: 15:12
 */

namespace App\Form;


use App\Entity\LoginTask;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class)
            ->add('client_login', TextType::class)
            ->add('send', SubmitType::class, array(
                'label' => 'send',
                'attr' => array(
                    'class' => 'btn btn-primary',
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => LoginTask::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            // a unique key to help generate the secret token
            'csrf_token_id'   => 'authenticate',
        ));
    }
}