<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\User;
use App\Form\RegisterType;

class UserController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
		// Crear formulario
		$user = new User();
		$form = $this->createForm(RegisterType::class, $user);
		
		// Rellenar el objeto con datos del form
		$form->handleRequest($request);
		
		// Comprobar si el form se ha enviado
		if($form->isSubmitted() && $form->isValid()){
			// Modificando el objeto para guardarlo
			$user->setRoles('ROLE_USER');
			$user->setCreatedAt(new \Datetime('now'));
			
			// Cifrar contraseña
			$encoded = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($encoded);
			
			// Guardar usuario
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			
            // Redirigir a la ruta tareas
			return $this->redirectToRoute('tasks');
		}
		
        return $this->render('user/register.html.twig', [
			'form' => $form->createView()
        ]);
    }
	
	public function login(AuthenticationUtils $autenticationUtils){
		$error = $autenticationUtils->getLastAuthenticationError();
		
		$lastUsername = $autenticationUtils->getLastUsername();
		
		return $this->render('user/login.html.twig', array(
			'error' => $error,
			'last_username' => $lastUsername
		));
	}
}
