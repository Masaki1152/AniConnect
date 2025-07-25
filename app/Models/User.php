<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Helpers\PaginationHelper;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 参照させたいworksを指定
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'age',
        'sex',
        'image',
        'password',
        'introduction',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 投稿の種類ごとのモデルとリレーション設定
    protected $postMaps;
    public function __construct()
    {
        $this->postMaps = [
            'work' => [
                'name' => __('common.work_post'),
                'model' => WorkPost::class,
                'commentModel' => WorkPostComment::class,
                'relations' => [
                    'work' => ['name', 'term']
                ],
                'post_id' => 'work_post_id'
            ],
            'workStory' => [
                'name' => __('common.work_story_post'),
                'model' => WorkStoryPost::class,
                'commentModel' => WorkStoryPostComment::class,
                'relations' => [
                    'work' => ['name', 'term'],
                    'workStory' => ['sub_title', 'episode'],
                ],
                'post_id' => 'work_story_post_id'
            ],
            'character' => [
                'name' => __('common.character_post'),
                'model' => CharacterPost::class,
                'commentModel' => CharacterPostComment::class,
                'relations' => [
                    'character' => ['name'],
                    'character.works' => ['name', 'term'],
                    'character.voiceArtist' => ['name'],
                ],
                'post_id' => 'character_post_id'
            ],
            'music' => [
                'name' => __('common.music_post'),
                'model' => MusicPost::class,
                'commentModel' => MusicPostComment::class,
                'relations' => [
                    'music' => ['name'],
                    'music.work' => ['name', 'term'],
                    'music.singer' => ['name'],
                ],
                'post_id' => 'music_post_id'
            ],
            'animePilgrimage' => [
                'name' => __('common.anime_pilgrimage_post'),
                'model' => AnimePilgrimagePost::class,
                'commentModel' => AnimePilgrimagePostComment::class,
                'relations' => [
                    'animePilgrimage' => ['name', 'place'],
                    'animePilgrimage.works' => ['name', 'term'],
                ],
                'post_id' => 'anime_pilgrimage_post_id'
            ],
        ];
    }

    // 投稿の種類とユーザーidで投稿を取得する処理
    public function fetchPosts($user_id, $postType, $search)
    {
        // typeが'none'の場合、すべての投稿を取得
        if ($postType === 'none') {
            $posts = collect();
            foreach ($this->postMaps as $key => $config) {
                $query = $this->searchAndProcessPosts($config, $user_id, $search, $key, 'model', 'posts');
                $posts = $posts->merge($query);
            }
            // 作成日時でソートしてページネーション
            $posts = $posts->sortByDesc('created_at')->values();
            return PaginationHelper::paginateCollection($posts, 10);
        }
        // typeが指定されている場合
        $config = $this->postMaps[$postType];
        $posts = $this->searchAndProcessPosts($config, $user_id, $search, $postType, 'model', 'posts');
        // 作成日時でソートしてページネーション
        $posts = $posts->sortByDesc('created_at')->values();
        return PaginationHelper::paginateCollection($posts, 10);
    }

    // 投稿の種類とユーザーidでコメントを取得する処理
    // 検索はコメント内のbodyのみ
    public function fetchComments($user_id, $postType, $search)
    {
        // typeが'none'の場合、すべてのコメントを取得
        if ($postType === 'none') {
            $posts = collect();
            foreach ($this->postMaps as $key => $config) {
                $query = $this->searchAndProcessPosts($config, $user_id, $search, $key, 'commentModel', 'comments');
                $posts = $posts->merge($query);
            }
            // 作成日時でソートしてページネーション
            $posts = $posts->sortByDesc('created_at')->values();
            return PaginationHelper::paginateCollection($posts, 10);
        }
        // typeが指定されている場合
        $config = $this->postMaps[$postType];
        $posts = $this->searchAndProcessPosts($config, $user_id, $search, $postType, 'commentModel', 'comments');
        // 作成日時でソートしてページネーション
        $posts = $posts->sortByDesc('created_at')->values();
        return PaginationHelper::paginateCollection($posts, 10);
    }

    // 投稿の種類とユーザーidで投稿を取得する処理
    public function fetchLikePosts($user_id, $type, $search)
    {
        $posts = collect();
        // typeが'none'の場合、すべての投稿を取得
        if ($type === 'none') {

            foreach ($this->postMaps as $key => $config) {
                //  いいねした投稿を取得
                $postQuery = $this->fetchAndProcessLikedPost($config, $key, $user_id, $search, 'model', 'likedPosts', $config['relations']);
                $posts = $posts->merge($postQuery);
                //  いいねしたコメントを取得
                $commentQuery = $this->fetchAndProcessLikedPost($config, $key, $user_id, $search, 'commentModel', 'comments');
                $posts = $posts->merge($commentQuery);
            }
            // 作成日時でソートしてページネーション
            $posts = $posts->sortByDesc('liked_created_at')->values();
            // 作成日時でソートしてページネーション
            return PaginationHelper::paginateCollection($posts, 10);
        }

        $config = $this->postMaps[$type];
        //  いいねした投稿を取得
        $postQuery = $this->fetchAndProcessLikedPost($config, $type, $user_id, $search, 'model', 'likedPosts', $config['relations']);
        $posts = $posts->merge($postQuery);
        //  いいねしたコメントを取得
        $commentQuery = $this->fetchAndProcessLikedPost($config, $type, $user_id, $search, 'commentModel', 'comments');
        $posts = $posts->merge($commentQuery);
        // 作成日時でソートしてページネーション
        $posts = $posts->sortByDesc('liked_created_at')->values();
        // 作成日時でソートしてページネーション
        return PaginationHelper::paginateCollection($posts, 10);
    }

    // 投稿とコメントの検索を行う
    public function applySearchFilter($query, $search, $fetchType, $relations = null)
    {
        if ($search != '') {
            // 検索語のスペースを半角に統一
            $search_split = mb_convert_kana($search, 's');
            // 半角スペースで単語ごとに分割して配列にする
            $search_array = preg_split('/[\s]+/', $search_split);

            $query->where(function ($query) use ($search_array, $relations, $fetchType) {
                foreach ($search_array as $search_word) {
                    // コメント取得の場合
                    if ($fetchType === 'comments') {
                        $query->where(function ($query) use ($search_array) {
                            foreach ($search_array as $search_word) {
                                $query->orWhere(function ($query) use ($search_word) {
                                    // 投稿のタイトル・本文で検索
                                    $query->where('body', 'LIKE', "%{$search_word}%");
                                });
                            }
                        });
                        return $query;
                    }

                    $query->where(function ($query) use ($search_word, $relations) {
                        // 投稿のタイトル・本文で検索
                        $query->where('post_title', 'LIKE', "%{$search_word}%")
                            ->orWhere('body', 'LIKE', "%{$search_word}%");

                        // 各リレーション先のカラムでも検索
                        foreach ($relations as $relation => $columns) {
                            $query->orWhereHas($relation, function ($relationQuery) use ($search_word, $columns) {
                                $relationQuery->where(function ($subQuery) use ($search_word, $columns) {
                                    foreach ($columns as $column) {
                                        $subQuery->orWhere($column, 'LIKE', "%{$search_word}%");
                                    }
                                });
                            });
                        }
                    });
                }
            });
        }
    }

    // 投稿やコメントにいいねした時間を追加
    public function addPostLikedTime($query, $user_id)
    {
        $query->each(function ($post) use ($user_id) {
            $post->liked_created_at = optional($post->users->firstWhere('id', $user_id))
                ->pivot
                ->created_at;
        });
    }

    // 投稿またはコメントを取得して加工する
    public function searchAndProcessPosts($config, $user_id, $search, $postType, $modelType, $fetchType)
    {
        $query = $config[$modelType]::where('user_id', $user_id);
        // コメント取得以外の場合のみwith()を適用
        if ($fetchType !== 'comments') {
            $query->with(array_keys($config['relations']));
        }

        $query->where(function ($query) use ($search, $config, $fetchType) {
            $this->applySearchFilter($query, $search, $fetchType, $config['relations']);
        });
        $posts = $query->orderBy('created_at', 'DESC')->get();
        // データの加工
        $this->addPostType($posts, $postType);
        $this->createTypeToURL($posts, $postType);
        return $posts;
    }

    // 取得した投稿に投稿の種類を付与する処理
    public function addPostType($posts, $postType)
    {
        $config = $this->postMaps[$postType];
        // 各投稿に投稿の種類を追加
        $posts->transform(function ($post) use ($config) {
            $post->postType = $config['name'];
            return $post;
        });
    }

    // 投稿の種類からリンク用のパスを生成
    public function createTypeToURL($posts, $postType)
    {
        // 各投稿の種類のURL
        $urls = [
            // 作品感想の詳細ページ
            'work' => function ($post) {
                return "/work_posts/{$post->work_id}/posts";
            },
            // あらすじ感想の詳細ページ
            'workStory' => function ($post) {
                return "/works/{$post->work_id}/stories/{$post->sub_title_id}/posts";
            },
            // 登場人物感想の詳細ページ
            'character' => function ($post) {
                return "/character_posts/{$post->character_id}/posts";
            },
            // 音楽感想の詳細ページ
            'music' => function ($post) {
                return "/music_posts/{$post->music_id}/posts";
            },
            // 聖地感想の詳細ページ
            'animePilgrimage' => function ($post) {
                return "/pilgrimage_posts/{$post->anime_pilgrimage_id}/posts";
            },
        ];

        // 投稿の種類に応じたURLを追加
        $posts->transform(function ($post) use ($urls, $postType) {
            $post->postURL = $urls[$postType]($post) . "/{$post->id}";
            return $post;
        });
    }

    // ユーザーがいいねした投稿とコメントを取得
    public function fetchAndProcessLikedPost($config, $key, $user_id, $search, $modelType, $fetchType, $relations = null)
    {
        // ユーザーがいいねしたコメントのidを取得
        $likedPostIds = $config[$modelType]::whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
            ->pluck('id');

        //  いいねしたコメントを取得
        $query = $config[$modelType]::whereIn('id', $likedPostIds)
            ->where(function ($query) use ($search, $fetchType, $relations) {
                $this->applySearchFilter($query, $search, $fetchType, $relations);
            })
            ->get();

        $this->addPostType($query, $key);
        $this->createTypeToURL($query, $key);
        $this->addPostLikedTime($query, $user_id);
        return $query;
    }

    // WorkPostに対するリレーション 1対1の関係
    public function workPost()
    {
        return $this->belongsTo(WorkPost::class, 'id', 'user_id');
    }

    // いいねをしたWorkPostに対するリレーション 多対多の関係
    public function workPosts()
    {
        return $this->belongsToMany(WorkPost::class);
    }

    // CharacterPostに対するリレーション 1対1の関係
    public function characterPost()
    {
        return $this->hasOne(CharacterPost::class, 'id', 'character_id');
    }

    // いいねをしたCharacterPostに対するリレーション 多対多の関係
    public function characterPosts()
    {
        return $this->belongsToMany(CharacterPost::class, 'character_posts_users', 'user_id', 'character_post_id');
    }

    // MusicPostに対するリレーション 1対1の関係
    public function musicPost()
    {
        return $this->hasOne(MusicPost::class, 'id', 'music_id');
    }

    // いいねをしたMusicPostに対するリレーション 多対多の関係
    public function musicPosts()
    {
        return $this->belongsToMany(MusicPost::class, 'music_posts_users', 'user_id', 'music_post_id');
    }

    // AnimePilgrimagePostに対するリレーション 1対1の関係
    public function animePilgrimagePost()
    {
        return $this->hasOne(AnimePilgrimagePost::class, 'id', 'anime_pilgrimage_id');
    }

    // いいねをしたAnimePilgrimagePostに対するリレーション 多対多の関係
    public function animePilgrimagePosts()
    {
        return $this->belongsToMany(AnimePilgrimagePost::class, 'anime_pilgrimage_posts_users', 'user_id', 'anime_pilgrimage_post_id');
    }

    // WorkStoryPostに対するリレーション 1対1の関係
    public function workStoryPost()
    {
        return $this->hasOne(WorkStoryPost::class, 'id', 'work_story_post_id');
    }

    // いいねをしたWorkStoryPostに対するリレーション 多対多の関係
    public function workStoryPosts()
    {
        return $this->belongsToMany(WorkStoryPost::class, 'work_story_posts_users', 'user_id', 'work_story_post_id');
    }

    // いいねをしたNotificationに対するリレーション 多対多の関係
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user', 'user_id', 'notification_id');
    }

    // 自己結合
    // 自分がフォローしているユーザーに対するリレーション　多対多の関係
    public function followings()
    {
        return $this->belongsToMany(
            User::class,
            'followers', // 中間テーブル名
            'following_id', // このユーザーがフォローしている（中間テーブルでの自分のカラム）
            'followed_id' // フォローしている相手のカラム
        );
    }

    // 自分をフォローしているユーザーに対するリレーション　多対多の関係
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'followers', // 中間テーブル名
            'followed_id', // このユーザーがフォローされている（中間テーブルでの自分のカラム）
            'following_id' // フォローしている相手のカラム
        );
    }

    // WorkPostCommentに対するリレーション 1対1の関係
    public function workPostComment()
    {
        return $this->hasOne(WorkPostComment::class, 'id', 'work_post_id');
    }

    // いいねをしたWorkPostCommentに対するリレーション 多対多の関係
    public function workPostComments()
    {
        return $this->belongsToMany(WorkPostComment::class, 'user_wp_comment', 'user_id', 'wp_comment_id');
    }

    // WorkStoryPostCommentに対するリレーション 1対1の関係
    public function workStoryPostComment()
    {
        return $this->hasOne(WorkStoryPostComment::class, 'id', 'work_story_post_id');
    }

    // いいねをしたWorkStoryPostCommentに対するリレーション 多対多の関係
    public function workStoryPostComments()
    {
        return $this->belongsToMany(WorkStoryPostComment::class, 'user_wsp_comment', 'user_id', 'wsp_comment_id');
    }

    // MusicPostCommentに対するリレーション 1対1の関係
    public function musicPostComment()
    {
        return $this->hasOne(MusicPostComment::class, 'id', 'music_post_id');
    }

    // いいねをしたMusicPostCommentに対するリレーション 多対多の関係
    public function musicPostComments()
    {
        return $this->belongsToMany(MusicPostComment::class, 'user_mp_comment', 'user_id', 'mp_comment_id');
    }

    // CharacterPostCommentに対するリレーション 1対1の関係
    public function characterPostComment()
    {
        return $this->hasOne(CharacterPostComment::class, 'id', 'character_post_id');
    }

    // いいねをしたCharacterPostCommentに対するリレーション 多対多の関係
    public function characterPostComments()
    {
        return $this->belongsToMany(CharacterPostComment::class, 'user_cp_comment', 'user_id', 'cp_comment_id');
    }

    // AnimePilgrimagePostCommentに対するリレーション 1対1の関係
    public function animePilgrimagePostComment()
    {
        return $this->hasOne(AnimePilgrimagePostComment::class, 'id', 'anime_pilgrimage_post_id');
    }

    // いいねをしたAnimePilgrimagePostCommentに対するリレーション 多対多の関係
    public function animePilgrimagePostComments()
    {
        return $this->belongsToMany(AnimePilgrimagePostComment::class, 'user_app_comment', 'user_id', 'app_comment_id');
    }

    // NotificationCommentに対するリレーション 1対1の関係
    public function notificationComment()
    {
        return $this->hasOne(NotificationComment::class, 'id', 'notification_id');
    }

    // いいねをしたNotificationCommentに対するリレーション 多対多の関係
    public function notificationComments()
    {
        return $this->belongsToMany(NotificationComment::class, 'user_notification_comment', 'user_id', 'notification_comment_id');
    }

    // 気になるをしたWorkに対するリレーション 多対多の関係
    public function works()
    {
        return $this->belongsToMany(Work::class);
    }

    // 気になるをしたWorkStoryに対するリレーション 多対多の関係
    public function workStories()
    {
        return $this->belongsToMany(WorkStory::class);
    }

    // 気になるをしたCharacterに対するリレーション 多対多の関係
    public function characters()
    {
        return $this->belongsToMany(Character::class);
    }

    // 気になるをしたMusicに対するリレーション 多対多の関係
    public function music()
    {
        return $this->belongsToMany(Music::class);
    }

    // 気になるをしたAnimePilgrimageに対するリレーション 多対多の関係
    public function animePilgrimages()
    {
        return $this->belongsToMany(AnimePilgrimage::class);
    }
}
