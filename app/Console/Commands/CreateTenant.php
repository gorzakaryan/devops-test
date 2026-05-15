<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stancl\Tenancy\Database\Models\Tenant;

class CreateTenant extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = 'tenant:create {id} {domain}';

    protected $description = 'Create a new tenant';

    public function handle()
    {
        $id = $this->argument('id');
        $domain = $this->argument('domain');

        Tenant::create([
            'id' => $id,
            'domain' => $domain,
        ]);

        $this->info("Tenant '$id' with domain '$domain' created successfully.");
    }
}
