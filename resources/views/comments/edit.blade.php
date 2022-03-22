@extends('layouts.app')

@section('css')

@endsection

@section('content')

    <form action="{{ route('comment.update', $comment->id) }}" method="POST">
        @csrf
        @method("put")
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit {{$comment->title}} comment Details</h1>

            <div>
                <a href="/" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50"></i> Back to Home</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Below is the {{$comment->title}} tile ,this is for the comment stored in the
                            management system.</p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9 col-xl-10">
                    <div class="card shadow h-100">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @csrf

                            <div class="form-group">
                                <label for="title">Comment Title</label>
                                <input type="text"
                                       class="form-control <?php if ($errors->has('title')) {?>border-danger<?php }?>"
                                       name="title" id="title" value="{{$comment->title}}">
                            </div>
                            <div class="form-group">
                                <label for="comment">Notes</label>
                                <input type="text" class="form-control" name="comment_" id="comment"
                                       value="{{$comment->comment}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

@endsection


