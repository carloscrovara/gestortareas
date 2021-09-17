<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;

class TaskController extends AbstractController
{
    public function index()
    {
		// Prueba de entidades y relaciones
		$em = $this->getDoctrine()->getManager();
		$task_repo = $this->getDoctrine()->getRepository(Task::class);
		$tasks = $task_repo->findBy([], ['id' => 'DESC']);
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }
	
	public function detail(Task $task){
		if(!$task){
			return $this->redirectToRoute('tasks');
		}
		return $this->render('task/detail.html.twig',[
			'task' => $task
		]);
	}
	
	public function creation(Request $request, UserInterface $user){
		$task = new Task();
		$form = $this->createForm(TaskType::class, $task);
		
		$form->handleRequest($request);
		
		if($form->isSubmitted() && $form->isValid()){
            // Modificando el objeto para guardarlo
			$task->setCreatedAt(new \Datetime('now'));
			$task->setUser($user);

            // Guardar usuario y tarea en DB
			$em = $this->getDoctrine()->getManager();
			$em->persist($task);
			$em->flush();
			
            // Redirigir a la ruta detalle de tarea
			return $this->redirect($this->generateUrl('task_detail', ['id' => $task->getId()]));
		}
		
		return $this->render('task/creation.html.twig',[
			'form' => $form->createView()
		]);
	}
	
	public function myTasks(UserInterface $user){
		$tasks = $user->getTasks();

		return $this->render('task/my-tasks.html.twig',[
			'tasks' => $tasks 
		]);	
	}
	
	public function edit(Request $request, UserInterface $user, Task $task){
		if(!$user || $user->getId() != $task->getUser()->getId()){
			return $this->redirectToRoute('tasks');
		}
		
        $form = $this->createForm(TaskType::class, $task);
		
		$form->handleRequest($request);
		
		if($form->isSubmitted() && $form->isValid()){
			// Guardar tarea modificada en DB
            $em = $this->getDoctrine()->getManager();
			$em->persist($task);
			$em->flush();
			
            // Redirigir a la ruta detalle de tarea
			return $this->redirect($this->generateUrl('task_detail', ['id' => $task->getId()]));
		}
		
		return $this->render('task/creation.html.twig',[
			'edit' => true,
			'form' => $form->createView()
		]);
	}
	
	public function delete(UserInterface $user, Task $task){
		if(!$user || $user->getId() != $task->getUser()->getId()){
			return $this->redirectToRoute('tasks');
		}
		
		if(!$task){
			return $this->redirectToRoute('tasks');
		}
		
        // Elimina tarea en DB
		$em = $this->getDoctrine()->getManager();
		$em->remove($task);
		$em->flush();
		
        // Redirigir a la ruta tareas
		return $this->redirectToRoute('tasks');
	}
}