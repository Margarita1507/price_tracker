<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Subscription;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ProductService
{
    public function subscribeToProduct($url, $email): void
    {
        $parserService = new ParserService();

        Subscription::firstOrCreate([
            'olx_url' => $url,
        ], [
            'email' => $email,
        ]);

        Product::firstOrCreate([
            'olx_url' => $url,
        ], [
            'price' => $parserService->getPriceFromOlx($url),
        ]);
    }

    public function checkPriceChanges(): void
    {
        $products = Product::all();
        $parserService = new ParserService();

        foreach ($products as $product) {
            $price = $parserService->getPriceFromOlx($product->olx_url);

            if ($product->price != $price) {
                $product->update([
                    'price' => $price,
                ]);

                $this->sendEmailsToSubscribers($product->olx_url, $price);
            }
        }
    }

    public function sendEmailsToSubscribers($url, $price): void
    {
        $mailService = new MailService();

        $subscriptions = Subscription::where('olx_url', $url)->get();

        if ($subscriptions->isNotEmpty()) {
            foreach ($subscriptions as $subscription) {
                $mailService->sendEmail($subscription->email, $price);
            }
        }
    }
}
