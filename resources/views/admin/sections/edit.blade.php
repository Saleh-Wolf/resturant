@extends('layouts.admin')

@section('title', 'Edit Section')

@section('content')

<div class="container-fluid">

    <h1 class="mb-4">
        Edit Section
    </h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>
        </div>
    @endif

    <div class="card">

        <div class="card-body">

            <form action="{{ route('admin.sections.update', $section) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label>
                        Name
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $section->name) }}"
                           required>

                </div>

                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea name="description"
                              class="form-control"
                              rows="3">{{ old('description', $section->description) }}</textarea>

                </div>

                <div class="form-group">

                    <label>
                        Display Order
                    </label>

                    <input type="number"
                           name="display_order"
                           class="form-control"
                           value="{{ old('display_order', $section->display_order) }}"
                           min="0">

                </div>

                <div class="form-check mb-3">

                    <input type="checkbox"
                           name="is_active"
                           class="form-check-input"
                           id="is_active"
                           {{ old('is_active', $section->is_active) ? 'checked' : '' }}>

                    <label class="form-check-label"
                           for="is_active">

                        Active

                    </label>

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Update Section

                </button>

                <a href="{{ route('admin.sections.index') }}"
                   class="btn btn-secondary">

                    Back

                </a>

            </form>

        </div>

    </div>

</div>

@endsection