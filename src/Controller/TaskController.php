<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/task", name: "app_task_")]
class TaskController extends AbstractController
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $task = new Task;
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->taskService->createTask($task);
            $this->addFlash('success', 'Задача успешно создана');
            return $this->redirectToRoute('app_task_list');
        }

        return $this->render('task/create.html.twig', [
            'task_form' => $form,
        ]);
    }

    #[Route("/", name: "list")]
    public function list(): Response
    {
        $tasks = $this->taskService->getAllTasks();
        return $this->render("task/list.html.twig", [
            'tasks' => $tasks,
        ]);
    }

    #[Route("/view/{id}", name: "view")]
    public function view(int $id): Response
    {
        $task = $this->getTaskOrThrow($id);
        return $this->render("task/view.html.twig", ['task' => $task]);
    }

    #[Route("/delete/{id}", name: "delete")]
    public function delete(int $id): Response
    {
        $task = $this->getTaskOrThrow($id);
        $this->taskService->deleteTask($task);
        $this->addFlash('success', "Задача с номером {$id} успешно удалена");
        return $this->redirectToRoute('app_task_list');
    }

    #[Route("/update/{id}", name: "update")]
    public function update(int $id, Request $request): Response
    {
        $task = $this->getTaskOrThrow($id);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->updateTask($task);
            $this->addFlash('success', "Задача с номером {$id} была обновлена");
            return $this->redirectToRoute('app_task_view', ['id' => $id]);
        }

        return $this->render('task/update.html.twig', [
            'task_form' => $form,
            'task' => $task,
        ]);
    }

    private function getTaskOrThrow(int $id): Task
    {
        $task = $this->taskService->getTaskById($id);

        if (!$task) {
            throw $this->createNotFoundException("Задача с номером {$id} не была найдена");
        }

        return $task;
    }
}
