<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\Comment1Type;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
    
    #[Route('/{id}', name: 'app_comment_delete', methods: ['GET'])]
    public function delete(Comment $comment, CommentRepository $commentRepository): Response
    {
        if ($this->getUser() !== $comment->getAuthor() && $this->getUser()->getRoles() !== 'ROLE_ADMIN' ) {
            throw $this->createAccessDeniedException('Only the owner can delete this comment');
        }
        $commentRepository->remove($comment, true);

        return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
    }
}
