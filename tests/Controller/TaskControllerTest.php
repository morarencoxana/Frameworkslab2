<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testCreateTask()
    {
        $client = static::createClient();

        // Имитируйте запрос GET на страницу создания задачи
        $crawler = $client->request('GET', '/task/create');

        // Проверьте, что страница создания задачи возвращается успешно
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Здесь вы можете добавить дополнительные проверки для формы

        // Создайте форму с данными и имитируйте отправку POST-запроса
        $formData = [
            'task_type' => 'Тип задачи',
            'task_description' => 'Описание задачи',
            // Добавьте другие поля формы, если они есть
        ];

        $client->submitForm('Создать', $formData);

        // Проверьте, что задача была создана успешно
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

}
