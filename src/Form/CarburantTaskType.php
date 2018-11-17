<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 17/10/18
 * Time: 22:11
 */

namespace App\Form;


use App\Entity\Carburant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarburantTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimeType::class, array(
                'date_widget' => 'single_text',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'input' => 'datetime'
            ))
            ->add('compteur', TextType::class)
            ->add('kilometre', TextType::class)
            ->add('litre', TextType::class)
            ->add('prix', TextType::class)
            ->add('lieu', TextType::class)
            ->add('station', TextType::class)
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'Sans Plomb 98' => 'SP98',
                    'Diesel' => 'GO'
                )
            ))
            ->add('voitureId', ChoiceType::class, array(
                'choices' => array(
                    'Laguna' => "2",
                    'Celica' => "1"
                )
            ))
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Carburant::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'csrf_token_id'   => 'fuel',
        ));
    }
}