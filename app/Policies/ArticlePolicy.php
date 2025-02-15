<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    public function viewRevisions(User $user, Article $article): bool
    {
        return $user->id === $article->user_id || $user->is_admin;
    }

    public function revertRevisions(User $user, Article $article): bool
    {
        return $user->id === $article->user_id || $user->is_admin;
    }
}
