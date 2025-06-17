<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InitialRequest;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupRejectedRequestFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-rejected-request-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes initial attachment files for rejected requests that are older than 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of rejected request files...');

        // پیدا کردن درخواست‌های رد شده که بیش از 24 ساعت از رد شدنشان گذشته است
        $cutoffTime = Carbon::now()->subHours(24);
        $requestsToCleanup = InitialRequest::where('status', 'rejected')
            ->whereNotNull('rejected_at') // اطمینان از اینکه زمان رد شدن ثبت شده است
            ->where('rejected_at', '<=', $cutoffTime)
            ->whereNotNull('initial_file_path') // فقط درخواست‌هایی که فایل دارند
            ->get();

        if ($requestsToCleanup->isEmpty()) {
            $this->info('No rejected request files found to clean up.');
            return 0; // کد موفقیت، بدون انجام کاری
        }

        $count = 0;
        foreach ($requestsToCleanup as $request) {
            // بررسی وجود فایل در storage
            if (Storage::disk('private')->exists($request->initial_file_path)) {
                // حذف فایل از storage
                Storage::disk('private')->delete($request->initial_file_path);
                $this->line("Deleted file: {$request->initial_file_path}");
            }

            // null کردن مسیر فایل در دیتابیس
            $request->update(['initial_file_path' => null]);
            $count++;
        }

        $this->info("Successfully cleaned up {$count} files.");
        return 0; // کد موفقیت
    }
}
