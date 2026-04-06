<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Loan;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_loan_request()
    {
        // Create a user and an item
        $user = User::factory()->create(['role' => 'peminjam']);
        $item = Item::factory()->create(['status' => 'tersedia']);

        // Act as the user and submit a loan request
        $response = $this->actingAs($user)->post('/user/borrow', [
            'item_id' => $item->id,
            'expected_return_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        // Assert the response is a redirect (back to previous page)
        $response->assertRedirect();

        // Assert the loan was created with status 'pending'
        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_view_pending_loans()
    {
        // Create admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Create a pending loan
        $user = User::factory()->create(['role' => 'peminjam']);
        $item = Item::factory()->create(['status' => 'tersedia']);
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending',
        ]);

        // Act as admin and visit the loan verification page
        $response = $this->actingAs($admin)->get('/admin/peminjaman');

        // Assert the page loads successfully
        $response->assertStatus(200);

        // Assert the loan appears in the view
        $response->assertSee($user->name);
        $response->assertSee($item->name);
        $response->assertSee('Pending');
    }

    public function test_admin_can_approve_loan()
    {
        // Create admin and pending loan
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'peminjam']);
        $item = Item::factory()->create(['status' => 'tersedia']);
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending',
        ]);

        // Act as admin and approve the loan
        $response = $this->actingAs($admin)->put("/admin/peminjaman/{$loan->id}/approve");

        // Assert redirect
        $response->assertRedirect();

        // Assert loan status changed to approved and item status to dipinjam
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'approved',
        ]);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'dipinjam',
        ]);
    }

    public function test_admin_can_reject_loan()
    {
        // Create admin and pending loan
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'peminjam']);
        $item = Item::factory()->create(['status' => 'tersedia']);
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => 'pending',
        ]);

        // Act as admin and reject the loan
        $response = $this->actingAs($admin)->put("/admin/peminjaman/{$loan->id}/reject");

        // Assert redirect
        $response->assertRedirect();

        // Assert loan status changed to rejected and item status unchanged
        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'rejected',
        ]);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'tersedia',
        ]);
    }
}
