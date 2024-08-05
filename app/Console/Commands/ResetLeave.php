<?php

namespace App\Console\Commands;

use App\Models\EmployeeModel;
use Illuminate\Console\Command;

class ResetLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset total leave based on work date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info('Command reset:leave is running...');
        $current_month = date('m');
        $current_day = date('d');
        $current_year = date('Y');
        $get_current_employee = EmployeeModel::where('status', 1)
            ->whereMonth('work_date', $current_month)
            ->whereDay('work_date', $current_day)->get();
        foreach ($get_current_employee as $value) {
            if ($value->vacation_reset == null || date('Y', strtotime($value->vacation_reset)) < $current_year) {
                $value->vacation = 12;
                $value->vacation_reset = date("Y-m-d");
                $value->save();
            }
        }

        // return 0;
    }
}
