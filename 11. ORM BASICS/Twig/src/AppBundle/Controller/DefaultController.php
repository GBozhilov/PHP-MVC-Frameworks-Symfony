<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
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

    /**
     * @Route("/orm")
     */
    public function ormAction()
    {
        $product = new Product();
        $product->setName('Coca Cola' . ' ' . random_int(1, 10));
        $product->setPrice(2.35 + random_int(1, 10));
        $product->setDescription('Description Alabala');
        $product->setCreateDate(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->render('default/orm.html.twig',
            ['product' => $product]);
    }

    /**
     * @Route("/orm_get")
     */
    public function getProduct()
    {
        $product = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->findByNameAndPrice();

        return $this->render('default/orm.html.twig',
            ['product' => $product]);
    }

    /**
     * @Route("/orm_update")
     */
    public function updateProduct()
    {
        $product = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->find(1);

        $product->setName('Pepsi Cola');

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->render('default/orm.html.twig',
            ['product' => $product]);
    }

    /**
     * @Route("/orm_delete")
     */
    public function deleteProduct()
    {
        $product = $this
            ->getDoctrine()
            ->getRepository(Product::class)
            ->find(3);

        if (!$product) {
            throw $this->createNotFoundException('Product not found!');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->render('default/orm.html.twig',
            ['product' => $product]);
    }
}
