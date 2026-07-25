<?php

namespace Tests\Feature;

use App\Livewire\Admin\SettingsManager;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsManagerFlutterwaveTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
        $this->actingAs($admin);

        return $admin;
    }

    public function test_payments_group_shows_the_v4_field_labels_and_not_the_old_v3_ones(): void
    {
        $this->actingAsAdmin();

        Livewire::test(SettingsManager::class)
            ->call('switchGroup', 'payments')
            ->assertSee('Flutterwave Client ID')
            ->assertSee('Flutterwave Client Secret')
            ->assertSee('Flutterwave Encryption Key')
            ->assertSee('Flutterwave Webhook Secret Hash')
            ->assertSee('Paystack Public Key') // untouched, still present
            ->assertSee('Paystack Secret Key') // untouched, still present
            ->assertDontSee('Flutterwave Public Key')
            ->assertDontSee('Flutterwave Secret Key');
    }

    public function test_saving_a_client_id_without_a_client_secret_is_blocked(): void
    {
        $this->actingAsAdmin();

        $keys = array_keys(SettingsManager::GROUPS['payments']['fields']);
        $clientIdIndex = array_search('services.flutterwave.client_id', $keys, true);

        Livewire::test(SettingsManager::class)
            ->call('switchGroup', 'payments')
            ->set("values.{$clientIdIndex}", 'new-client-id')
            ->call('save');

        $this->assertNull(Setting::get('services.flutterwave.client_id'));
    }

    public function test_saving_a_complete_client_id_and_secret_pair_succeeds_and_encrypts_the_secret(): void
    {
        $this->actingAsAdmin();

        $keys = array_keys(SettingsManager::GROUPS['payments']['fields']);
        $clientIdIndex = array_search('services.flutterwave.client_id', $keys, true);
        $clientSecretIndex = array_search('services.flutterwave.client_secret', $keys, true);

        Livewire::test(SettingsManager::class)
            ->call('switchGroup', 'payments')
            ->set("values.{$clientIdIndex}", 'new-client-id')
            ->set("values.{$clientSecretIndex}", 'super-secret-value')
            ->call('save');

        $this->assertSame('new-client-id', Setting::get('services.flutterwave.client_id'));
        $this->assertSame('super-secret-value', Setting::get('services.flutterwave.client_secret'));

        // Encrypted at rest: the raw DB row must not contain the plaintext secret.
        $rawRow = \Illuminate\Support\Facades\DB::table('settings')->where('key', 'services.flutterwave.client_secret')->value('value');
        $this->assertNotSame('super-secret-value', $rawRow);
    }

    public function test_test_connection_button_reports_success_without_persisting_anything(): void
    {
        $this->actingAsAdmin();

        Http::fake(['idp.flutterwave.com/*' => Http::response(['access_token' => 'tok', 'expires_in' => 3600], 200)]);

        $keys = array_keys(SettingsManager::GROUPS['payments']['fields']);
        $clientIdIndex = array_search('services.flutterwave.client_id', $keys, true);
        $clientSecretIndex = array_search('services.flutterwave.client_secret', $keys, true);

        Livewire::test(SettingsManager::class)
            ->call('switchGroup', 'payments')
            ->set("values.{$clientIdIndex}", 'typed-id')
            ->set("values.{$clientSecretIndex}", 'typed-secret')
            ->call('testFlutterwaveConnection')
            ->assertDispatched('cart-toast');

        // Nothing was saved just from testing.
        $this->assertNull(Setting::get('services.flutterwave.client_id'));
        $this->assertNull(Setting::get('services.flutterwave.client_secret'));
    }

    public function test_test_connection_button_reports_failure_for_bad_credentials(): void
    {
        $this->actingAsAdmin();

        Http::fake(['idp.flutterwave.com/*' => Http::response(['error' => 'invalid_client'], 401)]);

        $keys = array_keys(SettingsManager::GROUPS['payments']['fields']);
        $clientIdIndex = array_search('services.flutterwave.client_id', $keys, true);
        $clientSecretIndex = array_search('services.flutterwave.client_secret', $keys, true);

        $component = Livewire::test(SettingsManager::class)
            ->call('switchGroup', 'payments')
            ->set("values.{$clientIdIndex}", 'bad-id')
            ->set("values.{$clientSecretIndex}", 'bad-secret')
            ->call('testFlutterwaveConnection');

        $component->assertDispatched('cart-toast', message: '✗ Authentication failed — check your Client ID and Secret.');
    }
}
