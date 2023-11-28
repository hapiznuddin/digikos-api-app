<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Rent;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoices for all rents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $invoiceDate = Carbon::now()->startOfMonth();

        $rents = Rent::all();

        foreach ($rents as $rent) {
            Invoice::create([
                'rent_id' => $rent->id,
                'invoice_date' => $invoiceDate,
                'status' => 'Belum bayar',
            ]);
        }

        $this->info('Monthly invoices generated successfully.');
    }
}
