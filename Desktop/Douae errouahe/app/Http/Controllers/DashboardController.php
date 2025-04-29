<?php

namespace App\Http\Controllers;

use App\Models\{Customer, Product, Supplier, Category, Store, User};
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\{Cookie, App};
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    // afficher la page d'accueil du dashboard
    public function index(): View
    {
        $user = User::find(1); // cherche l'utilisateur dont le id est 1
        return view('dashboard', ['pic' => $user->avatar]);
    }

    // afficher tous les clients
    public function customers(): View
    {
        return view('customers.index', [
            'customers' => Customer::all()
        ]);
    }

    // afficher tous les fournisseurs
    public function suppliers(): View
    {
        return view('suppliers.index', [
            'suppliers' => Supplier::all()
        ]);
    }

    // afficher tous les produits avec leurs relations
    public function products(): View
    {
        $products = Product::with(['category', 'supplier', 'stock'])->get();
        return view('products.index', compact('products'));
    }

    // afficher les fournisseurs pour filtrer les produits
    public function productsBySupplier(): View
    {
        $suppliers = Supplier::all();
        return view('products.by-supplier', compact('suppliers'));
    }

    // récupérer les produits d'un fournisseur donné
    public function getProductsBySupplier(Supplier $supplier): View
    {
        $products = Product::with(['stock', 'category'])
            ->where('supplier_id', $supplier->id)
            ->get();

        return view('products._products_by_supplier', compact('products'));
    }

    // afficher les magasins pour filtrer les produits
    public function productsByStore(): View
    {
        $stores = Store::all();
        return view('products.by-store', compact('stores'));
    }

    // récupérer les produits liés à un magasin
    public function getProductsByStore(Store $store)
    {
        $products = Product::with(['category', 'stock'])
            ->whereHas('stock', fn($query) => $query->where('store_id', $store->id))
            ->get();

        return response()->json($products);
    }

    // afficher la page des commandes
    public function orders()
    {
        return view("orders.index");
    }

    // enregistrer une valeur dans un cookie
    public function saveCookie()
    {
        $name = request()->input("txtCookie");
        Cookie::queue("UserName", $name, 6000000); // sauvegarde pour une longue durée
        return redirect()->back();
    }

    // enregistrer une valeur dans la session
    public function saveSession(Request $request)
    {
        $name = $request->input("txtSession");
        $request->session()->put('SessionName', $name);
        return redirect()->back();
    }

    // enregistrer un avatar pour l'utilisateur
    public function saveAvatar()
    {
        request()->validate([
            'avatarFile' => 'required|image',
        ]);

        $ext = request()->avatarFile->getClientOriginalExtension();
        $name = Str::random(30) . time() . "." . $ext;
        request()->avatarFile->move(public_path('storage/avatars'), $name);

        $user = User::find(1); // encore une fois, utilisateur fixe
        $user->update(['avatar' => $name]);

        return redirect()->back();
    }
}
