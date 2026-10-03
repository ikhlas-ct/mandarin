<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelajar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PelajarController extends Controller
{
    // ===================== READ (daftar) =====================
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $pelajars = Pelajar::with('user')
            ->withCount('progresHafalans')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('nama', 'like', "%{$search}%")
                      ->orWhere('no_telp', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($u) use ($search) {
                          $u->where('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            ->when(in_array($status, ['aktif', 'nonaktif'], true), fn ($q) => $q->where('status', $status))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'      => Pelajar::count(),
            'aktif'      => Pelajar::where('status', 'aktif')->count(),
            'nonaktif'   => Pelajar::where('status', 'nonaktif')->count(),
            'tanpa_akun' => Pelajar::whereNull('user_id')->count(),
        ];

        return view('pages.pelajar.index', compact('pelajars', 'stats'));
    }

    // ===================== CREATE =====================
    public function create()
    {
        return view('pages.pelajar.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules($request), $this->messages());

        $pelajar = DB::transaction(function () use ($request, $data) {
            $user = User::create([
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => $data['password'], // di-hash otomatis oleh cast 'hashed'
                'role'     => 'pelajar',
                'status'   => $data['status'],
            ]);

            return Pelajar::create([
                'user_id'    => $user->id,
                'nama'       => $data['nama'],
                'no_telp'    => $data['no_telp'] ?? null,
                'alamat'     => $data['alamat'] ?? null,
                'status'     => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
                'foto'       => $request->hasFile('foto')
                    ? $request->file('foto')->store('pelajar', 'public')
                    : null,
            ]);
        });

        return redirect()
            ->route('admin.pelajar.index')
            ->with('success', "Pelajar \"{$pelajar->nama}\" berhasil ditambahkan.");
    }

    // ===================== READ (detail) =====================
    public function show(Pelajar $pelajar)
    {
        $pelajar->load('user');

        $progres = $pelajar->progresHafalans()
            ->with('kosakata')
            ->latest('updated_at')
            ->paginate(10);

        $ringkasan = $pelajar->progresHafalans()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalReview = $pelajar->riwayatReviews()->count();

        return view('pages.pelajar.show', compact('pelajar', 'progres', 'ringkasan', 'totalReview'));
    }

    // ===================== UPDATE =====================
    public function edit(Pelajar $pelajar)
    {
        $pelajar->load('user')->loadCount('progresHafalans');

        return view('pages.admin.pelajar.edit', compact('pelajar'));
    }

    public function update(Request $request, Pelajar $pelajar)
    {
        $pelajar->load('user');
        $data = $request->validate($this->rules($request, $pelajar), $this->messages());

        DB::transaction(function () use ($request, $data, $pelajar) {
            $payload = [
                'nama'       => $data['nama'],
                'no_telp'    => $data['no_telp'] ?? null,
                'alamat'     => $data['alamat'] ?? null,
                'status'     => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
            ];

            if ($request->hasFile('foto')) {
                $this->hapusFoto($pelajar->foto);
                $payload['foto'] = $request->file('foto')->store('pelajar', 'public');
            } elseif ($request->boolean('hapus_foto')) {
                $this->hapusFoto($pelajar->foto);
                $payload['foto'] = null;
            }

            $pelajar->update($payload);

            $user = $pelajar->user;

            if ($user) {
                $akun = [
                    'username' => $data['username'],
                    'email'    => $data['email'],
                    'status'   => $data['status'],
                ];
                if (! empty($data['password'])) {
                    $akun['password'] = $data['password'];
                }
                $user->update($akun);
            } elseif (! empty($data['username'])) {
                // Pelajar lama yang belum punya akun: buatkan sekarang.
                $user = User::create([
                    'username' => $data['username'],
                    'email'    => $data['email'],
                    'password' => $data['password'],
                    'role'     => 'pelajar',
                    'status'   => $data['status'],
                ]);
                $pelajar->update(['user_id' => $user->id]);
            }
        });

        return redirect()
            ->route('admin.pelajar.index')
            ->with('success', "Pelajar \"{$pelajar->nama}\" berhasil diperbarui.");
    }

    // ===================== DELETE =====================
    public function destroy(Pelajar $pelajar)
    {
        $nama   = $pelajar->nama;
        $jumlah = $pelajar->progresHafalans()->count();
        $foto   = $pelajar->foto;

        DB::transaction(function () use ($pelajar) {
            $user = $pelajar->user;

            // progres_hafalans & riwayat_reviews ikut terhapus (ON DELETE CASCADE).
            $pelajar->delete();

            // Akun login ikut dihapus, tapi hanya kalau memang akun pelajar.
            if ($user && $user->isPelajar()) {
                $user->delete();
            }
        });

        $this->hapusFoto($foto);

        $pesan = "Pelajar \"{$nama}\" berhasil dihapus.";
        if ($jumlah > 0) {
            $pesan .= " {$jumlah} progres hafalannya ikut terhapus.";
        }

        return redirect()
            ->route('admin.pelajar.index')
            ->with('success', $pesan);
    }

    // ===================== HELPER =====================
    private function hapusFoto(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }

    private function rules(Request $request, ?Pelajar $pelajar = null): array
    {
        $user = $pelajar?->user;

        // Akun wajib saat tambah, atau saat edit pelajar yang sudah punya akun.
        // Untuk pelajar lama tanpa akun, akun opsional (tapi kalau diisi harus lengkap).
        $akunWajib = $pelajar === null || $user !== null;

        if ($akunWajib) {
            $username = ['required'];
            $email    = ['required'];
            $password = [$pelajar === null ? 'required' : 'nullable'];
        } else {
            $username = ['nullable', 'required_with:email,password'];
            $email    = ['nullable', 'required_with:username,password'];
            $password = ['nullable', 'required_with:username,email'];
        }

        return [
            'nama'       => ['required', 'string', 'max:50'],
            'no_telp'    => ['nullable', 'string', 'max:20'],
            'alamat'     => ['nullable', 'string', 'max:1000'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'status'     => ['required', Rule::in(['aktif', 'nonaktif'])],
            'foto'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            'username' => array_merge($username, [
                'string', 'max:100',
                Rule::unique('users', 'username')->ignore($user?->id),
            ]),
            'email' => array_merge($email, [
                'email', 'max:100',
                Rule::unique('users', 'email')->ignore($user?->id),
            ]),
            'password' => array_merge($password, ['string', 'min:8', 'confirmed']),
        ];
    }

    private function messages(): array
    {
        return [
            'nama.required' => 'Nama pelajar wajib diisi.',
            'nama.max'      => 'Nama pelajar maksimal 50 karakter.',
            'no_telp.max'   => 'Nomor telepon maksimal 20 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in'       => 'Status tidak valid.',

            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, PNG, atau WEBP.',
            'foto.max'   => 'Ukuran foto maksimal 2 MB.',

            'username.required'      => 'Username wajib diisi.',
            'username.required_with' => 'Username wajib diisi bila membuat akun.',
            'username.unique'        => 'Username sudah dipakai.',
            'username.max'           => 'Username maksimal 100 karakter.',

            'email.required'      => 'Email wajib diisi.',
            'email.required_with' => 'Email wajib diisi bila membuat akun.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah dipakai.',

            'password.required'      => 'Password wajib diisi.',
            'password.required_with' => 'Password wajib diisi bila membuat akun.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ];
    }
}
