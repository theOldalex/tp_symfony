<?php

// Fichier src/Form/ProductType.php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('label', TextType::class)
			->add('prix', MoneyType::class, [
				'invalid_message' => 'Rentre un prix, stp'
			])
			->add('photoPrincipale', UrlType::class)
			->add('description', TextareaType::class, [
				'required' => false
			])
			->add('submit', SubmitType::class, [
				'label' => 'Envoyer'
			]);
	}
}
