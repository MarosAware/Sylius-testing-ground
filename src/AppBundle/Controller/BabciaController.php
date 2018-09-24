<?php

namespace AppBundle\Controller;

use AppBundle\Services\Aws\AmazonAwsS3Service;
use Aws\S3\S3Client;
use Gaufrette\Adapter\AwsS3;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class BabciaController extends Controller
{

    private $filesystem;

    public function index()
    {
        $factory = $this->container->get('sylius.factory.product');

        $product = $factory->createNew();

        $product->setCode('babcia_product');

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response("<pre>" . var_dump($product));
    }


    public function index2()
    {
        return new Response("<pre>" . var_dump(Intl::getRegionBundle()->getCountryNames()));
    }

    public function index3(AmazonAwsS3Service $awsS3Service, Request $request)
    {
        $someData = array('message' => 'Some default message');
        $form = $this->createFormBuilder($someData)
            ->add('photo', FileType::class, array('label' => 'Upload file:'))
            ->add('submit', SubmitType::class, array('label' => 'Upload'))
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $data = $form->getData();
                $file = $awsS3Service->uploadFile($data['photo']);

                $this->render('@App/file.html.twig', ['file' => $file]);
            }
        }

        return $this->render(
            '@App/form.html.twig',
            array('form'  => $form->createView())
        );


//       $awsS3Service->upload('test.txt', 'content text', ['contentType' => 'text/plain']);
//       $awsS3Service->uploadFile()

    }


    public function index4(Request $request)
    {
        $someData = array('message' => 'Some default message');
        $form = $this->createFormBuilder($someData)
            ->add('photo', FileType::class, array('label' => 'Upload file:'))
            ->add('submit', SubmitType::class, array('label' => 'Upload'))
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                $data = $form->getData();


                $adapter = $this->get('knp_gaufrette.filesystem_map')->get('profile_photos')->getAdapter();
                $file = $adapter->write(basename($data['photo']), file_get_contents($data['photo']));

                $this->render('@App/file.html.twig', ['file' => $file]);
            }
        }

        return $this->render(
            '@App/form.html.twig',
            array('form'  => $form->createView())
        );


//       $awsS3Service->upload('test.txt', 'content text', ['contentType' => 'text/plain']);
//       $awsS3Service->uploadFile()

    }
}
