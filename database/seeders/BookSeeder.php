<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // ==========================================
        // 1. BUAT DATA KATEGORI DULU
        // ==========================================
        $categoryNames = [
            'Fiction',
            'Self-Improvement',
            'Science Fiction',
            'Biography',
            'Technology',
            'Romance',
            'History'
        ];

        $categories = [];
        foreach ($categoryNames as $name) {
            $categories[] = [
                'name'       => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insert kategori ke database
        DB::table('categories')->insert($categories);

        // Ambil semua ID kategori yang baru saja dibuat untuk diacak nanti
        $categoryIds = DB::table('categories')->pluck('id')->toArray();


        // ==========================================
        // 2. DATA BUKU
        // ==========================================
        $titles = [
            'Filosofi Teras',
            'Laskar Pelangi',
            'Atomic Habits',
            'Bumi',
            'Mantappu Jiwa',
            'Sapiens',
            'Negeri 5 Menara',
            'The Alchemist',
            'Rich Dad Poor Dad',
            'Sebuah Seni untuk Bersikap Bodo Amat',
            'Pulang',
            'Dilan 1990',
            'Perahu Kertas',
            'Thinking Fast and Slow',
            'Ketika Mas Gagah Pergi',
            'Siddhartha',
            'Start With Why',
            'Ikigai',
            'Habis Gelap Terbitlah Terang',
            'The 7 Habits',
            'Laut Bercerita',
            'Psycho-Cybernetics',
            'Hujan',
            'Deep Work',
            'Cantik Itu Luka',
            'Man\'s Search for Meaning',
            'Bongkar',
            'The Power of Now',
            'Ronggeng Dukuh Paruk',
            'Zero to One',
            '1984',
            'To Kill a Mockingbird',
            'The Great Gatsby',
            'Pride and Prejudice',
            'The Catcher in the Rye',
            'The Hobbit',
            'Fahrenheit 451',
            'Jane Eyre',
            'Animal Farm',
            'Brave New World',
            'Harry Potter and the Sorcerer\'s Stone',
            'The Lord of the Rings',
            'The Chronicles of Narnia',
            'The Hunger Games',
            'Divergent',
            'The Maze Runner',
            'Twilight',
            'The Fault in Our Stars',
            'The Kite Runner',
            'The Book Thief',
            'Educated',
            'Becoming',
            'The Glass Castle',
            'Into the Wild',
            'A Brief History of Time',
            'Freakonomics',
            'Outliers',
            'The Tipping Point',
            'Quiet',
            'Guns, Germs, and Steel',
            'Blink',
            'The Power of Habit',
            'Mindset',
            'Grit',
            'Essentialism',
            'You Are a Badass',
            'Big Magic',
            'Daring Greatly',
            'The Four Agreements',
            'Sapiens: A Graphic History',
            'Clean Code',
            'The Pragmatic Programmer',
            'Design Patterns',
            'Refactoring',
            'Code Complete',
            'Head First Design Patterns',
            'Clean Architecture',
            'Introduction to Algorithms',
            'Cracking the Coding Interview',
            'The Mythical Man-Month',
            'Don\'t Make Me Think',
            'The Design of Everyday Things',
            'Sprint',
            'Hooked',
            'Inspired',
            'Zero to Sold',
            'The Lean Startup',
            'Good to Great',
            'Built to Last',
            'The Innovator\'s Dilemma',
            'Dune',
            'Foundation',
            'Neuromancer',
            'Snow Crash',
            'The Martian',
            'Ender\'s Game',
            'Hitchhiker\'s Guide to the Galaxy',
            'I, Robot',
            'Do Androids Dream of Electric Sheep?',
            'The Left Hand of Darkness'
        ];

        $authors = [
            'Tere Liye',
            'Andrea Hirata',
            'James Clear',
            'Mark Manson',
            'Henry Manampiring',
            'J.K. Rowling',
            'George Orwell',
            'Robert T. Kiyosaki',
            'Yuval Noah Harari',
            'Pidi Baiq',
            'Dee Lestari',
            'Ahmad Fuadi',
            'Paulo Coelho',
            'Daniel Kahneman',
            'Stephen R. Covey'
        ];

        $books = [];

        foreach ($titles as $index => $title) {
            $imageNumber = floor($index / 10) + 1;
            $stock = rand(0, 15);
            $status = $stock > 0 ? 'avaiable' : 'not avaiable';

            $books[] = [
                // TAMBAHAN: Assign category_id secara acak dari data kategori di atas
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'title'       => $title,
                'image'       => 'assets/r/' . $imageNumber . '.jpg',
                'description' => 'Ini adalah deskripsi dummy untuk buku ' . $title . ' yang sangat menarik untuk dibaca.',
                'author'      => $authors[array_rand($authors)],
                'year'        => rand(1990, 2023),
                'stock'       => $stock,
                'status'      => $status,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        // Insert menggunakan chunk
        foreach (array_chunk($books, 25) as $chunk) {
            DB::table('books')->insert($chunk);
        }
    }
}
