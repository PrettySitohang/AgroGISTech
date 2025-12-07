<?php
// database/seeders/ArticleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('role', 'penulis')->first();
        $editor = User::where('role', 'editor')->first();

        Article::create([
            'title'        => 'Teknologi Pemupukan Modern pada Kelapa Sawit',
            'slug'         => 'teknologi-pemupukan-modern-pada-kelapa-sawit',
            'content'      => 'Konten artikel contoh tentang pemupukan...',
            'status'       => 'published',
            'author_id'    => $author?->id,
            'editor_id'    => $editor?->id,
            'published_at' => now(),
            'cover_image'  => 'articles/agrogistech-dummy.jpg',
        ]);

        Article::create([
            'title'       => 'Inovasi Irigasi Presisi untuk Perkebunan',
            'slug'        => 'inovasi-irigasi-presisi-untuk-perkebunan',
            'content'     => 'Konten sample tentang irigasi presisi...',
            'status'      => 'published',
            'author_id'   => $author?->id,
            'cover_image' => 'app/public/articles/agrogistech-dummy.jpg',
        ]);

        Article::create([
            'title'       => 'Manajemen Hama Terpadu',
            'slug'        => 'manajemen-hama-terpadu',
            'content'     => 'Draft mengenai pest management...',
            'status'      => 'draft',
            'author_id'   => $author?->id,
            'cover_image' => 'articles/agrogistech-dummy.jpg',
        ]);
    }
}
