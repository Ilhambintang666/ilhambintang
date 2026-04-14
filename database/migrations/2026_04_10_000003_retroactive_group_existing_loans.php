<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Data migration: kelompokkan loan lama (loan_group_id = null)
 * ke dalam loan_groups berdasarkan (user_id, borrow_date, expected_return_date, DATE(created_at))
 */
return new class extends Migration
{
    public function up(): void
    {
        // Ambil semua loan tanpa group
        $loans = DB::table('loans')->whereNull('loan_group_id')->get();

        if ($loans->isEmpty()) {
            return;
        }

        // Kelompokkan
        $grouped = $loans->groupBy(function ($loan) {
            $createdDate = $loan->created_at
                ? date('Y-m-d', strtotime($loan->created_at))
                : 'unknown';
            $borrow   = $loan->borrow_date ?? 'null';
            $expected = $loan->expected_return_date ?? 'null';
            return "{$loan->user_id}_{$borrow}_{$expected}_{$createdDate}";
        });

        foreach ($grouped as $key => $groupLoans) {
            $first = $groupLoans->first();

            // Tentukan status group berdasarkan status loan terbanyak
            $statuses = $groupLoans->pluck('status')->unique();
            $status   = $statuses->count() === 1
                ? $statuses->first()
                : $groupLoans->sortByDesc('created_at')->first()->status;

            // Buat loan_group header
            $groupId = DB::table('loan_groups')->insertGetId([
                'user_id'              => $first->user_id,
                'borrow_date'          => $first->borrow_date
                    ?? now()->toDateString(),
                'expected_return_date' => $first->expected_return_date
                    ?? now()->addDays(7)->toDateString(),
                'status'     => $status,
                'created_at' => $first->created_at ?? now(),
                'updated_at' => now(),
            ]);

            // Update semua loan dalam grup
            $loanIds = $groupLoans->pluck('id')->toArray();
            DB::table('loans')
                ->whereIn('id', $loanIds)
                ->update(['loan_group_id' => $groupId]);
        }
    }

    public function down(): void
    {
        // Hapus semua loan_groups dan reset loan_group_id
        DB::table('loans')->update(['loan_group_id' => null]);
        DB::table('loan_groups')->truncate();
    }
};
