<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class Work_StorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第1話',
            'sub_title' => '夢の中で逢った、ような……',
            'body' => '大好きな家族がいて、親友がいて、時には笑い、時には泣く、
そんな平和な日々を送る中学二年生、鹿目まどか。
ある晩、まどかはとても不思議な夢を見る。

その日も訪れるはずだった、変わらぬ日常――。
しかし、訪れたのは非日常――。

まどかの通うクラスにやってきた、一人の転校生・暁美ほむら。
まどかが夢で見た少女と瓜二つの容姿をした少女。

偶然の一致に戸惑うまどかに、ほむらは意味深な言葉を投げかけるのだった・・・。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/01.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第2話',
            'sub_title' => 'それはとっても嬉しいなって',
            'body' => 'ほむらに襲われたキュゥべえを助ける途中、
迷い込んだのは摩訶不思議な空間。
絶対絶命のピンチに陥ったまどかとさやかを救ったのは一人の魔法少女。
巴マミ。

その後二人が誘われたのは、魔法少女の部屋。

語られしは、キュゥべえに選ばれし者に与えられる資格。
魔法少女という存在、そして魔女という存在。

どんな望みをも叶えるチャンスと、その先に待つ過酷な使命。
悩む二人に、マミは「自分の魔女退治に付き合わないか」と提案をするのだった。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/02.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第3話',
            'sub_title' => 'もう何も恐くない',
            'body' => 'マミの魔女退治体験コースにも慣れつつある、まどかとさやか。
ただし、肝心な願い事は未決のまま。

悩むふたりに明かされた、マミの過去。

「願いの内容が、自分のための事柄でなくてはならいのか？」と問うさやかに、
マミは、厳しい口調で「他人の願いを叶えるのなら、なおのこと自分の望みをはっきりさせておかないと」と嗜めるのだった。

翌日の放課後、
恭介の見舞いに行ったとさやかと付き添いのまどかは、
その帰り道、偶然にも病院の駐輪場で孵化しかけたグリーフシードを発見する。
放置すれば、大惨事になりかねない事態に、さやかはキュゥべえと共に見張りを、まどかはマミを助けに呼びに走るのだった。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/03.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第4話',
            'sub_title' => '奇跡も、魔法も、あるんだよ',
            'body' => 'マミと魔女との壮絶な戦いの翌日、訪れたのはいつもと変わらない平和な日常。

魔法少女の敗北の結果を目の当たりにしたまどかとさやかは、魔法の世界に関わったことの重さを実感し、魔法少女になることを諦める。

その日の夕方、誰もいなくなったマミの部屋を訪れたまどかは、帰り道、マンションのエントランスでほむらと出会う。

夕日の中、並んで歩く二人。

魔法少女としての死ぬことの現実を語るほむらに、まどかは切ないほど優しい言葉をかけるのだった。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/04.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第5話',
            'sub_title' => '後悔なんて、あるわけない',
            'body' => '魔法少女として、魔女の手からまどかと仁美を救ったさやか。
キュゥべえとの契約により願いを叶えた今、その心は清々しく、魔法少女となったことに後悔はない様子。
反対にまどかはさやかよりも先に魔法少女になる決意をするも、諦めてしまった自分に悩む。

恭介の両親、主治医、病院スタッフが集まり、病院の屋上で開かれたのは恭介の手の快復祝い。
そこで、父の手から、かつて自分が愛用していたバイオリンを渡される。
始めは躊躇するも、意を決してバイオリンを披露する恭介。
まったく衰えていない天才の才能に、聴き惚れる一同。
その光景を見たさやかは、至福の喜びをかみ締める。

一方展望台には、そんな病院屋上でのさやかの動向をうかがう杏子の姿があった。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/05.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第6話',
            'sub_title' => 'こんなの絶対おかしいよ',
            'body' => 'さやかと杏子の戦闘現場に、突如現れたほむら。
戦闘の仲裁に入った彼女はさやかを一撃で気絶させ、
それを見た杏子はほむらを警戒し、その場を離脱したことにより、
戦闘は終息する。

翌日、杏子の乱入により取り逃してしまった使い魔の痕跡を探すさやかとまどか。
戦闘の痕跡が残るその現場で、杏子との平和的な解決を提案するまどかと、
命を賭けた魔法少女同士の闘いに覚悟を決めたさやか。
二人の意見は擦れ違ってしまう

そんな二人のやりとりの一方、
ゲームセンターでは、とある目的のため、ほむらは杏子と接触するのだった。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/06.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第7話',
            'sub_title' => '本当の気持ちと向き合えますか？',
            'body' => '魔法少女となった自分の体の真実を知ったさやか。
