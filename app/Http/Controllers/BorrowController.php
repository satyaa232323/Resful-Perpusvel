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
    public function index()
    {
        $borrows = Borrowing::with(['user', 'book'])->get();

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
        // Check if book is available
        $book = Book::findOrFail($request->book_id);
        if ($book->stock <= 0) {
            return ResponseHelper::jsonResponse(false, 'Book is not available for borrowing', [], 400);
        }

        // Calculate return date (7 days from loan date)
        $loanDate = Carbon::parse($request->loan_date);
        $returnDate = $loanDate->copy()->addDays(7);

        // Check for existing active borrows for this user and book
        $existingBorrow = Borrowing::where('user_id', $request->user_id)
            ->where('book_id', $request->book_id)
            ->whereIn('status', values: ['pending', 'approved'])
            ->first();

        if ($existingBorrow) {
            return ResponseHelper::jsonResponse(false, 'User already has an active borrow for this book', [], 400);
        }

        // Create the borrowing record
        $borrowing = Borrowing::create([
            'book_id' => $request->book_id,
        ]);

        // Decrease book stock
        $book->decrement('stock');

        return ResponseHelper::jsonResponse(
            true,
            'Book borrowed successfully. Please return by ' . $returnDate->format('Y-m-d'),
            $borrowing,
            201
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
    public function Return(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $book = Book::findOrFail($borrowing->book_id);

        // Handle return
        if ($request->status === 'returned') {
            $returnDate = Carbon::now();
            $dueDate = Carbon::parse($borrowing->loan_date)->addDays(7);

            $status = $returnDate->gt($dueDate) ? 'overdue' : 'returned';

            $borrowing->update([
                'return_date' => $returnDate,
                'status' => $status
            ]);

            // Increase book stock
            $book->increment('stock');

            $message = $status === 'overdue'
                ? 'Book returned late. Please note the overdue status.'
                : 'Book returned successfully';

            return ResponseHelper::jsonResponse(true, $message, $borrowing, 200);
        }

        return ResponseHelper::jsonResponse(false, 'Invalid update request', [], 400);
    }

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
            ->where('user_id', auth()->id())
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
