<?php

namespace Tests\Feature;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public'); // les images de test ne touchent pas le disque réel
    }

    public function test_fake_image_with_php_content_is_rejected(): void
    {
        $club = $this->club();
        $member = $this->user('member@test.fr', Role::ClubMember, $club);

        $file = UploadedFile::fake()->createWithContent('evil.jpg', '<?php echo "pirate"; ?>');

        $this->actingAs($member)->post(route('manage.media.store'), [
            'title' => 'Faux fichier',
            'file' => $file,
        ])->assertSessionHasErrors('file');

        $this->assertDatabaseCount('media', 0);
    }

    public function test_valid_image_is_accepted_as_pending_media(): void
    {
        $club = $this->club();
        $member = $this->user('member@test.fr', Role::ClubMember, $club);

        $this->actingAs($member)->post(route('manage.media.store'), [
            'title' => 'Vraie photo',
            'file' => UploadedFile::fake()->image('photo.jpg'),
        ])->assertRedirect(route('manage.media.index'));

        $this->assertDatabaseHas('media', [
            'club_id' => $club->id,
            'uploaded_by' => $member->id,
            'type' => 'photo',
            'status' => 'pending',
        ]);
    }
}
