<?php

namespace App\Repositories;
use App\Interfaces\CommentRepositoryInterfaces;
use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterfaces{
    public function create(array $details){
        return Comment::create($details);
    }
}
