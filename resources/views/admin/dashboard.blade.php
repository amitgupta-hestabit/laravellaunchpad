@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block"><button type="button" class="close" data-dismiss="alert">×</button>    
                <strong>{{ $message }}</strong>
            </div>
            @endif
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>
                <div class="card-body">
                   <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">Name</th>
                            <th scope="col">email</th>
                            <th scope="col">User Type</th>
                            <th scope="col">address</th>
                            <th scope="col">current school</th>
                            <th scope="col">previous school</th>
                            <th scope="col">experience</th>
                            <th scope="col">parent details</th>
                            <th scope="col">assigned teacher</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead> 
                        <tbody>
                        @if(!empty($users) && $users->count())
                            @foreach ($users as $user)
                                <tr>
                                    <td> <img src="/images/{{($user->profile_picture)?$user->profile_picture:'https://via.placeholder.com/150'}}" alt="profile image" style="max-height: 100px;">
                                        <br>
                                        {{ $user->name }}
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->user_type}}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->current_school }}</td>
                                    <td>{{ $user->previous_school }}</td>
                                    <td>{{ $user->experience }}</td>
                                    
                                    <td>  
                                        @if($user->parent_details)
                                        @foreach ($user->parent_details as $parentDetail)
                                        <p>{{$parentDetail['name'] }}:- {{$parentDetail['value'] }}</p>
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>@php
                                        if($user->assigned_teacher){
                                            $teacher = \App\Models\User::find($user->assigned_teacher);
                                            echo $teacher->name;
                                        }else {
                                            echo 'Null';
                                        }
                                        
                                    @endphp</td>
                                    <td>{{ $user->status }}</td>
                                    @if ($user->user_type == 'STUDENT')
                                        <td><a href="{{ route('admin.edit-student', ['userId'=>$user->id]) }}" class="btn btn-info btn-sm">Teacher assigned</a>
                                                <div>
                                                <form method="POST" action="{{route('admin.approved-student',['userId' => $user->id])}}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('patch')
                                                    
                                                            <input id="is_approved"  type="hidden" class="form-control @error('is_approved') is-invalid @enderror" name="is_approved" value="{{ $user->is_approved }}" autocomplete="is_approved">
                                                            <button type="submit" class="btn btn-primary">
                                                                @if ($user->is_approved == 0) {{ __('Approved') }}
                                                                @else {{ __('UnApproved') }}
                                                                @endif
                                                                </button>
                                                       
                                                </form>
                                            
                                            </div>
                                        </td>
                                    @else
                                        <td>
                                            {{-- <a href="{{ route('admin.edit-teacher', ['userId'=>$user->id]) }}" class="btn btn-info btn-sm">Approved</a> --}}
                                            <div>
                                                <form method="POST" action="{{route('admin.approved-student',['userId' => $user->id])}}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('patch')
                                                   
                                                        
                                                            <input id="is_approved"  type="hidden" class="form-control @error('is_approved') is-invalid @enderror" name="is_approved" value="{{ $user->is_approved }}" autocomplete="is_approved">
                                                            <button type="submit" class="btn btn-primary">
                                                                @if ($user->is_approved == 0) {{ __('Approved') }}
                                                                @else {{ __('UnApproved') }}
                                                                @endif
                                                                </button>
                                                       
                                                    
                                                </form>
                                            </div>
                                            
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10">There are no student or teacher.</td>
                            </tr>
                        @endif
                        
                        </tbody>
                    </table>
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
