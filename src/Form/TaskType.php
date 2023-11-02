<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Task;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Название книги",
                'attr' => [
                    'placeholder' => "Введите название книги",
                ],
                'constraints' => [
                    new Length(
                        min: 5,
                        max: 100,
                        minMessage: 'Название должно состоять минимум из {{ limit }} символов',
                        maxMessage: 'Название должно быть максимум из {{ limit }} символов'
                    ),
                    new Regex(
                        pattern: "/[A-Za-z]*/",
                        message: 'В названии не должно быть цифр'

                    ),
                    new NotBlank(
                        message: 'Название не может быть пустым'
                    )
                ]
            ])
            ->add('description', TextType::class, [
                'label' => "Описание книги",
                'attr' => [
                    'placeholder' => "Введите описание книги",
                ],
                'constraints' => [
                    new Length(
                        min: 5,
                        max: 100,
                        minMessage: 'Описание должно состоять минимум из {{ limit }} символов',
                        maxMessage: 'Описание должно быть максимум из {{ limit }} символов'
                    )
                ]
            ])
            ->add('result', CheckboxType::class, [
                'label' => "Завершено",
            ])
            ->add('date', DateTimeType::class, [
                'date_label' => 'Дата',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите категорию'
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Добавить",

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
