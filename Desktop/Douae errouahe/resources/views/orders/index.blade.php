@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Customer Orders</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Dashboard
        </a>
    </div>

    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group mb-3">
                <input type="text" id="customer-search" class="form-control" 
                       placeholder="Search by last name...">
                <button id="searchCustomers" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
            <div id="lstCustomers" class="mt-3"></div>
        </div>
    </div>

    <!-- Orders Section -->
    <div class="row">
        <div class="col-md-6" id="lstOrders"></div>
        <div class="col-md-6" id="orderDetails"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search customers
    document.getElementById('searchCustomers').addEventListener('click', searchCustomers);
    
    function searchCustomers() {
        const searchTerm = document.getElementById('customer-search').value;
        if (!searchTerm) return;
        
        fetch(`/api/customers/search/${searchTerm}`)
            .then(response => response.json())
            .then(displayCustomers);
    }

    // Display customer list
    function displayCustomers(customers) {
        const container = document.getElementById('lstCustomers');
        let html = '<div class="list-group">';
        
        customers.forEach(customer => {
            html += `
                <a href="#" class="list-group-item list-group-item-action" 
                   onclick="loadCustomerOrders(${customer.id})">
                    ${customer.first_name} ${customer.last_name}
                </a>
            `;
        });
        
        html += '</div>';
        container.innerHTML = html;
    }

    // Load customer orders
    window.loadCustomerOrders = function(customerId) {
        document.getElementById('orderDetails').innerHTML = '';
        
        fetch(`/api/customers/${customerId}/orders`)
            .then(response => response.json())
            .then(orders => {
                const container = document.getElementById('lstOrders');
                let html = '<div class="list-group">';
                
                orders.forEach(order => {
                    html += `
                        <a href="#" class="list-group-item list-group-item-action" 
                           onclick="loadOrderDetails(${order.id})">
                            Order #${order.id} - ${new Date(order.created_at).toLocaleDateString()}
                        </a>
                    `;
                });
                
                container.innerHTML = html + '</div>';
            });
    }

    // Load order details
    window.loadOrderDetails = function(orderId) {
        fetch(`/api/orders/${orderId}/details`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('orderDetails').innerHTML = html;
            });
    }
});
</script>
@endpush

<style>
    .list-group-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection