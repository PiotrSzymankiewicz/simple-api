<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api_create', methods: ['POST'])]
    public function create(Request $request, Filesystem $filesystem): JsonResponse
    {
        if (empty($request->getContent())) {
            return new JsonResponse(['error' => 'Content is null'], Response::HTTP_NO_CONTENT);
        }

        $uuid = Uuid::v4();
        $filesystem->dumpFile($this->uploadPath() . $uuid . '.json', $request->getContent());

        return new JsonResponse(['uuid' => $uuid], Response::HTTP_CREATED);
    }

    #[Route('/api/{uuid}', name: 'api_read', methods: ['GET'])]
    public function read(Request $request, Filesystem $filesystem): JsonResponse
    {
        $uuid = $request->attributes->get('uuid');
        $filePath = $this->uploadPath() . $uuid . '.json';

        if (!$filesystem->exists($filePath)) {
            return new JsonResponse(['error' => 'Plik o podanym UUID nie istnieje.'], 404);
        }

        $file = new File($filePath);
        $data = json_decode($file->getContent(), true);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api', name: 'api_list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $finder = new Finder();
        $finder->files()->in($this->uploadPath());

        $sortBy = $request->query->get('sortBy', 'date');
        $sortOrder = $request->query->get('sortOrder', 'asc');

        $files = iterator_to_array($finder, false);

        usort($files, fn($a, $b) => $this->compareFiles($a, $b, $sortBy, $sortOrder));

        return new JsonResponse(array_map([$this, 'formatFile'], $files));
    }

    private function compareFiles(SplFileInfo $a, SplFileInfo $b, string $sortBy, string $sortOrder): int
    {
        if ($sortBy === 'uuid') {
            $result = strcmp($a->getFilename(), $b->getFilename());
        } else {
            $result = $a->getCTime() <=> $b->getCTime();
        }

        return ($sortOrder === 'asc') ? $result : -$result;
    }

    private function formatFile(SplFileInfo $file): array
    {
        return [
            'uuid' => str_replace('.json', '', $file->getFilename()),
            'createAt' => $file->getCTime(),
        ];
    }

    private function uploadPath(): string
    {
        return $this->getParameter('kernel.project_dir') . '/' . $this->getParameter('upload_file_path');
    }
}