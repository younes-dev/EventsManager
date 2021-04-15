<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Service\ColorInterpreterService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class MainController extends AbstractController
{

    /**
     * @var ColorInterpreterService
     */
    private ColorInterpreterService $interpreter;

    public function __construct(ColorInterpreterService $interpreter)
    {
        $this->interpreter = $interpreter;
    }

    /**
     * @Route("/", name="main")
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository): Response
    {
//        dd($this->interpreter->colorConverter('#c43131'));
//        $brightness= ($this->interpreter->brightness('#c43131',100));
//        dd($brightness);

//        $checker= ($this->interpreter->get_brightness("#17160d") > 130) ? 'White' :'Black';
//        dd($checker);


        $events=$eventRepository->findAll();
        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStartAt()->format('Y-m-d H:i:s'),
                'end' => $event->getEndAt()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
            ];
        }
        $data = json_encode($rdvs);

//dd($data);
        return $this->render('main/index.html.twig', compact('data'));

    }

    /**
     * @Route("/inspect", name="inspect")
     * https://stackoverflow.com/questions/58780368/php-get-brighttest-hex-color-from-array-with-hex-colors
     */
    public function imageInspectorColor(): Response
    {
        $listImages = [ 'https://cdn2.clc2l.fr/t/R/e/Red-Dead-Redemption-II-Yq75UV.jpeg',
         'https://www.telerama.fr/sites/tr_master/files/e7e8d89e-4993-4f66-a7d1-b09f1f4d1413_2.jpg',
         'https://www.media2.hw-static.com/wp-content/uploads/mission-impossible-iii-movie-stills-tom-cruise_3480588-400x305.jpeg',
        'https://gs2-sec.ww.prod.dl.playstation.net/gs2-sec/appkgo/prod/CUSA03041_00/15/i_2efe1b71a037233f60cec3b41a18d69c02bcff5fd0c895c212d44f37883dbaf8/i/icon0.png',
        'https://i0.wp.com/giphygifs.s3.amazonaws.com/media/SaQp5H5qX3ums/giphy.gif',
        'https://s1.1zoom.me/b5548/425/Waves_Closeup_Ocean_Sea_518011_1600x1200.jpg',
        'https://wi.wallpapertip.com/wsimgs/2-21975_best-nature-wallpapers-in-4-k-and-full.png' ];
        $img7 = $listImages[rand(0,7)];
        $palette = $this->interpreter->detectColors($img7, 10, 5);


        return $this->render('main/inspect.html.twig',[
            'palette' => $palette,
            'img' => $img7

        ]);

    }

    /**
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     * @throws \Exception
     */
    public function createOrUpdateEvent(?Event $event, Request $request): Response
    {
        // On récupère les données
        $data = json_decode($request->getContent());
//dd($data);
        if(
            isset($data->title) && !empty($data->title) &&
            isset($data->start) && !empty($data->start) &&
            isset($data->description) && !empty($data->description) &&
            isset($data->backgroundColor) && !empty($data->backgroundColor) &&
            isset($data->borderColor) && !empty($data->borderColor) &&
            isset($data->textColor) && !empty($data->textColor)
        ){
            // Les données sont complètes
            // On initialise un code
            $code = 200;

            // On vérifie si l'id existe
            if(!$event){
                // On instancie un rendez-vous
                $event = new Event();

                // On change le code
                $code = 201;
            }

            // On hydrate l'objet avec les données
            $event->setTitle($data->title);
            $event->setDescription($data->description);
            $event->setStartAt(new DateTime($data->start));
            if($data->allDay){
                $event->setEndAt(new DateTime($data->start));
            }else{
                $event->setEndAt(new DateTime($data->end));
            }
            $event->setAllDay($data->allDay);
            $event->setBackgroundColor($data->backgroundColor);
            $event->setBorderColor($data->borderColor);
            $event->setTextColor($data->textColor);

            $em = $this->getDoctrine()->getManager();
//            dd($event);
            $em->persist($event);
            $em->flush();

            // On retourne le code
            return new Response('Ok', $code);
        }else{
            // Les données sont incomplètes
        return new Response('Données incomplètes '.(json_encode($data, JSON_PRETTY_PRINT)), 404);
        }


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    public function foo($bar){

        if ($bar == 0) {
            echo 0;
        }
        echo "default";


    }



}
