@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block"><button type="button" class="close" data-dismiss="alert">×</button>    
            <strong>{{ $message }}</strong>
        </div>
        @endif
            <div class="card">
                <div class="card-header">{{ __('Student Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('student.update-profile') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $student->name }}" autocomplete="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                            <div class="col-md-6">
                                <input id="email" disabled type="email" class="form-control" name="email" value="{{ $student->email }}" autocomplete="email">
                                 
                            </div>
                        </div>
                       
                       
                        <div class="form-group row">
                            <label for="profile_picture" class="col-md-4 col-form-label text-md-right">{{ __('Profile Picture') }}</label>
                            <div class="col-md-6">
                                <img id="preview-image-before-upload" src="{{ ($student->profile_picture)?'/images/'.$student->profile_picture:'https://via.placeholder.com/150' }}" alt="preview image" style="max-height: 200px;">
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control" placeholder="Choose profile picture" autocomplete="profile_picture" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="current_school" class="col-md-4 col-form-label text-md-right">{{ __('Current School') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="current_school" value="{{ $student->current_school }}" autocomplete="current_school">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="previous_school" class="col-md-4 col-form-label text-md-right">{{ __('Previous School') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="previous_school" value="{{ $student->previous_school }}" autocomplete="previous_school">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address"  autocomplete="address">{{ $student->address }}</textarea>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="assigned_teacher" class="col-md-4 col-form-label text-md-right">{{ __('Select Teacher') }}</label>
                            <div class="col-md-6">
                                @php $teachers  = \App\Models\User::where('user_type','STUDENT')->get();  @endphp
                                <select disabled class="form-control" id="assigned_teacher" name="assigned_teacher">
                                    <option value="">Please select Teacher</option>
                                    @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ ($teacher->id == $student->assigned_teacher)?'selected':''}}>{{ $teacher->name }}</option>  
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="parent_details" class="col-md-4 col-form-label text-md-right">{{ __('Parent Details') }}</label>
                            <div class="col-md-6" id="addMoreStudentFields">
                                @php
                                 $sn=0;
                                @endphp
                                @if ($student->parent_details)
                                
                                @foreach ($student->parent_details as $parentDetail)
                                    <div class="form-row {{($sn != 0)?'mt-2':''}}" id="field{{ $sn }}">
                                        <div class="col">
                                            <input type="text" name="parent_details[{{ $sn }}][name]" value="{{ $parentDetail['name'] }}" class="form-control" placeholder="Name">
                                        </div>
                                        <div class="col">
                                            <input type="text" name="parent_details[{{ $sn }}][value]" value="{{ $parentDetail['value'] }}" class="form-control" placeholder="Value">
                                        </div>
                                        @if ($sn == 0)
                                        <div class="col">
                                            <button type="button" class="btn btn-primary" onclick="addMore()">Add More</button>
                                        </div>
                                        @else
                                        <div class="col">
                                            <button type="button" class="btn btn-danger" onclick="remoreField({{ $sn }})">Remove</button>
                                        </div>
                                        @endif
                                       
                                    </div>  
                                    @php
                                    $sn++;
                                @endphp
                                @endforeach
                                @endif
                                
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label for="is_approved" class="col-md-4 col-form-label text-md-right">{{ __('Approved') }}</label>
                            <div class="col-md-6">
                                <input type="checkbox" class="form-control" name="is_approved" value="{{ $student->is_approved}}" autocomplete="is_approved" checked="{{ $student->is_approved ? 'checked' : ''; }}">
                               
                            </div>
                        </div> --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function (e) {
        $('#profile_picture').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => { 
                $('#preview-image-before-upload').attr('src', e.target.result); 
            }
        reader.readAsDataURL(this.files[0]); 
        });
    });
    let addRow = {{ $sn }};
    function addMore(){
        let html = '<div class="form-row mt-2" id="field'+ addRow +'"><div class="col"><input type="text" name="parent_details['+ addRow +'][name]" class="form-control" placeholder="Name"> </div><div class="col"><input type="text" name="parent_details['+ addRow +'][value]" class="form-control" placeholder="Value"></div><div class="col"><button type="button" class="btn btn-danger" onclick="remoreField('+ addRow +')">Remove</button></div></div>';
        $('#addMoreStudentFields').append(html);
        addRow++;
    }
    function remoreField(num){
        $('#field'+num).remove();
    }
</script>
@endsection
