<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChateaVendorTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/registro');

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testResetPassword()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/resetear-contraseña');

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
    }


    public function testUserLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/usuario/login');

        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testRegisterChannelNoRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/registrar-canal');

        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
    }

    public function testConfirmEmailNoRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/confirmar-email');

        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
    }
/*
    public function testRegisterConfirmedNoRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/registro/confirmado');

        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
    }
 */
    /*public function testRegisterFinalizedNoRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/registro/finalizado');

        $this->assertEquals(
            403,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testUserPreferencesNoRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/usuario/preferencias');

        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
    }*/
}
