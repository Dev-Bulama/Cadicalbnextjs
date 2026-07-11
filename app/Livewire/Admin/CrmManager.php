<?php

namespace App\Livewire\Admin;

use App\Models\CrmAutomationRule;
use App\Models\CrmConnection;
use App\Models\CrmFailedJob;
use App\Models\CrmFieldMapping;
use App\Models\CrmSyncLog;
use App\Models\CrmWebhookLog;
use App\Services\Crm\CrmSyncService;
use Livewire\Component;

class CrmManager extends Component
{
    public string $tab = 'overview';

    // Connection setup form
    public bool $showSetupForm = false;

    public string $clientId = '';

    public string $clientSecret = '';

    public string $redirectUri = '';

    // Field mapping form
    public bool $showMappingForm = false;

    public string $mapEntity = 'contact';

    public string $mapCadicalField = '';

    public string $mapCrmField = '';

    public string $mapDirection = 'both';

    // Automation rule form
    public bool $showRuleForm = false;

    public string $ruleName = '';

    public string $ruleDescription = '';

    public string $ruleTriggerEvent = 'order_completed';

    public string $ruleActionType = 'create_deal';

    public function saveConnection(): void
    {
        $data = $this->validate([
            'clientId' => 'required|string|max:255',
            'clientSecret' => 'required|string|max:255',
            'redirectUri' => 'required|url|max:255',
        ]);

        CrmConnection::where('is_active', true)->update(['is_active' => false]);

        CrmConnection::create([
            'provider' => 'zoho',
            'client_id' => $data['clientId'],
            'client_secret' => $data['clientSecret'],
            'redirect_uri' => $data['redirectUri'],
            'is_active' => true,
        ]);

        $this->showSetupForm = false;
        $this->dispatch('cart-toast', message: 'Zoho connection saved — click Connect to authorize');
    }

    public function testConnection(): void
    {
        $connection = $this->activeConnection();
        if (! $connection || ! $connection->access_token) {
            $this->dispatch('cart-toast', message: 'Complete the OAuth connection first');

            return;
        }

        $adapter = app(CrmSyncService::class)->adapterFor($connection);
        $result = $adapter->testConnection();

        $connection->update([
            'health_score' => $result['success'] ? 100 : 0,
            'last_error' => $result['success'] ? null : $result['message'],
        ]);

        $this->dispatch('cart-toast', message: $result['message']);
    }

    public function disconnect(): void
    {
        $connection = $this->activeConnection();
        $connection?->update([
            'is_connected' => false,
            'is_active' => false,
            'access_token' => null,
            'refresh_token' => null,
        ]);

        $this->dispatch('cart-toast', message: 'Disconnected from Zoho CRM');
    }

    public function syncEntity(string $entity): void
    {
        $connection = $this->activeConnection();
        if (! $connection || ! $connection->is_connected) {
            $this->dispatch('cart-toast', message: 'No connected CRM found');

            return;
        }

        try {
            app(CrmSyncService::class)->syncEntity($connection, $entity);
            $connection->update(['last_sync_at' => now()]);
            $this->dispatch('cart-toast', message: ucfirst($entity).' synced');
        } catch (\Throwable $e) {
            $this->dispatch('cart-toast', message: 'Sync failed: '.$e->getMessage());
        }
    }

    public function syncAll(): void
    {
        $connection = $this->activeConnection();
        if (! $connection || ! $connection->is_connected) {
            $this->dispatch('cart-toast', message: 'No connected CRM found');

            return;
        }

        try {
            app(CrmSyncService::class)->syncAll($connection);
            $connection->update(['last_sync_at' => now()]);
            $this->dispatch('cart-toast', message: 'Full sync completed');
        } catch (\Throwable $e) {
            $this->dispatch('cart-toast', message: 'Sync failed: '.$e->getMessage());
        }
    }

    public function saveMapping(): void
    {
        $connection = $this->activeConnection();
        if (! $connection) {
            return;
        }

        $data = $this->validate([
            'mapEntity' => 'required|string',
            'mapCadicalField' => 'required|string|max:100',
            'mapCrmField' => 'required|string|max:100',
            'mapDirection' => 'required|string',
        ]);

        CrmFieldMapping::updateOrCreate(
            ['connection_id' => $connection->id, 'entity' => $data['mapEntity'], 'cadical_field' => $data['mapCadicalField']],
            ['crm_field' => $data['mapCrmField'], 'direction' => $data['mapDirection']]
        );

        $this->reset(['mapCadicalField', 'mapCrmField']);
        $this->showMappingForm = false;
        $this->dispatch('cart-toast', message: 'Field mapping saved');
    }

    public function deleteMapping(int $id): void
    {
        CrmFieldMapping::whereKey($id)->delete();
    }

    public function saveRule(): void
    {
        $connection = $this->activeConnection();
        if (! $connection) {
            return;
        }

        $data = $this->validate([
            'ruleName' => 'required|string|max:255',
            'ruleDescription' => 'nullable|string',
            'ruleTriggerEvent' => 'required|string',
            'ruleActionType' => 'required|string',
        ]);

        CrmAutomationRule::create([
            'connection_id' => $connection->id,
            'name' => $data['ruleName'],
            'description' => $data['ruleDescription'] ?: null,
            'trigger_event' => $data['ruleTriggerEvent'],
            'action_type' => $data['ruleActionType'],
            'is_active' => true,
        ]);

        $this->reset(['ruleName', 'ruleDescription']);
        $this->showRuleForm = false;
        $this->dispatch('cart-toast', message: 'Automation rule created');
    }

    public function toggleRule(int $id): void
    {
        $rule = CrmAutomationRule::findOrFail($id);
        $rule->update(['is_active' => ! $rule->is_active]);
    }

    public function deleteRule(int $id): void
    {
        CrmAutomationRule::whereKey($id)->delete();
    }

    public function retryFailedJob(int $id): void
    {
        $job = CrmFailedJob::findOrFail($id);

        if ($job->retry_count >= $job->max_retries) {
            $job->update(['status' => 'abandoned']);
            $this->dispatch('cart-toast', message: 'Max retries exceeded — job abandoned');

            return;
        }

        $job->update(['status' => 'retrying', 'retry_count' => $job->retry_count + 1]);
        $this->dispatch('cart-toast', message: 'Retry queued');
    }

    private function activeConnection(): ?CrmConnection
    {
        return CrmConnection::where('is_active', true)->first();
    }

    public function render()
    {
        $connection = $this->activeConnection();

        $syncLogs = $connection ? CrmSyncLog::where('connection_id', $connection->id)->latest('started_at')->limit(30)->get() : collect();
        $mappings = $connection ? CrmFieldMapping::where('connection_id', $connection->id)->orderBy('entity')->get() : collect();
        $rules = $connection ? CrmAutomationRule::where('connection_id', $connection->id)->latest()->get() : collect();
        $failedJobs = $connection ? CrmFailedJob::where('connection_id', $connection->id)->whereIn('status', ['pending', 'retrying'])->latest()->get() : collect();
        $webhookLogs = $connection ? CrmWebhookLog::where('connection_id', $connection->id)->latest('received_at')->limit(30)->get() : collect();

        return view('livewire.admin.crm-manager', compact('connection', 'syncLogs', 'mappings', 'rules', 'failedJobs', 'webhookLogs'));
    }
}
