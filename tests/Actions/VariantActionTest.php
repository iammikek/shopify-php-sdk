<?php

namespace Signifly\Shopify\Test\Resources;

use Exception;
use Signifly\Shopify\Shopify;
use Signifly\Shopify\Test\TestCase;
use Signifly\Shopify\Resources\VariantResource;

class VariantActionTest extends TestCase
{
    /**
     * @test
     * @expectedException Exception
     */
    public function it_requires_parent_to_create()
    {
        $profile = $this->makeGuzzleMockHandlerProfile();
        $shopify = new Shopify($profile);

        $data = [
            'option1' => 'Yellow',
            'price' => '1.00',
        ];

        $shopify->variants()->create($data);
    }

    /**
     * @test
     */
    public function it_can_create_a_variant()
    {
        $profile = $this->makeGuzzleMockHandlerProfile([
            $this->makeGuzzleResponse(201, [], $this->getFixture('variant.json')),
        ]);
        $shopify = new Shopify($profile);

        $data = [
            'option1' => 'Yellow',
            'price' => '1.00',
        ];

        $response = $shopify->variants()->with('products', 1234)->create($data);

        $this->assertInstanceOf(VariantResource::class, $response);
        $this->assertSame(1070325021, $response->id);
        $this->assertCount(1, $this->mockHistory);
        $this->assertSame('products/1234/variants.json', $this->mockHistory[0]['request']->getUri()->getPath());
    }
}