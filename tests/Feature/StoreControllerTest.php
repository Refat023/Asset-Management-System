<?php

namespace Tests\Feature;

use App\Http\Controllers\StoreController;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        DB::purge('sqlite');

        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->nullable();
            $table->string('asset_type')->nullable();
            $table->string('model')->nullable();
            $table->string('checkstatus')->nullable();
            $table->timestamps();
        });

        Schema::create('wast_products', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->nullable();
            $table->string('asset_type')->nullable();
            $table->string('model')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('description')->nullable();
            $table->string('asset_sl_no')->nullable();
            $table->string('date')->nullable();
            $table->string('note')->nullable();
            $table->string('others')->nullable();
            $table->string('others1')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('wast_products');
        Schema::dropIfExists('stores');

        parent::tearDown();
    }

    public function test_wastproduct_creation_marks_matching_store_as_waste(): void
    {
        DB::table('stores')->insert([
            'asset_tag' => 'BX-1218',
            'asset_type' => 'Laptop',
            'model' => 'ThinkPad',
            'checkstatus' => 'INSTOCK',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $request = new Request([
            'asset_tag' => 'BX-1218',
            'asset_type' => 'Laptop',
            'model' => 'ThinkPad',
            'purchase_date' => '2024-01-01',
            'description' => 'Damaged',
            'asset_sl_no' => 'SL-100',
            'date' => '2024-01-15',
            'note' => 'Broken screen',
            'others' => 'BHML INDUSTRIES LTD',
            'others1' => null,
        ]);

        $response = (new StoreController())->wastproduct_store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertDatabaseHas('wast_products', ['asset_tag' => 'BX-1218']);
        $this->assertSame('Wast Products', Store::where('asset_tag', 'BX-1218')->value('checkstatus'));
    }
}
