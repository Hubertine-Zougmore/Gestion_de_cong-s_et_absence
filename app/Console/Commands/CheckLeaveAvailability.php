<?php

namespace App\Console\Commands;
use App\Services\LeaveValidationService;
use Illuminate\Console\Command;

class CheckLeaveAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:check {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   public function handle(LeaveValidationService $service)
    {
        try {
            $service->validateLeavePeriod($this->argument('date'));
            $this->info("Période valide ! Jours disponibles : " 
                . $service->getAvailableDays());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
