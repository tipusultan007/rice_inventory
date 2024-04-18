<?php

namespace App\Jobs;

use App\Events\CSVFileGenerated;
use App\Events\SalesCSVGenerated;
use App\Models\User;
use App\Notifications\CSVFileGeneratedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class GenerateSalesCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notifiable;

    protected $sales;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $csv->insertOne([
            'তারিখ',
            'ক্রেতা',
            'মেমো নং',
            'পরিমাণ',
            'ধোলাই',
            'ডিস্কাউন্ট',
            'সর্বমোট',
            'পরিশোধ',
            'নোট',
        ]);

        // Insert data rows
        foreach ($this->sales as $sale) {
            $csv->insertOne([
                $sale->date,
                $sale->customer->name,
                $sale->invoice_no,
                $sale->saleDetails->sum('quantity'),
                $sale->dholai,
                $sale->discount,
                $sale->total,
                $sale->paid,
                $sale->note,
            ]);
        }

        // Store the CSV file
        $csvFileName = 'sales_' . now()->format('YmdHis') . '.csv';
        $csvFileName = str_replace(['/', '\\'], '_', $csvFileName);

        Storage::disk('public')->put('csv/' . $csvFileName, $csv->getContent());

        $notificationData = [
            'title' => 'CSV File Generated',
            'message' => 'To download: <a href="'.route('download_csv', $csvFileName).'">Click Here</a>',
            'link' => route('download_csv', $csvFileName),
        ];

        event(new CSVFileGenerated($notificationData));

        $users = User::all();
        Notification::send($users, new CSVFileGeneratedNotification($csvFileName));
    }
}
