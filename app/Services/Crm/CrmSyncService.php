<?php

namespace App\Services\Crm;

use App\Models\Booking;
use App\Models\CrmConnection;
use App\Models\CrmSyncLog;
use App\Models\Institution;
use App\Models\Order;
use App\Models\Referral;
use App\Models\User;

class CrmSyncService
{
    /** @var array<string, string> */
    public const ENTITY_HANDLERS = [
        'contacts' => 'syncUsersToContacts',
        'accounts' => 'syncInstitutionsToAccounts',
        'deals' => 'syncOrdersToDeals',
        'tickets' => 'syncBookingsToTickets',
        'leads' => 'syncReferralsToLeads',
    ];

    public function adapterFor(CrmConnection $connection): ZohoCrmAdapter
    {
        return new ZohoCrmAdapter(
            clientId: (string) $connection->client_id,
            clientSecret: (string) $connection->client_secret,
            redirectUri: (string) $connection->redirect_uri,
            accessToken: $connection->access_token,
            apiDomain: $connection->api_domain,
        );
    }

    public function syncAll(CrmConnection $connection): void
    {
        foreach (array_keys(self::ENTITY_HANDLERS) as $entity) {
            $this->syncEntity($connection, $entity);
        }
    }

    public function syncEntity(CrmConnection $connection, string $entity): void
    {
        $method = self::ENTITY_HANDLERS[$entity] ?? null;
        if (! $method) {
            throw new \InvalidArgumentException("Unknown CRM entity: {$entity}");
        }

        $this->{$method}($connection);
    }

    public function syncUsersToContacts(CrmConnection $connection): void
    {
        $log = $this->startLog($connection, 'contact', 'push');

        try {
            $adapter = $this->adapterFor($connection);
            $users = User::limit(500)->get();

            $synced = 0;
            $failed = 0;
            $errors = [];

            foreach ($users as $user) {
                try {
                    $parts = explode(' ', (string) $user->name, 2);
                    $contact = [
                        'first_name' => $parts[0] ?: $user->name,
                        'last_name' => $parts[1] ?? '-',
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'address' => $user->address,
                        'city' => $user->city,
                        'state' => $user->state,
                        'country' => $user->country,
                    ];

                    $existing = $adapter->searchContacts($user->email);
                    if (! empty($existing)) {
                        $adapter->updateContact($existing[0]['id'], $contact);
                    } else {
                        $adapter->createContact($contact);
                    }
                    $synced++;
                } catch (\Throwable $e) {
                    $failed++;
                    $errors[] = "User {$user->email}: {$e->getMessage()}";
                }
            }

            $this->completeLog($log, $users->count(), $synced, $failed, $errors);
        } catch (\Throwable $e) {
            $this->failLog($log, $e->getMessage());
            throw $e;
        }
    }

    public function syncInstitutionsToAccounts(CrmConnection $connection): void
    {
        $log = $this->startLog($connection, 'account', 'push');

        try {
            $adapter = $this->adapterFor($connection);
            $institutions = Institution::limit(500)->get();

            $synced = 0;
            $failed = 0;
            $errors = [];

            foreach ($institutions as $inst) {
                try {
                    $account = [
                        'name' => $inst->inst_name,
                        'type' => $inst->inst_type,
                        'phone' => $inst->phone,
                        'email' => $inst->email,
                        'address' => $inst->address,
                        'city' => $inst->lga,
                        'state' => $inst->state,
                        'country' => 'Nigeria',
                        'industry' => 'Healthcare',
                    ];

                    $existing = $adapter->searchAccounts($inst->inst_name);
                    if (! empty($existing)) {
                        $adapter->updateAccount($existing[0]['id'], $account);
                    } else {
                        $adapter->createAccount($account);
                    }
                    $synced++;
                } catch (\Throwable $e) {
                    $failed++;
                    $errors[] = "{$inst->inst_name}: {$e->getMessage()}";
                }
            }

            $this->completeLog($log, $institutions->count(), $synced, $failed, $errors);
        } catch (\Throwable $e) {
            $this->failLog($log, $e->getMessage());
            throw $e;
        }
    }

