<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\ArticleRequest;
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
        $request->validate([
            'title' => 'required|max:255',
            'subtitle' => 'nullable|max:255',
            'content' => 'required',
            'description' => 'nullable|max:500',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required',
            'category_id' => 'required|exists:categories,id', // Aggiungi la validazione della categoria
        ]);
    
        $path = Storage::putFileAs('public/images', $request->file('images'), 'pippo.jpg');
    
        $articolo = new Article();
        $articolo->title = $request->input('title');
        $articolo->subtitle = $request->input('subtitle');
        $articolo->content = $request->input('content');
        $articolo->description = $request->input('description');
        $articolo->img = $path; 
        $articolo->price = $request->input('price');
        $articolo->category_id = $request->input('category_id'); // Assegna la categoria
    
        $path = Storage::putFileAs('public/images', $request->file('img'), 'pippo.jpg');

        // ...
    
        // Creazione di un nuovo articolo nel database con il percorso dell'immagine
        $articolo = new Article();
        $articolo->title = $request->input('title');
        // ... altre proprietà
        $articolo->img = $path = ('public/images');
        $articolo->save();
        return response()->json(['message' => 'Articolo creato con successo'], 201);
    }
    public function richiesta(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'price' => 'required', // Aggiunto campo price alla validazione
            'verified' => 'required',
        ]);
    
        // Salva la richiesta nell'entità 'requests'
        $articleRequest = new ArticleRequest();
        $articleRequest->user_id = $request->user()->id;
        $articleRequest->article_id = $request->input('article_id');
        $articleRequest->price = $request->input('price'); // Aggiunto il prezzo alla richiesta
        $articleRequest->verified = false;
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

    public function updatePrice(Request $request, $articleId)
{
    try {
        // Verifica se l'utente è un amministratore (implementa questa logica se necessario)

        $article = Article::findOrFail($articleId);
        $article->price = $request->input('price');
        $article->save();
     
        return response()->json(['message' => 'Prezzo aggiornato con successo']);
    } catch (\Exception $e) {
        \Log::error("Errore durante l'aggiornamento del prezzo: " . $e->getMessage());
        return response()->json(['error' => 'Errore durante l\'aggiornamento del prezzo'], 500);
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
    

    // public function articleSearch(Request $request, $id){


    //     $articolo = Article::find($id);
    //     if (!$articolo) {
    //         return response()->json([
    //             'message' => 'Article Not Found.'
    //         ], 404);
    //     }
    
    //     // Return Json Response
    //     return response()->json([
    //         'articolo' => $articolo
    //     ], 200);

    //     $query = $request->input('query');
    //     $articles = Article::search($query)->where('is_accepted' , true)->get();


    // }
}
