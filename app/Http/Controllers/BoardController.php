<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function createBoard(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'max_slots' => 'required|integer',
        ]);

        $board = Board::create($validatedData);
        return response()->json($board, 201);
    }

    public function assignUserToBoard($boardId, $userId)
    {
        $board = Board::findOrFail($boardId);
        $user = User::findOrFail($userId);

        if (!$board->isFull()) {
            $board->addUser($userId);
            if ($board->isFull()) {
                $board->closeBoard();
            }
            return response()->json(['message' => 'User assigned to board successfully'], 200);
        }

        return response()->json(['message' => 'Board is full'], 400);
    }
}
