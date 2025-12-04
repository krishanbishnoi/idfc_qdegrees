@extends('layouts.master')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Select Branch</h3>

    <form action="{{ route('show.branch.data') }}" method="POST">
        @csrf
        
        <div class="form-group mb-3">
            <label for="branch">Choose Branch:</label>
            <select name="branch" class="form-control" required>
                <option value="">-- Select Branch --</option>

                @foreach($branches as $b)
                    <option value="{{ $b->BranchName }}">{{ $b->BranchName }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Submit</button>
    </form>

</div>
@endsection
