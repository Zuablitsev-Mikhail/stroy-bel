<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Order;
use App\Repository\AddressRepository;
use Magento\Framework\Xml\Security;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    private $user;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];
        if ($this->user) {
            $builder
                ->add('address',
                    EntityType::class,
                    [
                        'class' => Address::class,
                        'choice_label' => 'address',
                        'label' => 'Адрес',
                        'query_builder' => function (AddressRepository $er) {
                            return $er->createQueryBuilder('a')
                                ->where('a.user = ' . $this->user);
                        },
                    ]
                );
        } else {
            $builder
                ->add('address',
                    EntityType::class,
                    [
                        'class' => Address::class,
                        'choice_label' => 'address',
                        'label' => 'Адрес',
                    ]
                );
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'user' => null
        ]);
    }
}
