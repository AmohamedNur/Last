<?php

namespace App\Controller;

use App\Entity\Dier;
use App\Form\InsertType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
      #[Route('/login', name: 'app_login')]
public function index(AuthenticationUtils $authenticationUtils): Response
{
             // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

             // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

          return $this->render('login/index.html.twig', [

                          'last_username' => $lastUsername,
                           'error'         => $error,
          ]);
      }

    #[Route('/redirect', name: 'redirect')]
    public function redirectAction(Security $security)
    {
        if ($security->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('app_admin');
        }

        if ($security->isGranted('ROLE_MEMBER')){
            return $this->redirectToRoute('app_member');
        }

        return $this->redirectToRoute('animal');
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(ManagerRegistry $doctrine): Response
    {
        $dier2 = $doctrine->getRepository(Dier::class)->findBy(['user'=>$this->getUser()]);
        $user = $this->getUser();

        return $this->render('login/admin.html.twig',
            [
                'dieren' => $dier2,
                'user' =>$user
            ]);
    }

//    #[Route('/member', name: 'app_member')]
//    public function member(): Response
//    {
//        $this->addFlash('success', 'je bent ingelogd als member');
//        return $this->render('login/member.html.twig');
//    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): never
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/member', name: 'app_member')]
    public function show(ManagerRegistry $doctrine): Response
    {
        $dier = $doctrine->getRepository(Dier::class)->findAll();
        $user = $this->getUser();


         return $this->render('login/member.html.twig',
             [
                 'dieren' => $dier,
                 'user' =>$user
             ]);
    }

    #[Route('/insert', name: 'insert')]
    public function insert(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dier = new Dier();
        $user = $this->getUser();

        $form = $this->createForm(InsertType::class, $dier);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dier = $form->getData();
            $dier->setUser($user);
            $entityManager->persist($dier);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');
        }


         return $this->renderForm('login/insert.html.twig',
             ['form' => $form]);
    }

    #[Route('/delete/{id} ' , name:"delete")]

    public function delete(EntityManagerInterface $em, Request $request ,int $id): Response
    {

        $dier=$em->getRepository(Dier::class)->find($id);
        $em->remove($dier);
        $em->flush();
        return  $this ->redirectToRoute('app_admin');
    }

}
