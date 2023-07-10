<?php

namespace Tests\Feature\Http\Controllers\User;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function happyPath()
    {
        Artisan::call('db:seed');
        Auth::loginUsingId(3);

        /** @var Product $randomProduct */
        $randomProduct = (new Product())->newQuery()
            ->inRandomOrder()
            ->limit(1)
            ->first();

        $this->post('/cart', ['product_id' => (string)$randomProduct->id, 'quantity' => 1])
            ->assertRedirect();

        $this->assertDatabaseCount(Cart::class, 1);

        /** @var Cart $cart */
        $cart = (new Cart())->newQuery()->first();

        $this->assertSame($randomProduct->id, $cart->product_id);

        $this->post('/order', ['cart_id' => (string)$cart->id, 'quantity' => 1])
            ->assertRedirect();

        /** @var Order $order */
        $order = (new Order())->newQuery()->first();

        $this->assertDatabaseCount(Order::class, 1);
        $this->assertDatabaseCount(Cart::class, 0);

        $this->assertSame($randomProduct->id, $order->product_id);
    }
}
