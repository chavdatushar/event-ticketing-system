<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    public function store(CommentRequest $request)
    {
        try{
            $comment =$this->commentRepository->create([
                'event_id' => $request->event_id,
                'user_id' => Auth::id(),
                'content' => $request->content
            ]);

            return response()->json([
                'data'=>$comment,
                'success' => true,
                'message' => 'Comment added successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
