<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/form", name="form")
     */
    public function formAction(Request $request)
    {
        $user = new User();
        $user->setName('Ivan');
        $user->setAge(20);

        $form = $this->createFormBuilder($user)
            ->add('id', NumberType::class)
            ->add('name', TextType::class)
            ->add('age', NumberType::class)
            ->add('description', TextareaType::class)
            ->add('createDate', DateTimeType::class,
                ['widget' => 'single_text'])
            ->add('cityId', ChoiceType::class,
                ['choices' => ['Sofia' => '1', 'Plovdiv' => '2']])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dump($form->getData());
        }

        return $this->render('default/form.html.twig',
            ['myForm' => $form->createView()]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction()
    {
        $users = [];
        $users[] = (object)['firstName' => 'Ivan', 'lastName' => 'Ivanov', 'age' => 11];
        $users[] = (object)['firstName' => 'Georgi', 'lastName' => 'Borisov', 'age' => 45];
        $users[] = (object)['firstName' => 'Petar', 'lastName' => 'Grigorov', 'age' => 21];

        return $this->render('default/test.html.twig',
            ['users' => $users]);
    }

    /**
     * @Route("/best", name="best")
     */
    public function bestAction()
    {
        return $this->render('default/best.html.twig');
    }
}
