<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class CleanupTemporaryBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cleanup-temporary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary bookings that are older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of temporary bookings...');

        // Find temporary bookings older than 24 hours
        $cutoffTime = Carbon::now()->subHours(24);
        
        $temporaryBookings = Booking::where('status', 'temporary')
            ->where('payment_status', 'awaiting_payment')
            ->where('created_at', '<', $cutoffTime)
            ->get();

        $deletedCount = 0;

        foreach ($temporaryBookings as $booking) {
            try {
                $flight = $booking->flight;
                
                // Delete the temporary booking
                $booking->delete();
                
                // Restore available seats
                if ($flight) {
                    $flight->increment('available_seats', $booking->number_of_passengers);
                }
                
                $deletedCount++;
                
                $this->info("Deleted temporary booking ID: {$booking->id}");
                
            } catch (\Exception $e) {
                $this->error("Error deleting booking ID {$booking->id}: {$e->getMessage()}");
            }
        }

        $this->info("Cleanup completed. Deleted {$deletedCount} temporary bookings.");

        return 0;
    }
}
