<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
//use Symfony\Component\Serializer\Serializer;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TodoListController extends AbstractController
{
    /**
     * @Route("/", name="todo_list")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to Todo  api!',
        ]);
    }
    /**
     * @Route("/new", name="todo_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        if ($request->request->get('todo')) {
            $todo = new Todo();
            $todo->setTodo($request->request->get('todo'));
            $todo->setDone(false);
            $todo->setCreatAt(new  \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();
            return $this->json([
                'code' => 'success',
                'msg' => 'Add task',
            ], 201);
        }
        return $this->json([
            'code' => 'success',
            'msg' => 'Invalid information send!',
        ], 400);
    }

    /**
     * @Route("/all", name="todo_all", methods={"GET"})
     * @param TodoRepository $repository
     * @return Response
     */
    public function all(TodoRepository $repository): Response
    {
        $todo = $repository->findAll();
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $serializer->serialize($todo, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['todo']]); // Output: {"name":"foo"}
        return $this->json([
            'code' => 'success',
            'msg' => 'all task',
            'todo' => $todo,
        ], 200);
    }

    /**
     * @Route("/show", name="todo_show", methods={"GET"})
     * @param TodoRepository $repository
     * @param Request $request
     * @return Response
     */
    public function show(TodoRepository $repository, Request $request): Response
    {
        if ($request->query->get('id')) {
            $todo = $repository->find($request->query->get('id'));
            if (!$todo)
                return $this->json([
                    'code' => 'success',
                    'msg' => 'Invalid information send!',
                ], 400);
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];

            $serializer = new Serializer($normalizers, $encoders);
            $serializer->serialize($todo, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => []]);
            return $this->json([
                'code' => 'success',
                'msg' => 'show task',
                'todo' => $todo,
            ], 201);
        }
        return $this->json([
            'code' => 'success',
            'msg' => 'Invalid information send!',
        ], 400);
    }

    /**
     * @Route("/edit", name="todo_edit", methods={"GET","POST"})
     * @param Request $request
     * @param TodoRepository $repository
     * @return Response
     */
    public function edit(Request $request, TodoRepository $repository): Response
    {
        if ($request->request->get('todo') && $request->request->get('id')) {
            $todo = $repository->find($request->request->get('id'));
            if (!$todo)
                return $this->json([
                    'code' => 'success',
                    'msg' => 'Invalid information send!',
                ], 400);
            $todo->setTodo($request->request->get('todo'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();
            return $this->json([
                'code' => 'success',
                'msg' => 'Task update',
            ], 200);
        }
        return $this->json([
            'code' => 'success',
            'msg' => 'Invalid information send!',
        ], 400);
    }

    /**
     * @Route("/done", name="todo_done", methods={"GET","POST"})
     * @param Request $request
     * @param TodoRepository $repository
     * @return Response
     */
    public function done(Request $request, TodoRepository $repository): Response
    {
        if ($request->request->get('id')) {
            $todo = $repository->find($request->request->get('id'));
            if (!$todo)
                return $this->json([
                    'code' => 'success',
                    'msg' => 'Invalid information send!',
                ], 400);
            $todo->setDone(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();
            return $this->json([
                'code' => 'success',
                'msg' => 'Task update',
            ], 200);
        }
        return $this->json([
            'code' => 'success',
            'msg' => 'Invalid information send!',
        ], 400);
    }

    /**
     * @Route("/delete", name="todo_delete", methods={"DELETE"})
     * @param Request $request
     * @param TodoRepository $repository
     * @return Response
     */
    public function delete(Request $request, TodoRepository $repository): Response
    {
        if ($request->request->get('id')) {
            $todo = $repository->find($request->request->get('id'));
            if (!$todo)
                return $this->json([
                    'code' => 'success',
                    'msg' => 'Invalid information send!',
                ], 400);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($todo);
            $entityManager->flush();
            return $this->json([
                'code' => 'success',
                'msg' => 'Task delete',
            ], 200);
        }
        return $this->json([
            'code' => 'success',
            'msg' => 'Invalid information send!',
        ], 400);
    }

}