    public function syncOrdersToDeals(CrmConnection $connection): void
    {
        $log = $this->startLog($connection, 'deal', 'push');

        try {
            $adapter = $this->adapterFor($connection);
            $orders = Order::where('status', Order::STATUS_PAID)->limit(200)->get();

            $synced = 0;
            $failed = 0;
            $errors = [];

            foreach ($orders as $order) {
                try {
                    $adapter->createDeal([
                        'name' => "Order #{$order->tracking_code}",
                        'stage' => 'Closed Won',
                        'amount' => (float) $order->total_amount,
                        'currency' => 'NGN',
                        'close_date' => $order->created_at->format('Y-m-d'),
                    ]);
                    $synced++;
                } catch (\Throwable $e) {
                    $failed++;
                    $errors[] = "Order {$order->tracking_code}: {$e->getMessage()}";
                }
            }

            $this->completeLog($log, $orders->count(), $synced, $failed, $errors);
        } catch (\Throwable $e) {
            $this->failLog($log, $e->getMessage());
            throw $e;
        }
    }

    public function syncBookingsToTickets(CrmConnection $connection): void
    {
        $log = $this->startLog($connection, 'ticket', 'push');

        try {
            $adapter = $this->adapterFor($connection);
            $bookings = Booking::limit(200)->get();

            $synced = 0;
            $failed = 0;
            $errors = [];

            foreach ($bookings as $booking) {
                try {
                    $adapter->createTicket([
                        'subject' => "Service Request – {$booking->service} [{$booking->ref}]",
                        'description' => $booking->notes,
                        'status' => 'New',
                        'priority' => $booking->urgency === 'emergency' ? 'High' : 'Medium',
                    ]);
                    $synced++;
                } catch (\Throwable $e) {
                    $failed++;
                    $errors[] = "Booking {$booking->ref}: {$e->getMessage()}";
                }
            }

            $this->completeLog($log, $bookings->count(), $synced, $failed, $errors);
        } catch (\Throwable $e) {
            $this->failLog($log, $e->getMessage());
            throw $e;
        }
    }

    public function syncReferralsToLeads(CrmConnection $connection): void
    {
        $log = $this->startLog($connection, 'lead', 'push');

        try {
            $adapter = $this->adapterFor($connection);
            $referrals = Referral::limit(200)->get();

            $synced = 0;
            $failed = 0;
            $errors = [];

            foreach ($referrals as $ref) {
                try {
                    $parts = explode(' ', $ref->referrer_full_name, 2);
                    $adapter->createLead([
                        'first_name' => $parts[0],
                        'last_name' => $parts[1] ?? '-',
                        'email' => $ref->referrer_email ?? '',
                        'phone' => $ref->referrer_phone,
                        'company' => $ref->referrer_facility,
                        'lead_source' => 'Referral',
                        'status' => 'Not Contacted',
                        'amount' => $ref->estimated_value ? (float) $ref->estimated_value : null,
                    ]);
                    $synced++;
                } catch (\Throwable $e) {
                    $failed++;
                    $errors[] = "Referral {$ref->ref_id}: {$e->getMessage()}";
                }
            }

            $this->completeLog($log, $referrals->count(), $synced, $failed, $errors);
        } catch (\Throwable $e) {
            $this->failLog($log, $e->getMessage());
            throw $e;
        }
    }

    private function startLog(CrmConnection $connection, string $entity, string $direction): CrmSyncLog
    {
        return CrmSyncLog::create([
            'connection_id' => $connection->id,
            'sync_type' => 'manual',
            'entity' => $entity,
            'direction' => $direction,
            'status' => 'running',
        ]);
    }

    private function completeLog(CrmSyncLog $log, int $total, int $synced, int $failed, array $errors): void
    {
        $log->update([
            'status' => $failed === 0 ? 'success' : 'partial',
            'records_total' => $total,
            'records_synced' => $synced,
            'records_failed' => $failed,
            'error_summary' => $errors ? implode('; ', $errors) : null,
            'completed_at' => now(),
            'duration_ms' => now()->diffInMilliseconds($log->started_at),
        ]);
    }

    private function failLog(CrmSyncLog $log, string $message): void
    {
        $log->update([
            'status' => 'failed',
            'error_summary' => $message,
            'completed_at' => now(),
        ]);
    }
}
