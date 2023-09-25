@extends('layouts.app')

@section('Titulo')
    {{$post->titulo}}
@endsection

@section('contenido')
    <div class="container mx-auto md:flex">
        <div class="md:w-2/5 sm ml:4">
            <img src="{{asset('uploads') . '/' . $post->imagen}}" alt="Imagen del post {{$post->titulo}}">
            <div class='p-3 flex items-center gap-4'>
                <div class='my-4'>
                    @auth    
                    <livewire:like-post :post="$post" />
                    @endauth    
                </div>
            </div>
            <div>
                <a class='font-bold' href="{{route('posts.index', $post->user)}}">{{$post->user->username}}</a>
                <p class='text-sm'>
                    {{$post->created_at->diffForHumans()}}
                </p>
                <p class='mt-5 text-gray-900'>
                    {{$post->descripcion}}
                </p>
            </div>
        @auth    
            @if ($post->user_id === auth()->user()->id)
                <form action="{{route('posts.destroy',$post)}}" method='POST'>
                    @method('DELETE')
                    @csrf
                    <input 
                    type="submit"
                    value='Eliminar publicacion'
                    class="bg-red-700 hover:bg-red-600 p-2 rounded text-white font-bold mt-9 cursor-pointer"
                    >
                </form>
            @endif
        @endauth
        </div>
        <div class="md:w-2/3 p-9">
            <div class="shadow bg-white p-9 mb-5">
                @auth
                    <p class="text-xl font-bold text-center mb-4">Agregar un nuevo comentario</p>
                    @if(session('mensaje'))
                        <div class="bg-green-500 p-2 rounded-lg mb-6 text-white text-center uppercase font-bold">
                            {{session('mensaje')}}
                        </div>
                    @endif
                    <form action="{{route('comentarios.store',['post'=>$post,'user'=>$user])}}" method="POST">
                        @csrf
                        <div class='mb-5'>
                            <label for="comentario" class="mb-2 bloack uppercase text-gray-800 font-bold">
                                Comentario
                            </label>
                            <textarea name="comentario" id="comentario" placeholder="Agrega un comentario" class="border p-3 w-full rounded-lg @error('comentario') border-red-800 bg-red-100 @enderror"
                            ></textarea>
                            @error('comentario')
                                <p class="bg-red-600 text-white font-medium my-2 rounded-lg text-sm p-2 text-center">{{$message}}</p>
                            @enderror
                            <input type="submit" value="Comentar" class="bg-blue-900 hover:bg-blue-600 transition-colors cursor-pointer 
                            uppercase font-bold w-full p-3 text-white rounded-lg mt-10">
                        </div>
                        <input 
                        name="imagen"
                        type="hidden"
                        value="Comentar"
                        />
                    </form>
                    <div class='bg-white shadow mb-5 max-h-96 overflow-y-scroll mt-10'>
                        @if ($post->comentarios->count())
                            @foreach ($post->comentarios as $comentario)
                                <div class='p-5 border-b shadow-m border-blue-200'>
                                    <a href="{{route('posts.index',$comentario->user)}}" class='font-bold'>
                                        {{$comentario->user->username}}
                                    </a>
                                    <p>{{$comentario->comentario}}</p>
                                    <p class='text-sm text-gray-500'>{{$comentario->created_at->diffForHumans()}}</p>
                                </div>
                            @endforeach
                        @else
                            <p class='p-10 text-center'>No hay comentarios aun</p>
                        @endif
                    </div>
                </div>
            @endauth
        </div>
    </div>
@endsection