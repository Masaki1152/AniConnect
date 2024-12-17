<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 参照させたいworksを指定
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'age',
        'sex',
        'image',
        'password',
        'introduction',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // 投稿の種類とユーザーidで投稿を取得する処理
    public function fetchPosts($user_id, $type, $search)
    {
        // 必要な種類の投稿を取得
        // 合わせて投稿者情報をリレーションで取得
        switch ($type) {
            case 'work':
                $posts = WorkReview::where('user_id', $user_id)
                    ->with(['user', 'work'])
                    ->where(function ($query) use ($search) {
                        // キーワード検索がなされた場合
                        if ($search != '') {
                            // 検索語のスペースを半角に統一
                            $search_split = mb_convert_kana($search, 's');
                            // 半角スペースで単語ごとに分割して配列にする
                            $search_array = preg_split('/[\s]+/', $search_split);
                            foreach ($search_array as $search_word) {
                                $query->where(function ($query) use ($search_word) {
                                    // 自身のカラムでの検索
                                    $query->where('post_title', 'LIKE', "%{$search_word}%")
                                        ->orWhere('body', 'LIKE', "%{$search_word}%");

                                    // リレーション先のWorksテーブルのカラムでの検索
                                    $query->orWhereHas('work', function ($workQuery) use ($search_word) {
                                        $workQuery->where('name', 'like', '%' . $search_word . '%')
                                            ->orWhere('term', 'like', '%' . $search_word . '%');
                                    });
                                });
                            }
                        }
                    })->orderBy('created_at', 'DESC')->paginate(10);
                $this->addPostType($posts, $type);
                break;
            case 'workStory':
                $posts = WorkStoryPost::where('user_id', $user_id)
                    ->with(['user', 'work', 'workStory'])
                    ->where(function ($query) use ($search) {
                        // キーワード検索がなされた場合
                        if ($search != '') {
                            // 検索語のスペースを半角に統一
                            $search_split = mb_convert_kana($search, 's');
                            // 半角スペースで単語ごとに分割して配列にする
                            $search_array = preg_split('/[\s]+/', $search_split);
                            foreach ($search_array as $search_word) {
                                $query->where(function ($query) use ($search_word) {
                                    // 自身のカラムでの検索
                                    $query->where('post_title', 'LIKE', "%{$search_word}%")
                                        ->orWhere('body', 'LIKE', "%{$search_word}%");

                                    // リレーション先のWorksテーブルのカラムでの検索
                                    $query->orWhereHas('work', function ($workQuery) use ($search_word) {
                                        $workQuery->where('name', 'like', '%' . $search_word . '%')
                                            ->orWhere('term', 'like', '%' . $search_word . '%');
                                    });

                                    // リレーション先のWork_storiesテーブルのカラムでの検索
                                    $query->orWhereHas('workStory', function ($workStoryQuery) use ($search_word) {
                                        $workStoryQuery->where('sub_title', 'like', '%' . $search_word . '%')
                                            ->orWhere('episode', 'like', '%' . $search_word . '%');
                                    });
                                });
                            }
                        }
                    })->orderBy('created_at', 'DESC')->paginate(10);
                $this->addPostType($posts, $type);
                break;
            case 'character':
                $posts = CharacterPost::where('user_id', $user_id)
                    ->with(['user', 'character', 'character.works', 'character.voiceArtist'])
                    ->where(function ($query) use ($search) {
                        // キーワード検索がなされた場合
                        if ($search != '') {
                            // 検索語のスペースを半角に統一
                            $search_split = mb_convert_kana($search, 's');
                            // 半角スペースで単語ごとに分割して配列にする
                            $search_array = preg_split('/[\s]+/', $search_split);
                            foreach ($search_array as $search_word) {
                                $query->where(function ($query) use ($search_word) {
                                    // 自身のカラムでの検索
                                    $query->where('post_title', 'LIKE', "%{$search_word}%")
                                        ->orWhere('body', 'LIKE', "%{$search_word}%");

                                    // リレーション先のCharactersテーブルのカラムでの検索
                                    $query->orWhereHas('character', function ($characterQuery) use ($search_word) {
                                        $characterQuery->where('name', 'like', '%' . $search_word . '%');

                                        // リレーション先のWorksテーブルのカラムでの検索
                                        $characterQuery->orWhereHas('works', function ($workQuery) use ($search_word) {
                                            $workQuery->where('name', 'LIKE', "%{$search_word}%")
                                                ->orWhere('term', 'like', '%' . $search_word . '%');
                                        });

                                        // リレーション先のvoice_artistsテーブルのカラムでの検索
                                        $characterQuery->orWhereHas('voiceArtist', function ($voiceArtistQuery) use ($search_word) {
                                            $voiceArtistQuery->where('name', 'LIKE', "%{$search_word}%");
                                        });
                                    });
                                });
                            }
                        }
                    })->orderBy('created_at', 'DESC')->paginate(10);
                $this->addPostType($posts, $type);
                break;
            case 'music':
                $posts = MusicPost::where('user_id', $user_id)
                    ->with(['user', 'music', 'music.work', 'music.singer'])
                    ->where(function ($query) use ($search) {
                        // キーワード検索がなされた場合
                        if ($search != '') {
                            // 検索語のスペースを半角に統一
                            $search_split = mb_convert_kana($search, 's');
                            // 半角スペースで単語ごとに分割して配列にする
                            $search_array = preg_split('/[\s]+/', $search_split);
                            foreach ($search_array as $search_word) {
                                $query->where(function ($query) use ($search_word) {
                                    // 自身のカラムでの検索
                                    $query->where('post_title', 'LIKE', "%{$search_word}%")
                                        ->orWhere('body', 'LIKE', "%{$search_word}%");

                                    // リレーション先のMusicsテーブルのカラムでの検索
                                    $query->orWhereHas('music', function ($musicQuery) use ($search_word) {
                                        $musicQuery->where('name', 'like', '%' . $search_word . '%');

                                        // リレーション先のWorksテーブルのカラムでの検索
                                        $musicQuery->orWhereHas('work', function ($workQuery) use ($search_word) {
                                            $workQuery->where('name', 'LIKE', "%{$search_word}%")
                                                ->orWhere('term', 'like', '%' . $search_word . '%');
                                        });

                                        // リレーション先のsingersテーブルのカラムでの検索
                                        $musicQuery->orWhereHas('singer', function ($singerQuery) use ($search_word) {
                                            $singerQuery->where('name', 'LIKE', "%{$search_word}%");
                                        });
                                    });
                                });
                            }
                        }
                    })->orderBy('created_at', 'DESC')->paginate(10);
                $this->addPostType($posts, $type);
                break;
            case 'animePilgrimage':
                $posts = AnimePilgrimagePost::where('user_id', $user_id)
                    ->with(['user', 'animePilgrimage'])
                    ->where(function ($query) use ($search) {
                        // キーワード検索がなされた場合
                        if ($search != '') {
                            // 検索語のスペースを半角に統一
                            $search_split = mb_convert_kana($search, 's');
                            // 半角スペースで単語ごとに分割して配列にする
                            $search_array = preg_split('/[\s]+/', $search_split);
                            foreach ($search_array as $search_word) {
                                $query->where(function ($query) use ($search_word) {
                                    // 自身のカラムでの検索
                                    $query->where('post_title', 'LIKE', "%{$search_word}%")
                                        ->orWhere('body', 'LIKE', "%{$search_word}%")
                                        ->orWhere('scene', 'LIKE', "%{$search_word}%");

                                    // リレーション先のanime_pilgrimagesテーブルのカラムでの検索
                                    $query->orWhereHas('animePilgrimage', function ($animePilgrimageQuery) use ($search_word) {
                                        $animePilgrimageQuery->where('name', 'like', '%' . $search_word . '%')
                                            ->orWhere('place', 'LIKE', "%{$search_word}%");

                                        // リレーション先のWorksテーブルのカラムでの検索
                                        $animePilgrimageQuery->orWhereHas('work', function ($workQuery) use ($search_word) {
                                            $workQuery->where('name', 'LIKE', "%{$search_word}%")
                                                ->orWhere('term', 'like', '%' . $search_word . '%');
                                        });;
                                    });
                                });
                            }
                        }
                    })->orderBy('created_at', 'DESC')->paginate(10);
                $this->addPostType($posts, $type);
                break;
        }
        return $posts;
    }

    // 取得した投稿に投稿の種類を付与する処理
    public function addPostType($posts, $type)
    {
        // 投稿の種類
        $postTypes = [
            'work' => '作品感想',
            'workStory' => 'あらすじ感想',
            'character' => '登場人物感想',
            'music' => '音楽感想',
            'animePilgrimage' => '聖地感想',
        ];

        // 各投稿に postType を追加
        $posts->getCollection()->transform(function ($post) use ($postTypes, $type) {
            // 任意の種類を設定
            $post->postType = $postTypes[$type];
            return $post;
        });
    }

    // WorkReviewに対するリレーション 1対1の関係
    public function workreview()
    {
        return $this->belongsTo(WorkReview::class, 'id', 'user_id');
    }

    // いいねをしたWorkReviewに対するリレーション 多対多の関係
    public function workreviews()
    {
        return $this->belongsToMany(WorkReview::class);
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
}
