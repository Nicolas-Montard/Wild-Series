<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }
    #[Route('/list/{id}', requirements: ['id'=>'\d+'], methods: ['GET'] , name: 'show')]
    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with this id found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
         ]);
    }
    #[Route('/new', name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository): Response
    {
        $program = new Program;

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programRepository->save($program, true);
        }

        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{program}/seasons/{season}', requirements: ['program'=>'\d+', 'season'=>'\d+'], methods: ['GET'] , name: 'season_show')]
    public function showSeason(Program $program, Season $season): Response
    {
        if (!$program || !$season) {
            throw $this->createNotFoundException(
                'No program or season found with this id'
            );
        }

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }
    #[Route('/{program}/season/{season}/episode/{episode}', requirements: ['program' =>'\d+', 'season'=>'\d+'], methods: ['GET'], name: 'episode_show')]
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

}