<?php

namespace App\Http\Controllers;

use App\Models\BlockedIp;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlockedIpController extends Controller
{
    public function index(Request $request)
    {
        $blockedIps = BlockedIp::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('BlockedIp/Index', [
            'blocked_ips' => $blockedIps,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:blocked_ips,ip_address',
            'reason' => 'nullable|string|max:255',
        ]);

        BlockedIp::create([
            'ip_address' => $request->ip_address,
            'reason' => $request->reason,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'IP berhasil diblokir.');
    }

    public function update(Request $request, BlockedIp $blockedIp)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $blockedIp->update($request->only('reason', 'is_active'));

        return redirect()->back()->with('success', 'Data IP diperbarui.');
    }

    public function destroy(BlockedIp $blockedIp)
    {
        $blockedIp->delete();

        return redirect()->back()->with('success', 'IP berhasil dihapus.');
    }
}
