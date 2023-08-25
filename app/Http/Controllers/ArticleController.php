<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Models\ArticleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $article = Article::all();
        return response()->json($article);
    }



    public function store(Request $request)
    {
        $nome = 'pippo' . strval(time()) . '.jpg';
        $path = Storage::putFileAs("/storage/images", $request->img, $nome);
    
      
        $articolo = Article::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'content' => $request->content,
            'description' => $request->description,
            'img' => 'storage/images' . $nome, 
            'price' => $request->price,
            'category' => $request->category,
        ]);

        $articolo->save();
    
        return response()->json(['message' => 'Articolo creato con successo']);
    }
    
    
    public function richiesta(Request $request)
    {
        // Salva la richiesta nell'entità 'requests'
        $articleRequest = ArticleRequest::create([
            'user_id' => $request->user_id,
            'article_id' => $request->article_id,
            'price' => $request->price,
            'verified' => false,
        ]);
            
           
        $articleRequest->save();
        return response()->json(['message' => 'Richiesta effettuata con successo']);
    }
    
    
    public function viewRequests(Request $request)
    {
        try {
            $requests = ArticleRequest::all(); // Recupera tutte le richieste
            return response()->json(['requests' => $requests]);
        } catch (\Exception $e) {
            // Aggiungi un log dell'errore
            \Log::error("Errore durante il recupero delle richieste: " . $e->getMessage());
            return response()->json(['error' => 'Errore durante il recupero delle richieste'], 500);
        }
    }

    
    public function acceptRequest($requestId)
{
    try {
        // Trova la richiesta dell'articolo con l'ID specificato
        $articleRequest = ArticleRequest::findOrFail($requestId);

        // Imposta il campo "verified" a 1 (approvato) e salva la richiesta
        $articleRequest->verified = 1;
        $articleRequest->save();

        return response()->json(['message' => 'Richiesta accettata con successo']);
    } catch (\Exception $e) {
        \Log::error("Errore durante l'accettazione della richiesta: " . $e->getMessage());
        return response()->json(['error' => 'Errore durante l\'accettazione della richiesta'], 500);
    }
}

public function rejectRequest($requestId)
{
    try {
        // Trova la richiesta dell'articolo con l'ID specificato
        $articleRequest = ArticleRequest::findOrFail($requestId);

        // Imposta il campo "verified" a 2 (rifiutato) e salva la richiesta
        $articleRequest->verified = 2;
        $articleRequest->save();

        return response()->json(['message' => 'Richiesta rifiutata con successo']);
    } catch (\Exception $e) {
        \Log::error("Errore durante il rifiuto della richiesta: " . $e->getMessage());
        return response()->json(['error' => 'Errore durante il rifiuto della richiesta'], 500);
    }
}

    
    public function dettaglio($id){

        $article = Article::find($id); 
        return $article;

    }


    public function update($id, Request $request)
    {

        try {
           
              $article = Article::findOrFail($id);
      
            $article->update(['title'=>$request->title, 'subtitle'=>$request->subtitle, 'description'=>$request->description, 'content'=>$request->content, 'img'=>$request->img,'price'=>$request->price]);
    
            return response()->json([
                'message' => 'Article Updated Successfully!!',
                'article' => $article,
            ]);
            
    
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something went wrong while updating the article!',
            ], 500);

            return response()->json([true]);
        }
    }


  public function destroy($id)
{
    try {
        // Trova l'articolo da eliminare
        $article = Article::findOrFail($id);
        
        // Verifica se l'articolo è associato a richieste
        if ($article->requests()->count() > 0) {
            // Se ci sono richieste associate, elimina prima le richieste
            ArticleRequest::where('article_id', $article->id)->delete();
        }
        
        // Elimina l'articolo
        $article->delete();
        
        return response()->json([
            'message' => 'Articolo eliminato con successo'
        ]);
        
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return response()->json([
            'message' => 'Si è verificato un errore durante l\'eliminazione dell\'articolo'
        ], 500);
    }
}


    


    public function show($id)
    {
        $articolo = Article::find($id);
        if (!$articolo) {
            return response()->json([
                'message' => 'Article Not Found.'
            ], 404);
        }
    
        // Return Json Response
        return response()->json([
            'articolo' => $articolo
        ], 200);
    }
    
    public function filterDataRequest(Request $request)
    {
        $userFilter = $request->input('userFilter');
        $dateFilter = $request->input('dateFilter');
        $orderBy = $request->input('orderBy');
        $orderDirection = $request->input('orderDirection');

        $query = ArticleRequest::query();

        if ($userFilter) {
            $query->where('user_name', 'like', '%' . $userFilter . '%');
        }

        if ($dateFilter) {
            $query->whereDate('date', $dateFilter);
        }

        if ($orderBy && $orderDirection) {
            $query->orderBy($orderBy, $orderDirection);
        }

        $filteredRequests = $query->get();

        return response()->json(['requests' => $filteredRequests]);
    }

    public function sortBy(Request $request)
    {
        $orderBy = $request->input('orderBy'); // 'name' o 'date'
        $orderDirection = $request->input('orderDirection'); // 'asc' o 'desc'
        
        $query = ArticleRequest::query();

        // Implementa la logica per l'ordinamento
        if ($orderBy && $orderDirection) {
            $query->orderBy($orderBy, $orderDirection);
        }

        $sortedRequests = $query->get();

        return response()->json(['requests' => $sortedRequests]);
    }

    public function filterUserRequest(Request $request)
    {
        $userFilter = $request->input('userFilter');

        $query = ArticleRequest::query();

        // Implementa la logica per il filtraggio per utente
        if ($userFilter) {
            $query->where('user_name', 'like', '%' . $userFilter . '%');
        }

        $filteredRequests = $query->get();

        return response()->json(['requests' => $filteredRequests]);
    }

   
    public function getMonthlyRequestStats()
    {
        $year = date('Y');
        $stats = [];

        for ($month = 1; $month <= 12; $month++) {
            $count = DB::table('article_requests')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $monthName = date("F", mktime(0, 0, 0, $month, 1));
            $stats[] = ['month' => $monthName, 'count' => $count];
        }

        return response()->json(['stats' => $stats]);
    }
    
}

    

