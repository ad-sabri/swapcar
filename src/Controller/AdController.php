<?php

namespace App\Controller;

use ArrayObject;
use App\Entity\Ad;
use App\Entity\Fuel;
use App\Entity\User;
use App\Form\AdType;
use App\Entity\Images;
use App\Entity\Comment;
use App\Entity\Gearbox;
use App\Entity\Category;
use App\Form\CommentType;
use Cocur\Slugify\Slugify;
use PhpParser\Node\Stmt\Break_;
use App\Repository\AdRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AdController extends AbstractController
{
    /**
     * @Route("/ad", name="ad")
     */
    public function index(AdRepository $repo, Request $request): Response
    {
        $ads = $repo->findAll();
        //dd($ads);

        return $this->render('ad/index.html.twig', [
            'controller_name' => 'AdController',
            'ads' => $ads
        ]);
    }

    /**
     * @Route("/ad/search", name="ad_search")
     */
    public function checkData(Request $request)
    {
        //Formulaire pour entrer les dates
        $form = $this->createFormBuilder()
            ->setMethod('GET')
            ->add('startDate', DateType::class, ['label' => 'Date de début', 'widget' => "single_text"])
            ->add('endDate', DateType::class, ['label' => 'Date de fin', 'widget' => "single_text"])
            ->add('save', SubmitType::class, ['label' => 'Rechercher une annonce'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dates = $form->getData();

            return $this->redirectToRoute("ad_results", $request->query->all());
        }

        return $this->render('ad/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/booking/results", name="ad_results")
     */

    public function results(Request $request, AdRepository $repo, PaginatorInterface $paginator)
    {
        $ads = $repo->findAll();
        $data = $request->query->all();
        //Tableau qui va representer chaque date de la periode en Datetime
        $reservation = [];
        $days = [];

        //Séparation en deux pour caluler les jours intermédiaires
        $start =  $data["form"]['startDate'];
        $end = $data["form"]['endDate'];

        //Conversion en timestamp pour calcul de l'intervalle
        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);

        $intervalle = range(
            $startTimestamp,
            $endTimestamp,
            24 * 60 * 60
        );

        //Transormation de la periode en datetime
        $days = array_map(function ($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $intervalle);

        $reservation = array_merge($reservation, $days);

        //Conversion du tableau de reservation en string pour comparer aux annonces

        $stringReservation = [];

        $stringReservation = array_map(function ($day) {
            return $day->format('Y-m-d');
        }, $reservation);

        $period = (count($stringReservation));

        $libres = [];

        foreach ($ads as $ad) {

            $unbookableDays = $ad->getNotAvaibleDays();

            if (empty(array_intersect($stringReservation, $unbookableDays))) {

                array_push($libres, $ad);
            }
        }

        $libres = $paginator->paginate(
            $libres, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        return $this->render('ad/results.html.twig', [
            'reservation' => $reservation,
            'ads' => $ads,
            'ad' => $ad,
            'libres' => $libres,
            'start' => $start,
            'period' => $period

        ]);
    }

    /**
     * @Route("/ad/create", name="ad_create") 
     * @IsGranted("ROLE_USER")
     */

    public function create(FormFactoryInterface $factory, Request $request, EntityManagerInterface $em, Security $security, UserInterface $user)
    {

        $slugify = new Slugify();

        $builder = $factory->createBuilder(AdType::class, null);

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ad = $form->getData();

            $title = $form->get('title')->getData();

            $slug = $slugify->slugify($title);
            $images = $form->get('images')->getData();

            foreach ($images as $image) {

                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                $img = new Images();
                $img->setName($fichier);
                $ad->addImage($img);
            }

            $ad->setSlug($slug);
            $ad->setAuthor($user);

            $em->persist($ad);
            $em->flush();

            $this->addFlash('success', 'Votre annonce est crée !');

            return $this->redirectToRoute("ad_show", [
                'slug' => $ad->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('ad/create.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/ad/{slug}", name="ad_show", priority=-1)
     */
    public function show($slug, AdRepository $adRepository, Request $request, EntityManagerInterface $em)
    {

        $ad = $adRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$ad) {
            throw new NotFoundHttpException("Cette annonce n'existe pas");
        }

        // Zone Commentaire

        $comment = new Comment;

        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {

            //$comment->setAuthor($user);
            $comment->setAd($ad);

            $parentid = $commentForm->get("parentid")->getData();

            if ($parentid != null) {
                $parent = $em->getRepository(Comment::class)->find($parentid);
            }

            $images = $ad->getImages();
            $comment->setParent($parent ?? null);

            //dd($comment);

            $em->persist($comment);

            $em->flush();

            return $this->redirectToRoute('ad_show', ['slug' => $ad->getSlug()]);
        }

        return $this->render('ad/show.html.twig', [
            'slug' => $slug,
            'ad' => $ad,
            'commentForm' => $commentForm->createView()
        ]);
    }
}