戦いの運命を受け入れてまで叶えた願いと、その代償の大きさの間で揺れてしまう。

そんな自宅でふさぎこむさやかの元に現れたのは、敵対していたはずの佐倉杏子。
彼女はさやかを外へと連れ出し、とある廃墟の教会へと誘う。

そこで杏子の口から語られたのは、自身が魔法少女となった理由。
果たして彼女の真意とは――',
            'official_link' => 'https://www.madoka-magica.com/tv/story/07.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第8話',
            'sub_title' => 'あたしって、ほんとバカ',
            'body' => '自らの負傷も意に介さず、ただ目の前の魔女を切り刻むさやか。
治癒魔法のおかげで最終的には無傷で魔女に勝利するも、もはや憔悴しきった様子。

その帰り道、降り出した雨の雨宿りがてらの休憩中、憔悴しきったさやかの様子を見かねたまどかは、さやかの戦い方について、口を出してしまう。
きれいごとばかりに感じるまどかのその言葉に、さやかはついに感情を爆発させ、その場を立ち去ってしまう。

涙に暮れながら、それでも追いかけられないまどか――
雨の中をはしりながら、自己嫌悪に悔し泣きをするさやか――

彼女のソウルジェムは、黒く黒く濁っていくのであった。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/08.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第9話',
            'sub_title' => 'そんなの、あたしが許さない',
            'body' => '漆黒のグリーフシードと化したさやかのソウルジェム。
そのグリーフシードは孵化し、新たな魔女が現れる。
さやかの身体を抱え、迫りくる魔女の攻撃に防戦一方の杏子。
魔女の結界に割って入ってきたほむらは、杏子を先導し結界から脱出する。

一方まどかは、さやかの捜索途中、重い足取りで歩く杏子とほむらの姿を見つける。変わり果てた姿となったさやかの前で泣き崩れるまどかに、
ほむらは冷淡な口調でソウルジェムの最後の秘密を語り、その場を立ち去る。

その日の深夜、杏子の元に現れたのはキュゥべえ。
キュゥべえとさやかの身体を元に戻すことについて話す杏子は、その会話の中で一縷の可能性を見出す。
翌朝、杏子は仁美と登校途中のまどかをテレパシーで呼び出し、驚きの提案をするのだった。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/09.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第10話',
            'sub_title' => 'もう誰にも頼らない',
            'body' => 'それはとある少女の転校風景。
必要以上に緊張し、萎縮する気弱そうな少女は、
クラスの全生徒の視線を一身に浴びながら、慣れない自己紹介をする。

休み時間、押しかけて興味津々に質問をしてくる女子たちに、
気圧されておどおどしている彼女を、その場から連れだしてくれたのは、クラスの保健委員を名乗る少女。
優しい笑顔を向ける彼女は、自分を名前負けだと感じる少女に対し、カッコいい名前だと言う。

長らくの入院生活により、学力も体力も他の生徒に劣る彼女は、
劣等感に肩を落として帰宅する途中、ふとしたことで魔女の結界に迷いこんでしまう。
彼女の絶対絶命のピンチに現れたのは、二人の魔法少女だった。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/10.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第11話',
            'sub_title' => '最後に残った道しるべ',
            'body' => '雨の中、しめやかに行われたさやかの葬儀。
うつろな目をして家に戻ったまどかは、玄関で出迎えた詢子への挨拶もそこらに自分の部屋に入ってしまう。
一人悲しみに暮れるまどかの元に現れたのはキュゥべえ。
さやか達の死について冷たい口調で語るその姿に、さすがのまどかも怒りを感じる。
そんなまどかの態度が理解できないキュゥべえは、
自分たちと人類がこれまで共に歩んできた歴史を語るのだった。',
            'official_link' => 'https://www.madoka-magica.com/tv/story/11.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
        DB::table('work_stories')->insert([
            'work_id' => 1,
            'episode' => '第12話',
            'sub_title' => 'わたしの、最高の友達',
            'body' => '一人ワルプルギスの夜に挑み、深手を負ったほむら。
何度挑戦しても勝てないくやしさ、自分の行為がかえってまどかを苦しめることになっていたことへの絶望で、自らのソウルジェムを黒く染め上げていく。そんなほむらの前に現れた少女、鹿目まどか。まどかは、決意のまなざしでワルプルギスの夜を見据え、ほむらに言い放つ。

「叶えたい願い事をみつけたの」

魔法少女となる者の運命を全て知った彼女は、
果たして何を願い、どんな決断を下すのか？',
            'official_link' => 'https://www.madoka-magica.com/tv/story/12.html',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
