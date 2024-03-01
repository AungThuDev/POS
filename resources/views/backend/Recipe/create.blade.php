@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <p class="h4 mt-3 mb-0 text-center">Add New Recipe Form</p>
        <div class="p-5">
            <form action="{{route('recipe.store')}}" method="POST" class="form-group" enctype="multipart/form-data">
                @csrf
                <label class="form-label" for="name">Name</label>
                <input name="name" id="name" type="text" class="form-control">
                @error('name')
                    <span class="text-danger d-block">{{$message}}</span>
                @enderror
                <label for="price" class="form-label mt-2">Price</label>
                <input name="price" id="price" type="text" class="form-control">
                @error('price')
                    <span class="text-danger d-block">{{$message}}</span>
                @enderror
                <label for="category_id" class="form-label mt-2">Category</label>
                <select name="category_id" class="form-select">
                    @if (!empty($categories))
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('category_id')
                    <span class="text-danger d-block">{{$message}}</span>
                @enderror
                <label class="form-label mt-2">Kitchen</label>
                <select name="kitchen_id" class="form-select">
                    @if (!empty($kitchens))
                        @foreach ($kitchens as $kitchen)
                            <option value="{{ $kitchen->id }}">{{ $kitchen->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('kitchen_id')
                    <span class="text-danger d-block">{{$message}}</span>
                @enderror
                <div class="mt-2 d-flex align-items-center justify-content-center flex-wrap">
                    <div class="d-flex flex-column">
                        <label class="form-label mt-2" for="image">Image</label>
                        <input name="image" id="image" type="file" class="form-control">
                    </div>
                    <div class="ms-4">
                        <img class="rounded border border-1 border-black shadow-sm" id="uploadImage" style="width: 250px;" src="">
                    </div>
                </div>
                @error('image')
                    <span class="text-danger d-block">{{$message}}</span>
                @enderror
                <div class="d-flex justify-content-around mt-5">
                    <a class="btn btn-danger d-block" href="{{ route('recipe.index') }}">Cancel</a>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('image').addEventListener('change', function() {
            let image = document.getElementById('image');
            let file = image.files[0];
            let url = URL.createObjectURL(file);
            document.getElementById('uploadImage').src = url;
        });
    </script>
@endsection
