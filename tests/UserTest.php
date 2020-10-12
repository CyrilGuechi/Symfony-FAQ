<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testRoutesAnonymous($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(302);
    }

    /**
     * @dataProvider provideUrls
     */
    public function testAsAdmin($url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'Claire',
            'PHP_AUTH_PW'   => 'claire',
        ]);
        $crawler = $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @dataProvider provideUrls
     */
    public function testAsUser($url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'Gertrude',
            'PHP_AUTH_PW'   => 'gertrude',
        ]);
        $crawler = $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function provideUrls()
    {
        // On rajoute des / à la fin des routes car Symfony les fait lui-même
        // Sans les /, la réponse est une 301 (redirection)
        // Pour la dernière route, EasyAdmin redirige toujours
        // vers une route avec des paramètres en GET
        // Donc on les ajoute ici pour éviter de se voir répondre une 301 également
        return [
            ['/'],
            ['/question/15'],
            ['/admin/user'],
        ];
    }
}
