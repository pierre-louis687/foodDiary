<?php

namespace App\Controller;

use App\Entity\FoodRecord;
//use App\Entity\User;
use App\Form\FoodType;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * @Route("/")
 */

class DiaryController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Environment $twig)
    {
        return new Response($twig->render('diary/index.html.twig'));
    }

    /**
     * @Route("/diary/list", name="diary")
     */
    public function listAction()
    {

        $repository = $this->getDoctrine()->getRepository('App:FoodRecord');

        return $this->render(
            'diary/list.html.twig',
            [
                'records' => $repository->findBy(
                    [
                        'recordedAt' => new \Datetime()
                    ]
                )
            ]
        );
    }

    /**
     * @Route("/diary/add-new-record", name="add-new-record")
     */
    public function addRecordAction(Request $request)
    {
        $foodRecord = new FoodRecord();
        $form = $this->createForm(FoodType::class, $foodRecord);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($foodRecord);
            $em->flush();

            $this->addFlash('success', 'Une nouvelle entrée dans votre journal a bien été ajoutée.');

            return $this->redirectToRoute('add-new-record');
        }

        return $this->render('diary/addRecord.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/diary/record", name="delete-record")
     */
    public function deleteRecordAction(Request $request)
    {
        if (!$record = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('App:FoodRecord')
            ->findOneById($request->request->get('record_id'))) {
            $this->addFlash('danger', "L'entrée du journal n'existe pas.");

            return $this->redirectToRoute('diary');
        }

        $csrf_token = new CsrfToken('delete_record', $request->request->get('_csrf_token'));

        if ($this->get('security.csrf.token_manager')->isTokenValid($csrf_token)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($record);
            $em->flush();

            $this->addFlash('success', "L'entrée a bien été supprimée du journal.");
        } else {
            $this->addFlash('error', 'An error occurred.');
        }

        return $this->redirectToRoute('diary');
    }
}
