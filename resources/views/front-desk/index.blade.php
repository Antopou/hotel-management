@extends('layouts.main')

@section('title', 'Front Desk - Hotel Management')

@section('breadcrumb')
<li class="breadcrumb-item active">Front Desk</li>
@endsection

@section('content')
<style>
    /* Enhanced Front Desk Styling - Keep all functionality, just improve visuals */
    body {
        font-size: 16px;
        line-height: 1.6;
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
    }
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .page-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    /* Enhanced Cards */
    .card {
        border: none;
        border-radius: 1.2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .card-header {
        border: none;
        border-radius: 1.2rem 1.2rem 0 0;
        padding: 1.5rem 2rem;
        font-weight: 600;
    }
    .card-body {
        padding: 2rem;
    }
    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    
    /* Enhanced gradient cards */
    .bg-gradient {
        border-radius: 1.2rem;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .bg-gradient:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(0,0,0,0.15);
    }
    .bg-gradient .card-body {
        padding: 2rem;
    }
    .bg-gradient .card-subtitle {
        font-size: 1rem;
        margin-bottom: 1rem;
        font-weight: 500;
    }
    .bg-gradient .btn {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .bg-gradient .btn:hover {
        transform: scale(1.05);
    }
    
    /* Enhanced stats */
    .display-6 {
        font-size: 3rem;
        font-weight: 800;
    }
    .text-muted {
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    /* Enhanced tables */
    .table {
        font-size: 1rem;
    }
    .table th {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        padding: 1rem;
    }
    .table td {
        padding: 1rem;
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Enhanced badges */
    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
    }
    
    /* Enhanced avatar */
    .avatar-sm {
        width: 3rem;
        height: 3rem;
        font-size: 1.2rem;
    }
    
    /* Enhanced buttons */
    .btn {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-sm {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    /* Better spacing */
    .mb-4 { margin-bottom: 2rem !important; }
    .mb-3 { margin-bottom: 1.5rem !important; }
    .py-3 { padding-top: 1.5rem !important; padding-bottom: 1.5rem !important; }
    
    /* Enhanced icons */
    .bi {
        font-size: 1.2em;
    }
    .fs-2 .bi {
        font-size: 2rem;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Front Desk Operations</h1>
    <p class="page-subtitle">Manage daily hotel operations and guest services</p>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
<div class="col-xl-3 col-md-6">
    <div class="card border-0 bg-gradient text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body d-flex align-items-center">
            <div class="me-3">
                <div class="bg-white bg-opacity-20 rounded-3 p-3">
                    <i class="bi bi-box-arrow-in-right fs-2"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="card-subtitle mb-2 text-white-50">Quick Check-in</h6>
                <a href="{{ route('checkins.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>New Check-in
                </a>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6">
    <div class="card border-0 bg-gradient text-white h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="card-body d-flex align-items-center">
            <div class="me-3">
                <div class="bg-white bg-opacity-20 rounded-3 p-3">
                    <i class="bi bi-calendar-plus fs-2"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="card-subtitle mb-2 text-white-50">New Reservation</h6>
                <a href="{{ route('reservations.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Book Room
                </a>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6">
    <div class="card border-0 bg-gradient text-white h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="card-body d-flex align-items-center">
            <div class="me-3">
                <div class="bg-white bg-opacity-20 rounded-3 p-3">
                    <i class="bi bi-person-plus fs-2"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="card-subtitle mb-2 text-white-50">Guest Registration</h6>
                <a href="{{ route('guests.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Add Guest
                </a>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6">
    <div class="card border-0 bg-gradient text-white h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <div class="card-body d-flex align-items-center">
            <div class="me-3">
                <div class="bg-white bg-opacity-20 rounded-3 p-3">
                    <i class="bi bi-receipt fs-2"></i>
                </div>
            </div>
            <div class="flex-grow-1">
                <h6 class="card-subtitle mb-2 text-white-50">Billing & Folios</h6>
                <a href="{{ route('folios.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-eye me-1">
