<?php

namespace App\Jobs;

use App\Events\CSVFileGenerated;
use App\Models\Sale;
use App\Models\User;
use App\Notifications\CSVFileGeneratedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;

class GenerateSaleProductCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            'পণ্য',
            'পরিমাণ',
            'দর',
            'সর্বমোট',
        ]);

        // Insert data rows
        foreach ($this->sales as $item) {
            $csv->insertOne([
                $item->sale->date,
                $item->sale->customer->name,
                $item->sale->invoice_no,
                $item->product->name,
                $item->quantity,
                $item->price_rate,
                $item->amount
            ]);
        }

        // Store the CSV file
        $csvFileName = 'sales_products_' . now()->format('YmdHis') . '.csv';
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
