<?php

namespace Tests\Feature;

use App\Livewire\LiveMonitorOverview;
use App\Models\Device;
use App\Models\Group;
use App\Models\Log;
use App\Models\Presentation;
use App\Models\Schedule;
use App\Models\Slide;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\SettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed([SettingsSeeder::class, PermissionSeeder::class]);
    }

    private function user(string $role, string $name = 'Jane Doe'): User
    {
        $user = User::create(['name' => $name, 'email' => uniqid().'@example.com', 'password' => 'secret']);
        $user->syncRoles([$role]);

        return $user;
    }

    private function presentation(int $slides = 1): Presentation
    {
        $this->actingAs($this->user('Super Administrator', 'Creator'));

        $presentation = Presentation::create(['name' => 'Pres '.uniqid(), 'description' => 'null', 'author' => 'Test', 'processed' => true, 'total_slides' => $slides]);

        for ($i = 1; $i <= $slides; $i++) {
            Slide::create(['presentation_id' => $presentation->id, 'order' => $i, 'type' => 'image', 'name' => 'x', 'name_on_disk' => "x-$i.jpg"]);
        }

        return $presentation;
    }

    private function device(array $attributes = []): Device
    {
        return Device::create(array_merge([
            'name' => 'Monitor', 'description' => 'Hall', 'ip_address' => '10.0.0.5',
            'secret' => 'abc123', 'registered' => true, 'active' => true,
        ], $attributes));
    }

    public function test_admin_pages_render(): void
    {
        $presentation = $this->presentation();
        $empty = Presentation::create(['name' => 'Empty', 'description' => 'null', 'author' => 'Test', 'processed' => true]);
        $group = Group::create(['name' => 'Group', 'presentation_id' => $empty->id, 'created_by' => 'Test']);
        $device = $this->device(['presentation_id' => $presentation->id, 'last_seen' => now()]);
        $schedule = Schedule::create([
            'name' => 'Schedule', 'presentation_id' => $presentation->id, 'devices' => [$device->id], 'groups' => [],
            'start_time' => now()->addDay(), 'end_time' => now()->addDays(2), 'enabled' => true, 'created_by' => 'Test',
        ]);

        $admin = $this->user('Super Administrator', 'Doe,  John');
        $this->actingAs($admin);

        foreach (['/devices', "/devices/{$device->id}", '/presentations', "/presentations/{$presentation->id}",
            '/presentations/ongoing', '/groups', "/groups/{$group->id}", '/schedules', '/schedules/create',
            '/users', "/users/{$admin->id}", '/logs', '/settings'] as $url) {
            $this->get($url)->assertOk();
        }

        // datetime-local inputs need the "T" separator, otherwise the browser shows empty fields
        $this->get("/schedules/{$schedule->id}")->assertOk()
            ->assertSee('value="'.$schedule->start_time->format('Y-m-d\TH:i').'"', false);

        $this->assertSame('DJ', $admin->initials());
    }

    public function test_device_page_for_user_without_update_permission(): void
    {
        $device = $this->device(['presentation_id' => $this->presentation()->id]);

        $this->actingAs($this->user('Supporter'))
            ->get("/devices/{$device->id}")
            ->assertOk();
    }

    public function test_user_without_role_can_use_menu(): void
    {
        $this->actingAs($this->user('User'));
        $other = $this->user('User', 'Other');
        $other->syncRoles([]);

        // Menu used roles[0] and crashed for users without a role
        $this->actingAs($other)->get('/devices')->assertOk()->assertSee('No roles');
    }

    public function test_registration_and_password_reset_are_disabled(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', ['name' => 'x', 'email' => 'x@example.com', 'password' => 'password', 'password_confirmation' => 'password'])->assertNotFound();
        $this->get('/password/reset')->assertNotFound();
    }

    public function test_first_user_becomes_super_administrator(): void
    {
        $user = User::create(['name' => 'First', 'email' => 'first@example.com', 'password' => 'secret']);
        $this->assertTrue($user->hasRole('User'));

        $this->actingAs($user)->get('/devices')->assertOk();

        $this->assertTrue($user->fresh()->hasRole('Super Administrator'));
    }

    public function test_monitor_api_and_page(): void
    {
        $presentation = $this->presentation(2);
        $device = $this->device(['presentation_id' => $presentation->id]);
        auth()->logout();

        $this->get('/monitor/abc123')->assertOk()->assertSee('x-2.jpg');

        $this->post('/api/devices/monitor/update', [
            'secret' => 'abc123', 'currentSlide' => 1, 'last_update' => 1,
            'startup_timestamp' => time(), 'presentation_id' => $presentation->id,
        ], ['X-Forwarded-For' => '10.1.2.3'])
            ->assertOk()
            ->assertJsonPath('presentation_id', $presentation->id)
            ->assertJsonPath('error', false);

        $device->refresh();
        $this->assertNotNull($device->last_seen);
        $this->assertSame(1, (int) $device->current_slide);
        $this->assertSame('10.1.2.3', $device->ip_address);
    }

    public function test_monitor_without_slides_shows_message(): void
    {
        $device = $this->device(['presentation_id' => $this->presentation(0)->id]);

        $this->get("/monitor/{$device->secret}")->assertOk()->assertSee('Presentation has no slides');
    }

    public function test_presentation_with_active_schedule_can_not_be_deleted(): void
    {
        $presentation = $this->presentation();
        Schedule::create([
            'name' => 'Active', 'presentation_id' => $presentation->id, 'devices' => [], 'groups' => [],
            'start_time' => now()->subHour(), 'end_time' => now()->addHour(), 'enabled' => true, 'created_by' => 'Test',
        ]);

        $this->delete("/presentations/{$presentation->id}")->assertSessionHasErrors();
        $this->assertModelExists($presentation);
    }

    public function test_presentation_with_finished_schedule_can_be_deleted(): void
    {
        $presentation = $this->presentation();
        $schedule = Schedule::create([
            'name' => 'Past', 'presentation_id' => $presentation->id, 'devices' => [], 'groups' => [],
            'start_time' => now()->subDays(2), 'end_time' => now()->subDay(), 'enabled' => true, 'created_by' => 'Test',
        ]);

        $this->delete("/presentations/{$presentation->id}")->assertRedirect('/presentations');

        $this->assertModelMissing($presentation);
        $this->assertSame(0, (int) $schedule->fresh()->presentation_id);
    }

    public function test_expired_schedule_deletes_presentation_without_logged_in_user(): void
    {
        $presentation = $this->presentation();
        $device = $this->device(['presentation_id' => $this->presentation()->id]);
        Schedule::create([
            'name' => 'Expired', 'presentation_id' => $presentation->id, 'devices' => [$device->id], 'groups' => [],
            'start_time' => now()->subDays(2), 'end_time' => now()->subDay(), 'enabled' => true,
            'created_by' => 'Test', 'delete_presentation' => true,
        ]);
        auth()->logout();

        $this->post('/api/devices/monitor/update', [
            'secret' => 'abc123', 'currentSlide' => 0, 'last_update' => 1,
            'startup_timestamp' => time(), 'presentation_id' => 1,
        ])->assertOk();

        $this->assertModelMissing($presentation);
        $this->assertTrue(Log::where('username', 'System')->where('action', 'like', '%'.$presentation->name.'%')->exists());
    }

    public function test_schedule_dates_are_stored_in_app_timezone(): void
    {
        $this->actingAs($this->user('Super Administrator'));
        $presentation = $this->presentation();

        $this->post('/schedules', [
            'name' => 'Timezone', 'start_date' => '2030-06-01T08:00', 'end_date' => '2030-06-01T18:00',
            'devices' => [$this->device()->id], 'presentation_id' => $presentation->id,
        ])->assertRedirect('/schedules');

        $schedule = Schedule::where('name', 'Timezone')->firstOrFail();
        $this->assertSame('2030-06-01 08:00', $schedule->start_time->format('Y-m-d H:i'));
    }

    public function test_device_update_during_schedule_keeps_own_presentation(): void
    {
        $own = $this->presentation();
        $device = $this->device(['presentation_id' => $own->id]);
        Schedule::create([
            'name' => 'Active', 'presentation_id' => $this->presentation()->id, 'devices' => [$device->id], 'groups' => [],
            'start_time' => now()->subHour(), 'end_time' => now()->addHour(), 'enabled' => true, 'created_by' => 'Test',
        ]);

        // The presentation select is not shown while a schedule is active
        $this->put("/devices/{$device->id}", ['name' => 'Renamed', 'description' => 'Hall'])->assertRedirect();

        $this->assertSame($own->id, (int) $device->fresh()->presentation_id);
        $this->assertSame('Renamed', $device->fresh()->name);
    }

    public function test_users_can_not_delete_admins_or_themselves(): void
    {
        $admin = $this->user('Super Administrator');
        $otherAdmin = $this->user('Super Administrator', 'Other Admin');

        $this->actingAs($admin)->delete("/users/{$admin->id}")->assertSessionHasErrors();
        $this->actingAs($admin)->delete("/users/{$otherAdmin->id}")->assertSessionHasErrors();
        $this->assertModelExists($admin);
        $this->assertModelExists($otherAdmin);
    }

    public function test_user_role_can_be_changed(): void
    {
        $admin = $this->user('Super Administrator');
        $user = $this->user('User', 'Someone');
        $manager = \Spatie\Permission\Models\Role::findByName('Manager', 'sanctum');

        $this->actingAs($admin)->put("/users/{$user->id}", ['roles' => $manager->id])->assertSessionHasNoErrors();

        $this->assertSame(['Manager'], $user->fresh()->getRoleNames()->all());
    }

    public function test_slide_can_be_deleted(): void
    {
        $presentation = $this->presentation(2);
        $slide = $presentation->slides()->first();

        $this->delete("/slides/{$slide->id}")->assertRedirect();
        $this->assertModelMissing($slide);

        $this->delete('/slides/999')->assertRedirect()->assertSessionHasErrors();
    }

    public function test_live_monitor_overview_sorting_is_remembered(): void
    {
        $this->actingAs($this->user('Super Administrator'));
        $this->device(['name' => 'B']);
        $this->device(['name' => 'A', 'secret' => 'other']);

        Livewire::test(LiveMonitorOverview::class)
            ->assertSeeInOrder(['A', 'B'])
            ->set('sort_by', 'status')
            ->assertOk();

        Livewire::test(LiveMonitorOverview::class)->assertSet('sort_by', 'status');
        Livewire::test(LiveMonitorOverview::class)->set('sort_by', 'invalid')->assertSet('sort_by', 'name');
    }

    private function divBalance(string $html): int
    {
        return substr_count($html, '<div') - substr_count($html, '</div');
    }

    public function test_schedule_form_prefills_dates_and_validates(): void
    {
        $this->actingAs($this->user('Super Administrator'));

        // datetime-local inputs need the "T" separator, otherwise the browser shows empty fields
        $this->get('/schedules/create')->assertOk()
            ->assertSee('value="'.now()->format('Y-m-d\TH:00').'"', false)
            ->assertSee('onsubmit="return validate()"', false);
    }

    public function test_presentation_can_be_renamed_without_new_file(): void
    {
        $presentation = $this->presentation();

        $html = $this->get("/presentations/{$presentation->id}")->assertOk()->getContent();
        $this->assertDoesNotMatchRegularExpression('/name="file"\s+required/', $html);

        $this->put("/presentations/{$presentation->id}", ['name' => 'Renamed'])->assertSessionHasNoErrors();
        $this->assertSame('Renamed', $presentation->fresh()->name);
    }

    public function test_read_only_user_does_not_see_placeholder_description(): void
    {
        $presentation = $this->presentation();

        $this->actingAs($this->user('Supporter'))
            ->get("/presentations/{$presentation->id}")
            ->assertOk()
            ->assertDontSee('</b> null</p>', false);
    }

    public function test_slide_list_has_balanced_markup(): void
    {
        $presentation = $this->presentation(2);

        $this->assertSame(0, $this->divBalance(view('livewire.show-slides', ['presentation' => $presentation])->render()));
    }

    public function test_device_page_markup_is_the_same_for_read_only_users(): void
    {
        $device = $this->device(['presentation_id' => $this->presentation()->id]);

        $admin = $this->actingAs($this->user('Super Administrator', 'Admin'))->get("/devices/{$device->id}")->getContent();
        $supporter = $this->actingAs($this->user('Supporter', 'Support'))->get("/devices/{$device->id}")->getContent();

        $this->assertSame($this->divBalance($admin), $this->divBalance($supporter));
    }

    public function test_never_connected_device_is_not_active(): void
    {
        $device = $this->device(['presentation_id' => $this->presentation()->id, 'last_seen' => null]);
        $device->created_at = now()->subDay();
        $device->save();

        $this->assertFalse($device->fresh()->isActive());
    }

    public function test_monitor_reports_independently_of_slide_changes(): void
    {
        $device = $this->device(['presentation_id' => $this->presentation()->id]);

        // Otherwise long videos or slide intervals make the monitor look offline
        $this->get("/monitor/{$device->secret}")->assertOk()
            ->assertSee('setInterval(giveServerUpdate, 30 * 1000)', false);
    }

    public function test_only_authorized_users_can_stop_processing(): void
    {
        $presentation = $this->presentation();
        $presentation->update(['processed' => false]);

        $this->actingAs($this->user('User', 'Plain'))->post("/presentations/ongoing/{$presentation->id}")->assertForbidden();
        $this->actingAs($this->user('User', 'Plain 2'))->get('/presentations/ongoing')->assertForbidden();
        $this->assertFalse((bool) $presentation->fresh()->processed);

        $this->actingAs($this->user('Super Administrator', 'Boss'))->post("/presentations/ongoing/{$presentation->id}")->assertRedirect('/presentations/ongoing');
        $this->assertTrue((bool) $presentation->fresh()->processed);
    }

    public function test_english_log_translations_exist(): void
    {
        app()->setLocale('en');

        foreach (['presentation_deleted_because_schedule', 'presentation_not_deleted_because_schedule'] as $key) {
            $this->assertNotSame("log.$key", __("log.$key", ['name' => 'A', 'schedule' => 'B']));
        }
    }

    public function test_long_log_messages_fit_into_the_column(): void
    {
        $log = Log::create(['ip_address' => '127.0.0.1', 'username' => 'System', 'action' => str_repeat('x', 400)]);

        $this->assertLessThanOrEqual(255, strlen($log->fresh()->action));
    }

    public function test_default_seeder_does_not_create_demo_data(): void
    {
        $this->seed();

        $this->assertSame(0, Device::count());
        $this->assertSame(0, Group::count());
    }
}
