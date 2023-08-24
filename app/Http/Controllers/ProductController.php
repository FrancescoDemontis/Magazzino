<?php

namespace App\Http\Controllers;


use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\ProductRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductRequestsExport;


class ProductController extends Controller
{
    public function index()
    {
        $orders = ProductRequest::all(); 
        
        return response()->json($orders);
    }
    
    public function downloadExcel()
    {
        $productRequests = ProductRequest::all(); // Supponendo che tu abbia definito ProductRequest correttamente
        $fileName = 'product_requests.xlsx';
        return Excel::download(new ProductRequestsExport($productRequests), $fileName);
    }


    public function submitRequest(Request $request)
    {
        $request->validate([
            'productLink' => 'required|url',
            'title' => 'required',
        ]);

        $productRequest = new ProductRequest([
            'user_id' => Auth::user()->id,
            'product_link' => $request->input('productLink'),
            'title' => $request->input('title'),
            'status' => 'Pending',
            'payment_status' => 'Pending',
        ]);
        $productRequest->save();

        return response()->json(['message' => 'Richiesta inviata con successo']);
    }
    public function acceptRequest($id)
    {
        $productRequest = ProductRequest::find($id);
        if (!$productRequest) {
            return response()->json(['message' => 'Richiesta non trovata'], 404);
        }

        $productRequest->status = 'Accepted';
        $productRequest->save();

        // Puoi inviare una notifica via email all'utente qui

        return response()->json(['message' => 'Richiesta accettata con successo']);
    }

    public function rejectRequest($id)
    {
        $productRequest = ProductRequest::find($id);
        if (!$productRequest) {
            return response()->json(['message' => 'Richiesta non trovata'], 404);
        }

        $productRequest->status = 'Rejected';
        $productRequest->save();

        // Puoi inviare una notifica via email all'utente qui

        return response()->json(['message' => 'Richiesta rifiutata con successo']);
    }
    public function filterByCategory(Request $request) {
        $category = $request->input('category');
    
        if ($category === 'all') {
            $products = Article::all();
        } else {
            $products = Article::whereHas('category', function ($query) use ($category) {
                $query->where('name', $category);
            })->get();
        }
    
        return response()->json($products);
    }
    
    
    
    public function filterByName(Request $request) {
        $searchTerm = $request->input('searchTerm');
        
        $products = Article::where('category', 'like', '%' . $searchTerm . '%')->get();
        
        return response()->json($products);
    }


    public function sortBy(Request $request) {
        $orderBy = $request->input('orderBy'); // 'name' o 'date'
        $orderDirection = $request->input('orderDirection'); // 'asc' o 'desc'
        
        $products = Article::orderBy($orderBy, $orderDirection)->get();
        
        return response()->json($products);
    }

    public function filter(Request $request)
    {
        $category = $request->input('category');
        $searchTerm = $request->input('searchTerm');
        $orderBy = $request->input('orderBy');
        $orderDirection = $request->input('orderDirection');

        $query = Article::query();

        if ($category !== 'all') {
            $query->whereHas('category', function ($query) use ($category) {
                $query->where('name', $category);
            });
        }

        if ($searchTerm) {
            $query->where('title', 'like', '%' . $searchTerm . '%');
        }

        if ($orderBy && $orderDirection) {
            $query->orderBy($orderBy, $orderDirection);
        }

        $filteredArticles = $query->get();

        return response()->json($filteredArticles);
    }
    

    
    



}
