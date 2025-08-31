<?php

namespace App\Jobs;

use App\Models\Category;
use App\Services\TicketClassifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ClassifyTicketJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    /**
     * Create a new job instance.
     */
    public $ticket;
    
    public function __construct($ticket) {
        $this->ticket = $ticket;
    }
    /**
     * Execute the job.
     */
    public function handle(TicketClassifier $classifier): void
    {
        $result = $classifier->classify(
            $this->ticket->subject,
            $this->ticket->body
        );

       $category =  Category::firstOrCreate([
            'category' => $result['category'],
        ]);

        $this->ticket->update([
            'category_id'   => $category->id,
            'ai_explanation' =>  (string)  $result['explanation'],
            'ai_confidence' =>  (string)  $result['confidence'],
            'classified_at' => now()
        ]);
    }
}
