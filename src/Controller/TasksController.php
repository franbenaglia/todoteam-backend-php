<?php
 
namespace App\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Tasks;
use Symfony\Component\HttpKernel\Exception\HttpException;
 
 
#[Route('/api/task', name: 'api_')]
class TasksController extends AbstractController
{
    #[Route('/tasks', name: 'tasks_index', methods:['get'] )]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        
        $tasks = $entityManager
            ->getRepository(Tasks::class)
            ->findAll();
    
        $data = [];
    
        foreach ($tasks as $task) {
           $data[] = [
               'id' => $task->getId(),
               'description' => $task->getDescription(),
               'dir' => $task->getDir(),
               'title' => $task->getTitle(),
           ];
        }
    
        return $this->json($data);
    }
    
    
    #[Route('/tasks/{pageNumber}/{pageSize}', name: 'tasks_paginated', methods:['get'] )]
     public function tasksPaginated(EntityManagerInterface $entityManager, int $pageNumber,int $pageSize): JsonResponse
    {
        $currentPage = $pageNumber+1;
        $limit = $pageSize; 

        $paginator = $entityManager->getRepository(Tasks::class)->findPaginated($currentPage, $limit);
        
        $tasks = [];
        
        foreach ($paginator as $task) {
        
        $tasks[] = [
               'id' => $task->getId(),
               'description' => $task->getDescription(),
               'dir' => $task->getDir(),
               'title' => $task->getTitle(),
           ];
              
        }

        $data = [
            'totalRecords' => count($paginator),
            'tasks' => $tasks
        ];
        
        return $this->json($data);
        
    }
  
  
    #[Route('/task', name: 'task_create', methods:['post'] )]
    public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $json = $this->getJson($request);
        
        $task = new Tasks();
        
        $task->setDescription($json['description']);
        $task->setDir($json['dir']);
        $task->setTitle($json['title']);
    
        $entityManager->persist($task);
        $entityManager->flush();
    
        $data =  [
            'id' => $task->getId(),
            'description' => $task->getDescription(),
            'dir' => $task->getDir(),
            'title' => $task->getTitle(),
        ];
            
        return $this->json($data);
    }
  
  
    #[Route('/task/{id}', name: 'task_show', methods:['get'] )]
    public function show(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $task = $entityManager->getRepository(Tasks::class)->find($id);
    
        if (!$task) {
    
            return $this->json('No task found for id ' . $id, 404);
        }
    
       $data =  [
            'id' => $task->getId(),
            'description' => $task->getDescription(),
            'dir' => $task->getDir(),
            'title' => $task->getTitle(),
        ];
            
        return $this->json($data);
    }
  
    #[Route('/task', name: 'task_update', methods:['put', 'patch'] )]
    public function update(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $json = $this->getJson($request);
        
        $id = $json['id'];
        
        $task = $entityManager->getRepository(Tasks::class)->find($id);
    
        if (!$task) {
            return $this->json('No task found for id ' . $id, 404);
        }
    
        $task->setDescription($json['description']);
        $task->setDir($json['dir']);
        $task->setTitle($json['title']);
        
        $entityManager->flush();
    
       $data =  [
            'id' => $task->getId(),
            'description' => $task->getDescription(),
            'dir' => $task->getDir(),
            'title' => $task->getTitle(),
        ];
            
        return $this->json($data);
    }
  
    #[Route('/task/{id}', name: 'task_delete', methods:['delete'] )]
    public function delete(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $task = $entityManager->getRepository(Tasks::class)->find($id);
    
        if (!$task) {
            return $this->json('No task found for id ' . $id, 404);
        }
    
        $entityManager->remove($task);
        $entityManager->flush();
    
        return $this->json('Deleted a task successfully with id ' . $id);
    }
    
    
    
    
    /**
     * @param Request $request
     *
     * @return mixed
     *
     * @throws HttpException
     */
    private function getJson(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(400, 'Invalid json');
        }

        return $data;
    } 
    
}
