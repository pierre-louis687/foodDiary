<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HTTPFoundation\Response;

class DiaryControllerTest extends WebTestCase
{
    private $client = null;
  
  public function setUp():void
  {
    $this->client = static::createClient();
  }
  
  public function testHomepageIsUp()
  {
    $this->client->request('GET', '/');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }  

  public function testListIsUp()
  {
    $this->client->request('GET', '/diary/list');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }

  public function testAddrecordIsUp()
  {
    $this->client->request('GET', '/diary/add-new-record');
    
    static::assertEquals(
      Response::HTTP_OK,
      $this->client->getResponse()->getStatusCode()
    );
  }
  public function testDeleterecordIsUp()
  {
    $this->client->request('GET', '/diary/record');
    
    static::assertEquals(
      302,
      $this->client->getResponse()->getStatusCode()
    );
  }

  public function testHomepage()
    {
        $crawler = $this->client->request('GET', '/');

        //$this->assertSame(1, $crawler->filter('html:contains("Bienvenue sur FoodDiary !")')->count());
        $this->assertSame(1, $crawler->filter('h1')->count());
    }

    public function testAddRecord()
    {
        $crawler = $this->client->request('GET', '/diary/add-new-record');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['food[username]'] = 'John Doe';
        $form['food[entitled]'] = 'Plat de pâtes';
        $form['food[calories]'] = 600;
        $crawler = $this->client->submit($form);

        $this->assertEquals('App\Controller\DiaryController::addRecordAction', $this->client->getRequest()->attributes->get('_controller'));
    }

    public function testAddRecord2()
    {
        $crawler = $this->client->request('GET', '/diary/add-new-record');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['food[username]'] = 'John Doe';
        $form['food[entitled]'] = 'Plat de pâtes';
        $form['food[calories]'] = 600;
        $crawler = $this->client->submit($form);

        $this->client->followRedirect();

        echo $this->client->getResponse()->getContent();
    }

    public function testAddRecord3()
    {
        $crawler = $this->client->request('GET', '/diary/add-new-record');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['food[username]'] = 'John Doe';
        $form['food[entitled]'] = 'Plat de pâtes';
        $form['food[calories]'] = 600;
        $this->client->submit($form);

        $crawler = $this->client->followRedirect(); // Attention à bien récupérer le crawler mis à jour

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    public function testList()
    {
        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Voir tous les rapports')->link();
        $crawler = $this->client->click($link);

        $info = $crawler->filter('h1')->text();
        $info = $string = trim(preg_replace('/\s\s+/', ' ', $info)); // On retire les retours à la ligne pour faciliter la vérification

        $this->assertSame("Tous les rapports Tout ce qui a été mangé !", $info);
    }


}