@extends('layouts.app')
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name">
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
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                 @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="user_type" class="col-md-4 col-form-label text-md-right">{{ __('User Type') }}</label>
                            <div class="col-md-6">
                                <select onchange="setMoreFields(this)" class="form-control @error('user_type') is-invalid @enderror" name="user_type" autocomplete="user_type">
                                    <option value="STUDENT">Student</option>
                                    <option value="TEACHER">Teacher</option>
                                </select>
                                @error('user_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="profile_picture" class="col-md-4 col-form-label text-md-right">{{ __('Profile Picture') }}</label>
                            <div class="col-md-6">
                                <img id="preview-image-before-upload" src="https://via.placeholder.com/150" alt="preview image" style="max-height: 200px;">
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" placeholder="Choose profile picture" autocomplete="profile_picture" >
                                @error('profile_picture')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="current_school" class="col-md-4 col-form-label text-md-right">{{ __('Current School') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('current_school') is-invalid @enderror" name="current_school" value="{{ old('current_school') }}" autocomplete="current_school">
                                @error('current_school')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="previous_school" class="col-md-4 col-form-label text-md-right">{{ __('Previous School') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('previous_school') is-invalid @enderror" name="previous_school" value="{{ old('previous_school') }}" autocomplete="previous_school">
                                @error('previous_school')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}"  autocomplete="address"></textarea>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div id="studentFields" class="">
                            <div class="form-group row">
                                <label for="parent_details" class="col-md-4 col-form-label text-md-right">{{ __('Parent Details') }}</label>
                                <div class="col-md-6" id="addMoreStudentFields">
                                    <div class="form-row" id="field0">
                                        <div class="col">
                                            <input type="text" name="parent_details[0][name]" class="form-control" placeholder="Name">
                                        </div>
                                        <div class="col">
                                            <input type="text" name="parent_details[0][value]" class="form-control" placeholder="Value">
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-primary" onclick="addMore()">Add More</button>
                                        </div>
                                    </div>
                                    @error('parent_details')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div id="teacherFields" class="d-none">
                            <div class="form-group row">
                                <label for="experience" class="col-md-4 col-form-label text-md-right">{{ __('Experience') }}</label>
                                <div class="col-md-6">
                                    <input id="experience" type="number" class="form-control" name="experience" value="{{ old('experience') }}"  autocomplete="experience">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="expertise_in_subjects" class="col-md-4 col-form-label text-md-right">{{ __('Expertise In subjects') }}</label>
                                <div class="col-md-6">
                                    @php $subjects  = \App\Models\Subject::all();  @endphp
                                    <select multiple class="form-control" id="expertise_in_subjects" name="expertise_in_subjects[]">
                                        <option value="">Please select subjects</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>  
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
    let addRow = 1;
    function addMore(){
        let html = '<div class="form-row" id="field'+ addRow +'"><div class="col"><input type="text" name="parent_details['+ addRow +'][name]" class="form-control" placeholder="Name"> </div><div class="col"><input type="text" name="parent_details['+ addRow +'][value]" class="form-control" placeholder="Value"></div><div class="col"><button type="button" class="btn btn-danger" onclick="remoreField('+ addRow +')">Remove</button></div></div>';
        $('#addMoreStudentFields').append(html);
        addRow++;
    }
    function remoreField(num){
        $('#field'+num).remove();
    }
    function setMoreFields(obj)
    {
        if(obj.value == 'TEACHER'){
            $('#teacherFields').removeClass('d-none');
            $('#studentFields').addClass('d-none');
        }else{
            $('#teacherFields').addClass('d-none');
            $('#studentFields').removeClass('d-none');
        }
    }
</script>
@endsection