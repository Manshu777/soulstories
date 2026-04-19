<?php

namespace Database\Seeders;

use App\Models\Library;
use App\Models\Story;
use App\Models\StoryChapter;
use App\Models\StoryComment;
use App\Models\StoryLike;
use App\Models\StoryRead;
use App\Models\StoryReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $genres = ['Romance', 'Thriller', 'Drama', 'Comedy', 'Mystery'];
        $languages = ['hindi', 'hinglish'];
        $storyTypes = ['short_story', 'series'];

        // Create 8 writer users (excluding admin)
        $writers = collect();
        for ($i = 1; $i <= 8; $i++) {
            $writers->push(User::firstOrCreate(
                ['email' => "writer{$i}@souldiaries.com"],
                [
                    'name' => fake()->name(),
                    'username' => 'writer' . $i . Str::random(4),
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'bio' => fake()->sentence(8),
                ]
            ));
        }

        $writers->each(function ($u) {
            if (! $u->hasRole('writer')) {
                $u->assignRole('writer');
            }
        });

        $writerIds = $writers->pluck('id')->toArray();

        // Create 15–20 stories
        for ($s = 0; $s < 18; $s++) {
            $userId = $writerIds[array_rand($writerIds)];
            $title = fake()->sentence(3);
            $slug = Str::slug($title) . '-' . Str::random(5);
            while (Story::where('slug', $slug)->exists()) {
                $slug = Str::slug($title) . '-' . Str::random(5);
            }

            $approval = ['pending', 'approved', 'approved', 'rejected'][array_rand(['pending', 'approved', 'approved', 'rejected'])];
            $visibility = $approval === 'approved' ? 'public' : (array_rand([0, 1]) ? 'public' : 'draft');

            $story = Story::create([
                'user_id' => $userId,
                'title' => $title,
                'slug' => $slug,
                'description' => fake()->paragraphs(2, true),
                'genre' => $genres[array_rand($genres)],
                'tags' => [fake()->word(), fake()->word(), fake()->word()],
                'language' => $languages[array_rand($languages)],
                'story_type' => $storyTypes[array_rand($storyTypes)],
                'visibility' => $visibility,
                'status' => array_rand([0, 1]) ? 'ongoing' : 'completed',
                'approval_status' => $approval,
                'approved_at' => $approval === 'approved' ? now() : null,
                'rejected_at' => $approval === 'rejected' ? now() : null,
                'read_time' => 0,
            ]);

            // 2–5 chapters per story
            $numChapters = random_int(2, 5);
            for ($c = 1; $c <= $numChapters; $c++) {
                $content = fake()->paragraphs(4, true);
                $readTime = max(1, (int) ceil(str_word_count($content) / 180));
                StoryChapter::create([
                    'story_id' => $story->id,
                    'chapter_title' => 'Chapter ' . $c . ': ' . fake()->words(3, true),
                    'chapter_number' => $c,
                    'content' => $content,
                    'read_time' => $readTime,
                    'status' => $c === 1 ? 'published' : (array_rand([0, 1]) ? 'published' : 'draft'),
                    'sort_order' => $c - 1,
                ]);
            }
            $story->recalculateReadTime();
        }

        $stories = Story::all();
        $chapters = StoryChapter::where('status', 'published')->get();
        $allUsers = User::where('status', 'active')->pluck('id')->toArray();

        // Followers: random follow relationships
        for ($f = 0; $f < 25; $f++) {
            $follower = $allUsers[array_rand($allUsers)];
            $following = $allUsers[array_rand($allUsers)];
            if ($follower !== $following) {
                DB::table('followers')->insertOrIgnore([
                    'follower_id' => $follower,
                    'following_id' => $following,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Story likes (on stories)
        foreach ($stories->random(min(12, $stories->count())) as $story) {
            $userIds = array_slice($allUsers, 0, random_int(1, 4));
            foreach (array_unique($userIds) as $uid) {
                if ($story->user_id !== $uid) {
                    StoryLike::firstOrCreate(
                        ['user_id' => $uid, 'story_id' => $story->id, 'story_chapter_id' => null]
                    );
                }
            }
        }

        // Chapter likes
        foreach ($chapters->random(min(15, $chapters->count())) as $chapter) {
            $uid = $allUsers[array_rand($allUsers)];
            if ($chapter->story->user_id !== $uid) {
                StoryLike::firstOrCreate(
                    ['user_id' => $uid, 'story_id' => null, 'story_chapter_id' => $chapter->id]
                );
            }
        }

        // Comments on chapters
        foreach ($chapters->random(min(20, $chapters->count())) as $chapter) {
            $numComments = random_int(1, 3);
            for ($i = 0; $i < $numComments; $i++) {
                StoryComment::create([
                    'user_id' => $allUsers[array_rand($allUsers)],
                    'story_chapter_id' => $chapter->id,
                    'body' => fake()->sentence(8),
                    'status' => 'visible',
                ]);
            }
        }

        // Library (saved stories)
        $approvedStories = $stories->where('approval_status', 'approved');
        if ($approvedStories->isNotEmpty()) {
            foreach ($approvedStories->random(min(10, $approvedStories->count())) as $story) {
                $userIds = array_slice($allUsers, 0, random_int(1, 3));
                foreach (array_unique($userIds) as $uid) {
                    if ($story->user_id !== $uid) {
                        Library::firstOrCreate(['user_id' => $uid, 'story_id' => $story->id]);
                    }
                }
            }
        }

        // Story reads
        if ($chapters->isNotEmpty()) {
            foreach ($chapters->random(min(30, $chapters->count())) as $chapter) {
                $uid = $allUsers[array_rand($allUsers)];
                StoryRead::firstOrCreate(
                    [
                        'user_id' => $uid,
                        'story_id' => $chapter->story_id,
                        'story_chapter_id' => $chapter->id,
                    ],
                    ['read_at' => now()]
                );
            }
        }

        // Reports
        foreach ($stories->random(min(3, $stories->count())) as $story) {
            $uid = $allUsers[array_rand($allUsers)];
            if ($story->user_id !== $uid) {
                StoryReport::firstOrCreate(
                    ['user_id' => $uid, 'story_id' => $story->id],
                    ['reason' => 'Other', 'details' => fake()->sentence(4), 'status' => 'pending']
                );
            }
        }
    }
}
