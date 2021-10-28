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
                <div class="card-header">{{ __('Teacher Profile') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('teacher.update-profile') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $teacher->name }}" autocomplete="name">
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
                                <input id="email" disabled type="email" class="form-control" name="email" value="{{ $teacher->email }}" autocomplete="email">
                                 
                            </div>
                        </div>
                       
                       
                        <div class="form-group row">
                            <label for="profile_picture" class="col-md-4 col-form-label text-md-right">{{ __('Profile Picture') }}</label>
                            <div class="col-md-6">
                                <img id="preview-image-before-upload" src="{{ ($teacher->profile_picture)?'/images/'.$teacher->profile_picture:'https://via.placeholder.com/150' }}" alt="preview image" style="max-height: 200px;">
                                <input type="file" id="profile_picture" name="profile_picture" class="form-control" placeholder="Choose profile picture" autocomplete="profile_picture" >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="current_school" class="col-md-4 col-form-label text-md-right">{{ __('Current School') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="current_school" value="{{ $teacher->current_school }}" autocomplete="current_school">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="previous_school" class="col-md-4 col-form-label text-md-right">{{ __('Previous School') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="previous_school" value="{{ $teacher->previous_school }}" autocomplete="previous_school">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control @error('address') is-invalid @enderror" name="address"  autocomplete="address">{{ $teacher->address }}</textarea>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                                <label for="experience" class="col-md-4 col-form-label text-md-right">{{ __('Experience') }}</label>
                                <div class="col-md-6">
                                    <input id="experience" type="number" class="form-control" name="experience" value="{{ $teacher->experience }}"  autocomplete="experience">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="expertise_in_subjects" class="col-md-4 col-form-label text-md-right">{{ __('Expertise In subjects') }}</label>
                                <div class="col-md-6">
                                    @php $subjects  = \App\Models\Subject::all();  @endphp
                                    <select multiple class="form-control" id="expertise_in_subjects" name="expertise_in_subjects[]">
                                        <option value="">Please select subjects</option>
                                        @php
                                          $allSubjects = $teacher->expertiseInSubjects()->pluck('subject_id')->toArray();  
                                        @endphp
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ in_array($subject->id,$allSubjects)?'selected':''}}>{{ $subject->name }}</option>  
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
    
</script>
@endsection
