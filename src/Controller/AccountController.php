<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    /** 
     * Pour ce  connecter 
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $usernme = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $usernme
        ]);
    }

    /**
     * Pour ce déconnecter 
     * @Route("/logout", name="account_logout")
     * 
     * @return void
     */

    public function logout()
    {
    }

    /**
     * Enregistrement
     * @Route("/register", name="account_register")
     *
     * @return Respons
     */
    public function register(Request $request, UserPasswordHasherInterface  $encoder, EntityManagerInterface $manager)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->hashPassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte bien été crée ! Vous pouvez maintenant vous connecter !"
            );

            return $this->redirectToRoute('account_login');
        }


        return $this->render('account/registraion.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER");
     * 
     * @return Response
     */

    public function profile(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le profil a bien été enregistrée !"
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /** 
     * Pour ce  connecter 
     * @Route("/account/password-update", name="account_password")
     * IsGranted("ROLE_USER")
     */
    public function updatePassword(Request $request, UserPasswordHasherInterface  $encoder, EntityManagerInterface $manager)
    {
        $passordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passordUpdate);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($passordUpdate->getOldPassword(), $user->getHash())) {
                //une erreur personelle
                $form->get('OldPassword')->addError(new FormError("le mot de passe que vous avez tapé n'est pas correct"));
            } else {
                $newPassword = $passordUpdate->getNewPassword();
                $hash = $encoder->hashPassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('homepage');
            }
        }


        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Undocumented function
     * 
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }


    /**
     * Pour afficher le nombre des réservations 
     * @Route("/account/bookings", name="account_bookings")
     *
     * @return Response
     */
    public function bookings()
    {
        return $this->render('account/bookings.html.twig');
    }
}
