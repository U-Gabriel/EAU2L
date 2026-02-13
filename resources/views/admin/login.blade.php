@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<section class="min-h-screen bg-[#020617] flex items-center justify-center p-6 relative overflow-hidden">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px]"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-white tracking-tighter">ADMINISTRATION</h1>
            <p class="text-white/40 mt-2">Accès sécurisé - Zone restreinte</p>
        </div>

        <div class="backdrop-blur-2xl bg-white/[0.02] border border-white/10 p-10 rounded-[2rem] shadow-2xl">
           <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                @if($errors->has('login'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] p-3 rounded-xl text-center font-bold">
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/50 uppercase tracking-widest ml-1">Identifiant</label>
                    <input type="text" name="pseudo" value="{{ old('pseudo') }}" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white focus:outline-none focus:border-blue-500 transition-all" 
                        placeholder="Votre pseudo">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/50 uppercase tracking-widest ml-1">Mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-4 text-white focus:outline-none focus:border-blue-500 transition-all" 
                        placeholder="••••••••">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 transition-all transform active:scale-[0.98] mt-4">
                    SE CONNECTER
                </button>
            </form>
        </div>
        
        <div class="text-center mt-8">
            <a href="/" class="text-white/20 hover:text-white/50 text-xs uppercase tracking-[0.2em] transition-colors">Retour au site</a>
        </div>
    </div>
</section>
@endsection