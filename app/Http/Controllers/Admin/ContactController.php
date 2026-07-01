<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    // Tampilkan semua pesan masuk
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.contact.index', compact('messages'));
    }

    // Balas pesan & kirim email ke user, lalu hapus pesan
    public function reply(Request $request, ContactMessage $message)
    {
        $request->validate([
            'reply' => ['required', 'string', 'min:5'],
        ]);

        // Kirim email balasan ke user
        try {
            Mail::to($message->email)
                ->send(new ContactReplyMail($message, $request->reply));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[Contact Reply Mail] ' . $e->getMessage());
            return back()->with('error', 'Gagal mengirim balasan email. Silakan coba lagi.');
        }

        // Hapus pesan setelah berhasil dibalas
        $message->delete();

        return redirect()->route('admin.contact.index')
            ->with('success', 'Balasan berhasil dikirim ke ' . $message->email . ' dan pesan telah dihapus.');
    }

    // Hapus pesan tanpa membalas
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.contact.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
