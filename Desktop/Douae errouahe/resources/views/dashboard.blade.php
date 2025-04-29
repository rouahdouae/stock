@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Section principale -->
    <div class="hero-section">
        <h1>Welcome to Stock</h1>
        <p class="subtitle">Manage your inventory and customers efficiently</p>
        
        <div class="action-buttons">
            <a href="/customers" class="btn btn-primary">Customers</a>
            <a href="/suppliers" class="btn btn-success">Suppliers</a>
            <a href="/products" class="btn btn-info">Products</a>
            <a href="/products-by-category" class="btn btn-warning">By Category</a>
            <a href="/products-by-supplier" class="btn btn-secondary">By Supplier</a>
            <a href="/products-by-store" class="btn btn-dark">By Store</a>
            <a href="{{ route('orders.index') }}" class="btn btn-danger">Orders</a>
        </div>
    </div>

    <!-- Section utilisateur -->
    <div class="user-section">
        <!-- Cookie -->
        <div class="user-greeting">
            <h2>Hello {{ Cookie::has('UserName') ? Cookie::get('UserName') : 'Guest' }}</h2>
            <form method="POST" action="saveCookie" class="user-form">
                @csrf
                <input type="text" name="txtCookie" placeholder="Enter your name">
                <button type="submit" class="btn btn-save">Save Cookie</button>
            </form>
        </div>

        <!-- Session -->
        <div class="user-greeting">
            <h2>Hello {{ Session::has('SessionName') ? Session('SessionName') : 'Guest' }}</h2>
            <form method="POST" action="saveSession" class="user-form">
                @csrf
                <input type="text" name="txtSession" placeholder="Enter your name">
                <button type="submit" class="btn btn-save">Save Session</button>
            </form>
        </div>

        <!-- Avatar -->
        <div class="avatar-section">
            <form method="POST" action="saveAvatar" enctype="multipart/form-data" class="avatar-form">
                @csrf
                <div class="avatar-upload">
                    <label for="avatarFile">Choose your profile picture</label>
                    <input type="file" id="avatarFile" name="avatarFile" accept="image/*">
                    <button type="submit" class="btn btn-save">Save Picture</button>
                </div>
                @if($pic)
                    <img src="{{ asset('storage/avatars/'.$pic) }}" alt="Profile picture" class="avatar-preview">
                @endif
            </form>
        </div>
    </div>
</div>

<style>
    /* Styles modernes */
    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .hero-section {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .hero-section h1 {
        font-size: 2.5rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .subtitle {
        font-size: 1.2rem;
        color: #7f8c8d;
        margin-bottom: 2rem;
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
    }

    .action-buttons .btn {
        min-width: 150px;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .user-section {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .user-greeting {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #eee;
    }

    .user-greeting h2 {
        font-size: 1.5rem;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .user-form {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .user-form input {
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        flex-grow: 1;
    }

    .btn-save {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-save:hover {
        background-color: #2980b9;
    }

    .avatar-section {
        margin-top: 2rem;
    }

    .avatar-upload {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #3498db;
    }

    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .user-form {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endsection