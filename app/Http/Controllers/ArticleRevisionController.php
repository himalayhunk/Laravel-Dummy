<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleRevisionController extends Controller
{
    public function listRevisions($articleId)
    {
        $article = Article::findOrFail($articleId);
        
        if (!Gate::allows('view-revisions', $article)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($article->revisions);
    }

    public function showRevision($articleId, $revisionId)
    {
        $revision = ArticleRevision::where('article_id', $articleId)->findOrFail($revisionId);
        
        if (!Gate::allows('view-revisions', $revision->article)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($revision);
    }

    public function revertRevision($articleId, $revisionId)
    {
        $article = Article::findOrFail($articleId);
        $revision = ArticleRevision::where('article_id', $articleId)->findOrFail($revisionId);

        if (!Gate::allows('revert-revisions', $article)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $article->update([
            'title'   => $revision->title,
            'slug' => $revision->slug,
            'description' => $revision->description,
            'body' => $revision->body
        ]);

        return response()->json(['message' => 'Article reverted successfully', 'article' => $article]);
    }
}
