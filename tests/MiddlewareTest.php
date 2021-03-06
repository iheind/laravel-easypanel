<?php


namespace EasyPanelTest;


use EasyPanel\Http\Middleware\isAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test * */
    public function user_is_unauthorized(){
        \Illuminate\Support\Facades\Route::get('/admin')->middleware(isAdmin::class);
        $this->actingAs($this->user)->get('/admin');

        $middleware = new isAdmin();
        $res = $middleware->handle(request(), function (){});

        $this->assertEquals($res->getStatusCode(), 302);
    }

    /** @test * */
    public function user_is_valid(){
        \Illuminate\Support\Facades\Route::get('/admin')->middleware(isAdmin::class);
        $this->user->update([
            'is_superuser' => true
        ]);

        $this->actingAs($this->user->refresh())->get('/admin');
        $middleware = new isAdmin();
        $res = $middleware->handle(request(), function (){});

        $this->assertEquals($res, null);
    }
}