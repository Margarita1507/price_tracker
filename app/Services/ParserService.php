<?php

namespace App\Services;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ParserService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getPriceFromOlx(string $url): string
    {
        try {
            $response = $this->client->request('GET', $url, [
                'verify' => false, // Отключение проверки SSL
            ]);

            $htmlContent = $response->getBody()->getContents();
            $crawler = new Crawler($htmlContent);

            $priceElement = $crawler->filter('.css-90xrc0');
            $price = $priceElement->text();

            return trim(preg_replace('/[^\d.]/', '', $price));
        } catch (\Exception $e) {
            throw new \Exception('Price not found or page could not be processed');
        }
    }
}
