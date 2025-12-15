@extends('layouts.patient-modern')

@section('title', 'Thành viên gia đình (đã gỡ)')
@section('page-title', 'Thành viên gia đình (đã gỡ)')
@section('page-subtitle', 'Module Thành viên gia đình đã được gỡ khỏi ứng dụng')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning mb-0">Module "Thành viên gia đình" đã bị gỡ khỏi ứng dụng. Nếu cần khôi phục, kiểm tra nhánh backup `remove-family-backup-*`.</div>
                </div>
            </div>
        </div>
    </div>
@endsection
