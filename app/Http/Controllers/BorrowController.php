<?php

namespace App\Http\Controllers;
use App\Helpers\ResponseHelper;
use App\Http\Requests\BorrowRequest;
use App\Models\Borrowing;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $borrows = Borrowing::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        if ($borrows->isEmpty()) {
            return ResponseHelper::jsonResponse(false, 'No borrowings found', [], 404);
        }

        return ResponseHelper::jsonResponse(
            true,
            'Borrowings retrieved successfully',
            $borrows,
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function Borrowing(BorrowRequest $request)
    {
       $request->validated();
       
        $borrowins = new Borrowing();
        $borrowins->user_id = $request->user()->id;
        $borrowins->book_id = $request->book_id;
        $borrowins->borrow_code = 'BRW-' . strtoupper(uniqid());
         // Generate a unique borrow code
         // Example: BRW-1234567890
        $borrowins->loan_date = Carbon::now();
        $borrowins->return_date = Carbon::now()->addDays(7); // Assuming a 7-day loan period
        $borrowins->status = 'loaned'; // Initial status
        $borrowins->save();

        // Decrease book stock
        $book = Book::findOrFail($request->book_id);
        if ($book->stock <= 0) {
            return ResponseHelper::jsonResponse(false, 'Book is not available for borrowing', [], 400);
        }   
        $book->decrement('stock');
    
        return ResponseHelper::jsonResponse(
            true,
            'Book borrowed successfully',
            $borrowins,
            201
        );
    }


    public function returnning(BorrowRequest $request, string $id)
    {
        $returned = Borrowing::findOrFail($id);
        if ($returned->status !== 'loaned') {
            return ResponseHelper::jsonResponse(false, 'Book is not currently loaned', [], 400);
        }
        $returned->status = 'returned';
        $returned->save();
       

        // Increase book stock
        $book = Book::findOrFail($returned->book_id);
        $book->increment('stock');
        return ResponseHelper::jsonResponse(
            true,
            'Book returned successfully',
            $returned,
            200
        );
        

        

        
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $borrowing = Borrowing::with(['user', 'book'])->find($id);

        if (!$borrowing) {
            return ResponseHelper::jsonResponse(false, 'Borrowing record not found', [], 404);
        }

        return ResponseHelper::jsonResponse(
            true,
            'Borrowing record retrieved successfully',
            $borrowing,
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        // Only allow deletion of returned books
        if ($borrowing->status !== 'returned') {
            return ResponseHelper::jsonResponse(false, 'Cannot delete active borrowing record', [], 400);
        }

        $borrowing->delete();

        return ResponseHelper::jsonResponse(true, 'Borrowing record deleted successfully', null, 200);
    }

    /**
     * Return a borrowed book using just the book_id
     */
    public function returnBook(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id'
        ]);

        // Get the current active borrowing for this book and user
        $borrowing = Borrowing::where('book_id', $request->book_id)
            ->where('user_id', $request->user()->id)
            ->whereIn('status', ['pending', 'approved'])
            ->latest()
            ->first();

        if (!$borrowing) {
            return ResponseHelper::jsonResponse(
                false,
                'No active borrowing found for this book',
                [],
                404
            );
        }

        $returnDate = Carbon::now();
        $dueDate = Carbon::parse($borrowing->loan_date)->addDays(7);
        $status = $returnDate->gt($dueDate) ? 'overdue' : 'returned';

        // Update the borrowing record
        $borrowing->update([
            'return_date' => $returnDate,
            'status' => $status
        ]);

        // Increase book stock
        $book = Book::findOrFail($borrowing->book_id);
        $book->increment('stock');

        $message = $status === 'overdue'
            ? 'Book returned late. Please note the overdue status.'
            : 'Book returned successfully';

        return ResponseHelper::jsonResponse(
            true,
            $message,
            $borrowing,
            200
        );
    }
}