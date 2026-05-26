<?php

namespace Tests\Feature\Drive;

use App\Models\FileManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileManagerStorageLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    private function makeUser(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function makeFileRecord(int $userId, int $sizeBytes): FileManager
    {
        return FileManager::create([
            'link' => \Illuminate\Support\Str::random(7),
            'name' => 'existing.pdf',
            'path' => 'drive/'.$userId.'/existing.pdf',
            'size' => $sizeBytes,
            'format' => 'pdf',
            'mime_type' => 'application/pdf',
            'user_id' => $userId,
            'parent_id' => null,
            'is_folder' => false,
            'isDeleted' => 0,
            'google_drive_id' => null,
            'uploader_id' => $userId,
        ]);
    }

    /** @test */
    public function employer_can_upload_within_the_500mb_limit(): void
    {
        $user = $this->makeUser('employer');
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('resume.pdf', 1024); // 1 MB

        $response = $this->post(route('drive.file.upload'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('t_file_manager', [
            'user_id' => $user->id,
            'name' => 'resume.pdf',
        ]);
    }

    /** @test */
    public function applicant_can_upload_within_the_500mb_limit(): void
    {
        $user = $this->makeUser('applicant');
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('cv.pdf', 512); // 512 KB

        $response = $this->post(route('drive.file.upload'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('t_file_manager', [
            'user_id' => $user->id,
            'name' => 'cv.pdf',
        ]);
    }

    /** @test */
    public function employer_can_upload_a_file_in_chunks(): void
    {
        $user = $this->makeUser('employer');
        $this->actingAs($user);

        $uploadId = 'upload-'.\Illuminate\Support\Str::random(12);
        $fileName = 'large-contract.pdf';
        $fileMime = 'application/pdf';
        $content = str_repeat('ABCD1234', 700000);
        $chunkSize = 2 * 1024 * 1024;
        $chunks = str_split($content, $chunkSize);

        foreach ($chunks as $index => $chunkContent) {
            $response = $this->post(
                route('drive.file.upload'),
                [
                    'file' => UploadedFile::fake()->createWithContent('chunk-'.$index.'.part', $chunkContent),
                    'upload_id' => $uploadId,
                    'chunk_index' => $index,
                    'total_chunks' => count($chunks),
                    'file_name' => $fileName,
                    'file_size' => strlen($content),
                    'file_mime' => $fileMime,
                ],
                [
                    'Accept' => 'application/json',
                    'X-Requested-With' => 'XMLHttpRequest',
                ]
            );

            $response->assertOk();
            $response->assertJson(['success' => true]);
        }

        $finalize = $this->post(
            route('drive.file.upload'),
            [
                'upload_action' => 'finalize',
                'upload_id' => $uploadId,
            ],
            [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ]
        );

        $finalize->assertOk();
        $finalize->assertJson(['success' => true]);

        $record = FileManager::where('user_id', $user->id)->where('name', $fileName)->first();

        $this->assertNotNull($record);
        $this->assertSame(strlen($content), $record->size);
        $this->assertSame($content, Storage::disk('local')->get($record->path));
    }

    /** @test */
    public function employer_cannot_upload_when_storage_limit_is_exceeded(): void
    {
        $user = $this->makeUser('employer');
        $this->actingAs($user);

        // Fill storage to just under the limit (499 MB used)
        $this->makeFileRecord($user->id, 499 * 1048576);

        // Try to upload a 2 MB file (would push total to 501 MB)
        $file = UploadedFile::fake()->create('overflow.pdf', 2048);

        $response = $this->post(route('drive.file.upload'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('file');
        $this->assertDatabaseMissing('t_file_manager', [
            'user_id' => $user->id,
            'name' => 'overflow.pdf',
        ]);
    }

    /** @test */
    public function applicant_cannot_upload_when_storage_limit_is_exceeded(): void
    {
        $user = $this->makeUser('applicant');
        $this->actingAs($user);

        // Fill storage to the exact limit (500 MB)
        $this->makeFileRecord($user->id, 500 * 1048576);

        $file = UploadedFile::fake()->create('one-more.pdf', 1024);

        $response = $this->post(route('drive.file.upload'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('file');
        $this->assertDatabaseMissing('t_file_manager', [
            'user_id' => $user->id,
            'name' => 'one-more.pdf',
        ]);
    }

    /** @test */
    public function admin_is_not_subject_to_storage_limit(): void
    {
        $user = $this->makeUser('admin');
        $this->actingAs($user);

        // Fill beyond the 500 MB limit
        $this->makeFileRecord($user->id, 600 * 1048576);

        $file = UploadedFile::fake()->create('admin-file.pdf', 1024);

        $response = $this->post(route('drive.file.upload'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('t_file_manager', [
            'user_id' => $user->id,
            'name' => 'admin-file.pdf',
        ]);
    }

    /** @test */
    public function super_admin_is_not_subject_to_storage_limit(): void
    {
        $user = $this->makeUser('super_admin');
        $this->actingAs($user);

        $this->makeFileRecord($user->id, 600 * 1048576);

        $file = UploadedFile::fake()->create('super-file.pdf', 1024);

        $response = $this->post(route('drive.file.upload'), [
            'file' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('t_file_manager', [
            'user_id' => $user->id,
            'name' => 'super-file.pdf',
        ]);
    }

    /** @test */
    public function used_storage_bytes_helper_only_counts_non_deleted_files(): void
    {
        $user = $this->makeUser('employer');

        // Active file: 100 MB
        $this->makeFileRecord($user->id, 100 * 1048576);

        // Deleted file: 200 MB (should not be counted)
        FileManager::create([
            'link' => \Illuminate\Support\Str::random(7),
            'name' => 'deleted.pdf',
            'path' => 'drive/'.$user->id.'/deleted.pdf',
            'size' => 200 * 1048576,
            'format' => 'pdf',
            'mime_type' => 'application/pdf',
            'user_id' => $user->id,
            'parent_id' => null,
            'is_folder' => false,
            'isDeleted' => 1,
            'google_drive_id' => null,
            'uploader_id' => $user->id,
        ]);

        $used = FileManager::usedStorageBytes($user->id);

        $this->assertEquals(100 * 1048576, $used);
    }

    /** @test */
    public function storage_quota_bar_is_visible_for_employer_on_file_manager_page(): void
    {
        $user = $this->makeUser('employer');
        $this->actingAs($user);

        $this->makeFileRecord($user->id, 100 * 1048576);

        $response = $this->get(route('filemanager.index'));

        $response->assertOk();
        $response->assertSee('Storage Used');
        $response->assertSee('500 MB');
    }

    /** @test */
    public function storage_quota_bar_is_not_visible_for_admin_on_file_manager_page(): void
    {
        $user = $this->makeUser('admin');
        $this->actingAs($user);

        $response = $this->get(route('filemanager.index'));

        $response->assertOk();
        $response->assertDontSee('Storage Used');
    }
}
