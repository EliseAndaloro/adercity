<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\Photo1Type;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/photos")
 */
class PhotoController extends AbstractController
{
    /**
     * @Route("/", name="photo_index", methods={"GET"})
     */
    public function index(PhotoRepository $photoRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $photos = $paginator->paginate($photoRepository->findAll(), $request->query->getInt('page',1), 5);
        return $this->render('photo/index.html.twig', [
            'photos' => $photos,
        ]);
    }

    /**
     * @Route("/add", name="photo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $photo = new Photo();
        $form = $this->createForm(Photo1Type::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            
            $photo->setFilename($fileName);
            
            $delimiter = '-';
            $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $form->get('title')->getData());
	        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
	        $slug = strtolower($slug);
	        $slug = preg_replace("/[\/_|+ -]+/", $delimiter, $slug);
            $slug = trim($slug, $delimiter);
            
            $photo->setSlug($slug);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();

            return $this->redirectToRoute('photo_index');
        }

        return $this->render('photo/new.html.twig', [
            'photo' => $photo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="photo_show", methods={"GET"})
     */
    public function show(Photo $photo): Response
    {
        return $this->render('photo/show.html.twig', [
            'photo' => $photo,
        ]);
    }


    /**
     * @IsGranted("ROLE_ADMIN")
     * 
     * @Route("/{id}", name="photo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Photo $photo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$photo->getId(), $request->request->get('_token'))) {
            $filename = $photo->getFilename();
            unlink($this->getParameter('images_directory').'/'.$filename);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($photo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('photo_index');
    }
}
