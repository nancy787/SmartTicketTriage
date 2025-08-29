<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public $ticket;
    
    public function __construct(Ticket $ticket) {
        $this->ticket = $ticket;
    }

    public function index(Request $request) {
        try {
            $page = $request->page ?? env('page', 10);
            $listTickets = $this->ticket->orderBy('created_at', 'desc')
                                        ->get();
                                        // ->paginate($page);
            return response()->json([
                'listTickets' => $listTickets,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request) {
        try {
            $validated = $request->validate([
                'subject'     => 'required|max:255',
                'body'        => 'required|string',
                'status'      => 'sometimes|in:open,in_progress,resolved,closed',
                'category_id' => 'nullable|exists:categories,id',
                'note'        => 'nullable|string',
            ]);

            $ticket = $this->ticket->create([
                'subject'      => $request->subject,
                'body'         => $request->body,
                'status'       => $request->status ?? 'open',
                'category_id'  => $request->category_id,
                'note'         => $request->note,
            ]);

            return response()->json([
                'success' => true,
                'ticket'  => $ticket
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ]);
        }

    }

    public function ticketDetails($id) {
        try {
            $ticket = $this->ticket->find($id);
            if(!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'No ticket found',
                ]);
            }

            return response()->json([
                'success' => true,
                'ticket' => $ticket->load('category')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ]);
        }
    }
    
    public function update($id, Request $request) {
        try {
            $validated = $request->validate([
                'status'  => 'sometimes|in:open,in_progress,resolved,closed',
                'category_id' => 'nullable|exists:categories,id',
                'note'    => 'nullable|string',
            ]);

            $ticket = $this->ticket->find($id);

            if(!$ticket) {
                return response()->json([
                    'success' => false,
                    'message' => 'No ticket found',
                ]);
            }

            $ticket->update($validated);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ]);
        }
    }
}
