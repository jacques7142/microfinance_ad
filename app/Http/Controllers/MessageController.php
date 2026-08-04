<?php

namespace App\Http\Controllers;

use App\Models\JournalActivite;
use App\Models\Message;
use App\Models\Societaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    // ====================== Côté sociétaire ======================

    public function societaireIndex(): View
    {
        $societaire = Auth::guard('societaire')->user();

        Message::where('societaire_id', $societaire->id)
            ->where('expediteur', 'agence')
            ->where('lu', false)
            ->update(['lu' => true]);

        $messages = $societaire->messages()
            ->orderBy('date_envoi')
            ->get();

        return view('societaires.messages', [
            'societaire' => $societaire,
            'messages' => $messages,
        ]);
    }

    public function societaireSend(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'contenu' => ['required', 'string', 'max:2000'],
        ]);

        $societaire = Auth::guard('societaire')->user();

        Message::create([
            'societaire_id' => $societaire->id,
            'utilisateur_id' => null,
            'expediteur' => Message::EXPEDITEUR_SOCIETAIRE,
            'contenu' => $data['contenu'],
            'date_envoi' => now(),
            'lu' => false,
        ]);

        JournalActivite::enregistrer('message_societaire', "Message envoyé par le sociétaire {$societaire->numero_societaire}");

        return redirect()->route('societaire.messages')->with('success', 'Votre message a bien été envoyé. Un personnel de la COOPEC-AD vous répondra rapidement.');
    }

    // ====================== Côté personnel ======================

    public function staffIndex(Request $request): View
    {
        $user = $request->user();

        $conversations = Societaire::with(['agence'])
            ->whereHas('messages')
            ->when($user->role !== 'administrateur', fn ($q) => $q->where('agence_id', $user->agence_id))
            ->withCount(['messages as non_lus' => fn ($q) => $q->where('expediteur', Message::EXPEDITEUR_SOCIETAIRE)->where('lu', false)])
            ->with(['messages' => fn ($q) => $q->orderByDesc('date_envoi')->limit(1)])
            ->orderByDesc('non_lus')
            ->orderBy('nom')
            ->get();

        return view('messages.index', ['conversations' => $conversations]);
    }

    public function staffShow(Request $request, Societaire $societaire): View
    {
        $user = $request->user();
        if ($user->role !== 'administrateur' && $societaire->agence_id !== $user->agence_id) {
            abort(403);
        }

        Message::where('societaire_id', $societaire->id)
            ->where('expediteur', Message::EXPEDITEUR_SOCIETAIRE)
            ->where('lu', false)
            ->update(['lu' => true]);

        $societaire->load('agence');
        $messages = $societaire->messages()
            ->orderBy('date_envoi')
            ->get();

        return view('messages.show', [
            'societaire' => $societaire,
            'messages' => $messages,
        ]);
    }

    public function staffReply(Request $request, Societaire $societaire): RedirectResponse
    {
        $user = $request->user();
        if ($user->role !== 'administrateur' && $societaire->agence_id !== $user->agence_id) {
            abort(403);
        }

        $data = $request->validate([
            'contenu' => ['required', 'string', 'max:2000'],
        ]);

        Message::create([
            'societaire_id' => $societaire->id,
            'utilisateur_id' => $user->id,
            'expediteur' => Message::EXPEDITEUR_AGENCE,
            'contenu' => $data['contenu'],
            'date_envoi' => now(),
            'lu' => false,
        ]);

        JournalActivite::enregistrer('message_personnel', "Réponse de {$user->nomComplet()} au sociétaire {$societaire->numero_societaire}");

        return redirect()->route('messages.show', $societaire)->with('success', 'Réponse envoyée.');
    }
}
