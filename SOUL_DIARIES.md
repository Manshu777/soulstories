# Soul Diaries – Story Publishing System

Minimalist story platform (Hindi/Hinglish, 5–10 min reads). Only **logged-in users** can publish; stories are **approved by admin** before going public.

---

## 1. Database schema

| Table | Purpose |
|-------|--------|
| **users** | Existing (name, email, username, avatar, bio, status, etc.). `status`: active / blocked. |
| **stories** | Series/Book. `user_id`, title, slug, cover_image, description, genre, tags (JSON), language (hindi/hinglish), story_type (short_story/series), content_guidance, visibility (public/draft), status (ongoing/completed), approval_status (pending/approved/rejected), approved_at, rejected_at, admin_notes, read_time. |
| **story_chapters** | Chapters. `story_id`, chapter_title, chapter_number, content, read_time, audio_file, spotify_playlist, status (draft/published), sort_order. |
| **story_likes** | Likes on story or chapter. `user_id`, `story_id` OR `story_chapter_id`. |
| **story_comments** | Chapter comments (and line comments via line_number). `user_id`, story_chapter_id, parent_id, body, line_number, status (visible/hidden/removed). |
| **followers** | Existing. follower_id, following_id. |
| **libraries** | Saved stories. user_id, story_id. |
| **story_reads** | Reading history / total reads. user_id, story_id, story_chapter_id (optional), read_at. |
| **story_reports** | Report story. user_id, story_id, reason, details, status. |

---

## 2. Laravel models

- **App\Models\Story** – `user()`, `chapters()`, `publishedChapters()`, `likes()`, `libraries()`, `storyReads()`, `reports()`, `isApproved()`, `isPublic()`, `recalculateReadTime()`. Route key: `slug`.
- **App\Models\StoryChapter** – `story()`, `comments()`, `likes()`, `previousChapter()`, `nextChapter()`.
- **App\Models\StoryLike** – story or chapter like. `user()`, `story()`, `storyChapter()`.
- **App\Models\StoryComment** – `user()`, `storyChapter()`, `parent()`, `replies()`.
- **App\Models\Library** – `user()`, `story()`.
- **App\Models\StoryRead** – `user()`, `story()`, `storyChapter()`.
- **App\Models\StoryReport** – `user()`, `story()`.
- **User** – Soul Diaries: `stories()`, `library()`, `storyReads()`, `storyLikes()`, `isBlocked()`.

---

## 3. Controllers

| Controller | Responsibility |
|------------|----------------|
| **Diary\StoryController** | index (home), show, create, store, edit, update, destroy. |
| **Diary\ChapterController** | show (record read), create, store, edit, update, destroy, reorder. |
| **Diary\DashboardController** | Single action: dashboard (create story, my stories, drafts, followers, analytics). |
| **Diary\AuthorController** | show (profile), follow, unfollow. |
| **Diary\LikeController** | story(), chapter() – toggle like. |
| **Diary\CommentController** | store. |
| **Diary\LibraryController** | index, store, destroy. |
| **Diary\ReportController** | store. |
| **Admin\AdminStoryController** | index (stories), approve, reject, destroy, blockUser, unblockUser, moderateComments, hideComment, removeComment, showComment. |

---

## 4. Routes

**Diary** (`routes/diary.php`):

- **Public:** `GET /` (home), `GET /stories/{slug}`, `GET /author/{username}`, `GET /stories/{story}/chapters/{chapter}` (chapter read).
- **Auth:** `GET /dashboard`, create/update/delete story & chapters, like (story/chapter), comment, library (index/store/destroy), report, follow/unfollow.

**Admin** (`routes/admin.php`, middleware `auth` + `admin`):

- Prefix: `/admin`. Stories: index, approve, reject, destroy. Users: block, unblock. Comments: index, hide, remove, show.

**Web** (`routes/web.php`):

- Login, register, forgot-password, verify-otp views; then `require diary.php` and `admin.php`.

---

## 5. Minimal UI (Blade, mobile-first)

- **Layout:** `layouts.app` + `components.navbar` (Tailwind, Soul Diaries branding).
- **Pages:**  
  - Home: `diary.stories.index`  
  - Story: `diary.stories.show` (cover, author, follow, likes, reads, library, report, share, chapters list).  
  - Chapter: `diary.chapters.show` (content, prev/next, like, comments, optional audio/Spotify).  
  - Author: `diary.authors.show` (profile, follow, stories).  
  - Create/Edit story: `diary.stories.create`, `diary.stories.edit` (with chapters list).  
  - Create/Edit chapter: `diary.chapters.create`, `diary.chapters.edit`.  
  - Dashboard: `diary.dashboard` (create story, my stories, library, analytics, followers).  
  - Library: `diary.library.index`.  
  - Admin: `admin.stories.index`, `admin.comments.index`.

Design: clean white layout, simple fonts, Medium-like, mobile-friendly.

---

## 6. API structure (optional)

Current app is **web (session)**. For a future API:

- Use same controllers or add `Api\` controllers that return JSON.
- Protect with `auth:sanctum` and use the same models and policies.
- Suggested endpoints: `GET /api/stories`, `GET /api/stories/{slug}`, `POST /api/stories`, `GET /api/stories/{story}/chapters`, `POST /api/stories/{story}/chapters`, `POST /api/stories/{story}/like`, `POST /api/comments`, `GET /api/me/library`, etc.

---

## Setup

1. **Migrations:** Already run for Soul Diaries tables (`stories`, `story_chapters`, `story_likes`, `story_comments`, `libraries`, `story_reads`, `story_reports`).
2. **Roles:** `php artisan db:seed` (runs `RoleSeeder`: creates `admin` and `writer` roles). Assign admin: `User::find(1)->assignRole('admin');` (e.g. in tinker).
3. **Auth:** Use existing login/register (Breeze or API). After **web** login, user can access dashboard and publish.
4. **Admin:** Only users with role `admin` can access `/admin` (middleware `admin`).

---

## Rules (enforced in app)

- Only logged-in users can publish (create/edit story and chapters).
- Stories are visible as “public” only when `approval_status = approved` and `visibility = public`.
- Hindi / Hinglish only (language field).
- Read time is auto-calculated from chapter content (words / 180 wpm).
