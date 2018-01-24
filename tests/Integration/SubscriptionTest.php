<?php

namespace Tests\Integration;

use Tests\IntegrationTestCase;
use \App\Fee;
use \App\Subscription;

class SubscriptionTest extends IntegrationTestCase {
	public function setUp() {
		parent::setUp();

		$this->runSeeder('FeeSeeder');
		$this->runSeeder('RightSeeder');
		$this->runSeeder('UsergroupSeeder');
		
	}

	/** @test */
	public function it_can_get_a_single_subscription() {
		$this->authAsApi();

		Subscription::create(['title' => 'Beitrag', 'amount' => '5000'])
			->fee()->associate(Fee::first())
			->save();

		$this->getApi('subscription/1')
			->assertSuccess()
			->assertJson(['title' => 'Beitrag',
				'id' => 1,
				'amount' => 5000,
				'fee_id' => 1,
				'fee' => ['title' => 'Voller Beitrag', 'nami_id' => 1]
			]);
	}

	/** @test */
	public function it_can_get_all_subscriptions() {
		$this->authAsApi();

		Subscription::create(['title' => 'Beitrag', 'amount' => '5000'])
			->fee()->associate(Fee::first())
			->save();

		$this->getApi('subscription')
			->assertSuccess()
			->assertJson([['title' => 'Beitrag',
				'id' => 1,
				'amount' => 5000,
				'fee_id' => 1,
				'fee' => ['title' => 'Voller Beitrag', 'nami_id' => 1]
			]]);
	}

	/** @test */
	public function it_can_add_a_subscription() {
		$this->authAsApi();

		$this->postApi('subscription', [
			'title' => 'Beitrag',
			'fee' => 2,
			'amount' => '100,00'
		])
			->assertSuccess();

		$sub = Subscription::first();

		$this->assertEquals('Beitrag', $sub->title);
		$this->assertEquals('Familienbeitrag', $sub->fee->title);
		$this->assertEquals('10000', $sub->amount);
	}

	/** @test */
	public function it_must_not_have_a_fee() {
		$this->authAsApi();

		$this->postApi('subscription', [
			'title' => 'Beitrag',
			'fee' => null,
			'amount' => '100,00'
		])
			->assertSuccess();

		$sub = Subscription::first();

		$this->assertEquals('Beitrag', $sub->title);
		$this->assertNull($sub->fee);
		$this->assertEquals('10000', $sub->amount);
	}

	/** @test */
	public function it_should_validate() {
		$this->withExceptionHandling();

		$this->authAsApi();

		$this->postApi('subscription', [
			'title' => null,
			'fee' => 2,
			'amount' => '100,00'
		])
			->assertValidationFailedWith('title');

		$this->postApi('subscription', [
			'title' => 'Beitrag',
			'fee' => 2,
			'amount' => null
		])
			->assertValidationFailedWith('amount');

		$this->postApi('subscription', [
			'title' => 'Beitrag',
			'fee' => 2,
			'amount' => '44.55'
		])
			->assertValidationFailedWith('amount');
	}

	/** @test */
	public function it_can_update_a_subscription() {
		$this->authAsApi();

		$sub = $this->create('Subscription');

		$this->patchApi('subscription/'.$sub->id, [
			'title' => 'Beitrag',
			'fee' => 2,
			'amount' => '100,00'
		])
			->assertSuccess();

		$this->assertCount(1, Subscription::get());
		$sub = Subscription::where('id', $sub->id)->first();

		$this->assertEquals('Beitrag', $sub->title);
		$this->assertEquals('Familienbeitrag', $sub->fee->title);
		$this->assertEquals('10000', $sub->amount);
	}

	/** @test */
	public function it_validates_the_subscription_on_update() {
		$this->withExceptionHandling();

		$this->authAsApi();

		$sub = $this->create('Subscription');

		$this->patchApi('subscription/'.$sub->id, [
			'title' => null,
			'fee' => 2,
			'amount' => '100,00'
		])
			->assertValidationFailedWith('title');

		$this->patchApi('subscription/'.$sub->id, [
			'title' => 'Beitrag',
			'fee' => 2,
			'amount' => null
		])
			->assertValidationFailedWith('amount');
	}

	/** @test */
	public function it_allows_the_fee_to_be_null_on_update() {
		$this->authAsApi();

		$sub = $this->create('Subscription', ['fee_id' => 2]);

		$this->patchApi('subscription/'.$sub->id, [
			'title' => 'Beitrag',
			'fee' => null,
			'amount' => '100,00'
		])
			->assertSuccess();

		$sub = $sub->fresh(['fee']);

		$this->assertNull($sub->fee);
	}
}
