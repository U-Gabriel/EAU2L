@extends('layouts.admin')

@section('admin_content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-12">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight">Utilisateurs</h1>
            <p class="text-white/40 mt-1">Gérez les accès administratifs de la plateforme.</p>
        </div>
    </div>

    <div class="bg-white/[0.02] border border-white/10 rounded-[2.5rem] p-10 backdrop-blur-md mb-12">
        <h2 class="text-xl font-bold text-white mb-8 flex items-center gap-3">
            <span class="w-8 h-8 rounded-lg bg-blue-600/20 text-blue-500 flex items-center justify-center text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </span>
            Nouvel Utilisateur
        </h2>

    <div class="mb-8">
        @if(session('success'))
            <div class="flex items-center gap-4 p-5 rounded-[1.5rem] bg-emerald-500/10 border border-emerald-500/20 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-emerald-400 font-bold text-sm tracking-wide">OPÉRATION RÉUSSIE</p>
                    <p class="text-emerald-400/70 text-xs">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="flex items-center gap-4 p-5 rounded-[1.5rem] bg-red-500/10 border border-red-500/20 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-red-400 font-bold text-sm tracking-wide">ACTION REQUISE</p>
                    <ul class="text-red-400/70 text-xs list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/50 uppercase tracking-widest ml-1">Pseudo *</label>
                    <input type="text" name="pseudo" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all" placeholder="ex: Admin_Core">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/50 uppercase tracking-widest ml-1">Email</label>
                    <input type="email" name="mail" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-blue-500 transition-all" placeholder="admin@core.com">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/50 uppercase tracking-widest ml-1">Mot de passe *</label>
                    <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-blue-500 transition-all" placeholder="••••••••">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-white/50 uppercase tracking-widest ml-1">Rôle</label>
                    <select name="id_role" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:outline-none focus:border-blue-500 transition-all appearance-none">
                        <option value="1" class="bg-[#020617]">Utilisateur Standard</option>
                        <option value="2" class="bg-[#020617]" selected>Administrateur (Rôle 2)</option>
                    </select>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full md:w-auto px-12 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 transition-all transform active:scale-95">
                    CRÉER LE COMPTE
                </button>
            </div>
        </form>

        <div class="mt-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-bold text-white italic">Membres du Système</h3>
                <p class="text-white/30 text-xs uppercase tracking-widest mt-1">Gestion des accès actifs</p>
            </div>
            <span class="px-4 py-1 bg-blue-500/10 border border-blue-500/20 rounded-full text-blue-400 text-[10px] font-bold">
                {{ \App\Models\User::count() }} TOTAL
            </span>
        </div>

        <div class="bg-white/[0.01] border border-white/5 rounded-[2.5rem] overflow-hidden backdrop-blur-sm">
            <table class="w-full text-left border-collapse admin-table-mobile">
                <thead>
                    <tr class="bg-white/5 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">
                        <th class="p-6">Identité</th>
                        <th class="p-6">Contact</th>
                        <th class="p-6 text-center">Rôle</th>
                        <th class="p-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach(\App\Models\User::orderBy('id_person', 'desc')->get() as $user)
                        <tr class="group hover:bg-white/[0.03] transition-all duration-300">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-blue-500/20">
                                        {{ strtoupper(substr($user->pseudo, 0, 1)) }}
                                    </div>
                                    <span class="text-white font-bold tracking-wide">{{ $user->pseudo }}</span>
                                    @if($user->id_person == auth()->id())
                                        <span class="text-[9px] font-black text-blue-400 bg-blue-400/10 px-2 py-0.5 rounded-full uppercase ml-2">Vous</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-6 text-sm text-white/50 font-medium">{{ $user->mail ?? '—' }}</td>
                            <td class="p-6">
                                <div class="flex justify-center">
                                    <span class="px-3 py-1 {{ $user->id_role == 2 ? 'bg-blue-500/10 border-blue-500/20 text-blue-400' : 'bg-white/5 border-white/10 text-white/30' }} border text-[9px] font-black uppercase rounded-lg tracking-tighter">
                                        {{ $user->id_role == 2 ? 'Administrateur' : 'Standard' }}
                                    </span>
                                </div>
                            </td>

                            <td class="p-6 text-right">
                                @if($user->id_person == auth()->id())
                                    <button onclick="toggleEditModal('{{ $user->pseudo }}', '{{ $user->mail }}', '{{ route('admin.users.update', $user->id_person) }}')" 
                                        class="p-3 rounded-xl bg-blue-500/10 text-blue-500 border border-blue-500/20 hover:bg-blue-500 hover:text-white transition-all duration-300 group-hover:scale-110">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                @else
                                    <form action="{{ route('admin.users.destroy', $user->id_person) }}" method="POST" onsubmit="return confirm('Supprimer cet accès définitivement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-3 rounded-xl bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all duration-300 group-hover:scale-110">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
<div id="editModal" class="fixed inset-0 z-[150] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-[#020617] border border-white/10 rounded-[2.5rem] p-10 w-full max-w-lg shadow-2xl">
        <h3 class="text-2xl font-bold text-white mb-6">Modifier mon profil</h3>
        
        <form id="editForm" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="space-y-2">
                <label class="text-xs font-bold text-white/50 uppercase">Pseudo</label>
                <input type="text" name="pseudo" id="editPseudo" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:border-blue-500 outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-white/50 uppercase">Email</label>
                <input type="email" name="mail" id="editMail" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:border-blue-500 outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-bold text-white/50 uppercase">Nouveau mot de passe (laisser vide si inchangé)</label>
                <input type="password" name="password" class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white focus:border-blue-500 outline-none transition-all" placeholder="••••••••">
            </div>
            
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 px-6 py-4 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl transition-all">ANNULER</button>
                <button type="submit" class="flex-1 px-6 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 transition-all">SAUVEGARDER</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleEditModal(pseudo, mail, actionUrl) {
        document.getElementById('editPseudo').value = pseudo;
        document.getElementById('editMail').value = mail;
        document.getElementById('editForm').action = actionUrl;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
<style>
    @media (max-width: 768px) {
    /* Cache l'en-tête */
    .admin-table-mobile thead {
        display: none !important;
    }

    .admin-table-mobile, 
    .admin-table-mobile tbody, 
    .admin-table-mobile tr, 
    .admin-table-mobile td {
        display: block !important;
        width: 100% !important;
    }

    .admin-table-mobile tr {
        margin-bottom: 1.5rem !important;
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 2rem !important;
        padding: 1rem !important;
    }

    .admin-table-mobile td {
        padding: 0.75rem 0.5rem !important;
        border: none !important;
        position: relative;
    }

    /* Style des Labels data-label */
    .admin-table-mobile td::before {
        content: attr(data-label);
        display: block;
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #3b82f6; /* Bleu pour rappeler ton thème */
        margin-bottom: 4px;
        opacity: 0.6;
    }

    /* Alignement spécifique pour le rôle et les actions sur mobile */
    .admin-table-mobile td[data-label="Rôle"] div,
    .admin-table-mobile td[data-label="Actions"] div {
        justify-content: flex-start !important;
    }

    /* Bouton d'action en pleine largeur sur mobile */
    .admin-table-mobile td[data-label="Actions"] button,
    .admin-table-mobile td[data-label="Actions"] form {
        width: 100%;
    }
    
    .admin-table-mobile td[data-label="Actions"] .flex {
        flex-direction: column;
    }
}
</style>
@endsection