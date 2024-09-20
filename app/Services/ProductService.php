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

        $product = Product::firstOrCreate([
            'olx_url' => $url,
        ], [
            'price' => $parserService->getPriceFromOlx($url),
        ]);

        Subscription::firstOrCreate([
            'email' => $email,
        ], [
            'product_id' => $product->id,
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

                $this->sendEmailsToSubscribers($product->id, $price);
            }
        }
    }

    public function sendEmailsToSubscribers($productId, $price): void
    {
        $mailService = new MailService();

        $subscriptions = Subscription::where('product_id', $productId)->get();

        if ($subscriptions->isNotEmpty()) {
            foreach ($subscriptions as $subscription) {
                $mailService->sendEmail($subscription->email, $price);
            }
        }
    }
}
