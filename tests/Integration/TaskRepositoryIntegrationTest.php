<?php

namespace App\Tests\Integration;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryIntegrationTest extends KernelTestCase
{
    private TaskRepository $taskRepository;
    private \Doctrine\ORM\EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        // Récupère la vraie instance de TaskRepository via le container Symfony
        $this->taskRepository = static::getContainer()->get(TaskRepository::class);
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();

        // Nettoyer la table Task avant chaque test
        $this->entityManager->createQuery('DELETE FROM App\Entity\Task')->execute();
    }

    public function testPersistAndRetrieveTask(): void
    {
        $task = new Task();
        $task->setTitle('Integration Test');
        $task->setContent('This is a test task.');
        $task->setIsDone(false);

        // IMPORTANT : Associer un utilisateur à la tâche si user_id est NOT NULL dans la BDD
        // Ici, pour le test, on crée un user factice minimal pour satisfaire la contrainte
        $user = new \App\Entity\User();
        $user->setEmail('testuser@example.com');
        $user->setPassword('password'); // Hash pas nécessaire ici, juste un test

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $task->setUser($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();
        $this->entityManager->clear();

        $retrievedTasks = $this->taskRepository->findBy(['title' => 'Integration Test']);

        $this->assertCount(1, $retrievedTasks);
        $this->assertEquals('Integration Test', $retrievedTasks[0]->getTitle());
        $this->assertFalse($retrievedTasks[0]->isDone());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->createQuery('DELETE FROM App\Entity\Task')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\User')->execute();
        $this->entityManager->close();

        unset($this->entityManager, $this->taskRepository);
    }
}
