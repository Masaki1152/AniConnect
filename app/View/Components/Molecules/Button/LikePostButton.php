<?php

namespace App\View\Components\Molecules\Button;

use Illuminate\View\Component;

class LikePostButton extends Component
{
    public $type;
    public $post;
    public $dataTypeAttributes;
    public $likeCountUrl;

    public function __construct($type, $post)
    {
        $this->post = $post;
        $typeId = $type . '_id';
        $typeColumnId = $type === 'pilgrimage' ? 'anime_' . $type . '_id' : $type . '_id';
        $typePostId = $type . '_post_id';

        if ($type === 'work_story') {
            $this->dataTypeAttributes = [
                'work-id' => $post->work_id,
                'work_story-id' => $post->sub_title_id,
                'post-id' => $post->id,
            ];
            $this->likeCountUrl = route($type . '_post_like.index', [
                'work_id' => $post->work_id,
                $typeId => $post->sub_title_id,
                $typePostId => $post->id
            ]);
        } else {
            $this->dataTypeAttributes = [
                $type . '-id' => $post->$typeColumnId,
                'post-id' => $post->id,
            ];
            $this->likeCountUrl = route($type . '_post_like.index', [
                $typeId => $post->$typeColumnId,
                $typePostId => $post->id
            ]);
        }
    }

    public function render()
    {
        return view('components.molecules.button.like-post-button');
    }
}
