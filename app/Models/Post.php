<?php

    namespace App\Models;

use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;


    class Post
    {
        public $title;
        public $slug;
        public $excerpt;
        public $image;
        public $video;
        public $date;
        public $body;
        public $category;
        public $author;

        public function __construct($title, $slug, $excerpt, $image, $video, $date, $body, $category, $author)
        {
            $this->title = $title;
            $this->slug = $slug;
            $this->excerpt = $excerpt;
            $this->image = $image;
            $this->video = $video;
            $this->date = $date;
            $this->body = $body;
            $this->category = $category;
            $this->author = $author;
        }

        public static function all() {
            return collect(File::files(resource_path("/posts")))
                ->map(function ($file) {
                    return YamlFrontMatter::parse($file->getContents());
                })
                ->map(function ($document) {
                    return new Post(
                        $document->title,
                        $document->slug,
                        $document->excerpt,
                        $document->image,
                        $document->video,
                        $document->date,
                        $document->body(),
                        $document->category,
                        $document->author
                    );
                })
                ->sortByDesc('date');
        }

        public static function find($slug) {
            return self::all()->firstWhere('slug', $slug);
        }
    }
