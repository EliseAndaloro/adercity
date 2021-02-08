<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
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
     * Return the view to see all photos, there are 8photos/page
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
     * Return the view to add a photo or if the form is submitted it persists data and return the view to see all photos
     * @Route("/add", name="photo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Save img in public/uploads directory with a unique name
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
     * Show a photo 
     * @Route("/{slug}", name="photo_show", methods={"GET"})
     */
    public function show(Photo $photo): Response
    {
        return $this->render('photo/show.html.twig', [
            'photo' => $photo,
        ]);
    }


    /**
     * Allowed only for admin
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
