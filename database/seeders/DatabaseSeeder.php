<?php

namespace Database\Seeders;

use Abaydullah\GooglePlayScraper\Scraper;
use App\Models\App;
use App\Models\Category;
use App\Models\Developer;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $category = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
       $category = Category::create([
           'name' => 'Android Wear',
           'google_name' => 'ANDROID_WEAR',
           'google_url' => 'https://play.google.com/store/apps/category/ANDROID_WEAR',
           'slug' => Str::slug('Android Wear'),
           'type' => 'app',
       ]);
//       $category = Category::create([
//           'name' => 'Application',
//           'google_name' => 'APPLICATION',
//           'google_url' => 'https://play.google.com/store/apps/category/APPLICATION',
//           'slug' => Str::slug('Application'),
//           'type' => 'app',
//       ]);
       $category = Category::create([
           'name' => 'Art & Design',
           'google_name' => 'ART_AND_DESIGN',
           'google_url' => 'https://play.google.com/store/apps/category/ART_AND_DESIGN',
           'slug' => Str::slug('Art & Design'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Auto & Vehicles',
           'google_name' => 'AUTO_AND_VEHICLES',
           'google_url' => 'https://play.google.com/store/apps/category/AUTO_AND_VEHICLES',
           'slug' => Str::slug('Auto & Vehicles'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Beauty',
           'google_name' => 'BEAUTY',
           'google_url' => 'https://play.google.com/store/apps/category/BEAUTY',
           'slug' => Str::slug('Beauty'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Books & Reference',
           'google_name' => 'BOOKS_AND_REFERENCE',
           'google_url' => 'https://play.google.com/store/apps/category/BOOKS_AND_REFERENCE',
           'slug' => Str::slug('Books & Reference'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Business',
           'google_name' => 'BUSINESS',
           'google_url' => 'https://play.google.com/store/apps/category/BUSINESS',
           'slug' => Str::slug('Business'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Comics',
           'google_name' => 'COMICS',
           'google_url' => 'https://play.google.com/store/apps/category/COMICS',
           'slug' => Str::slug('Comics'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Communication',
           'google_name' => 'COMMUNICATION',
           'google_url' => 'https://play.google.com/store/apps/category/COMMUNICATION',
           'slug' => Str::slug('Communication'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Dating',
           'google_name' => 'DATING',
           'google_url' => 'https://play.google.com/store/apps/category/DATING',
           'slug' => Str::slug('Dating'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Education',
           'google_name' => 'EDUCATION',
           'google_url' => 'https://play.google.com/store/apps/category/EDUCATION',
           'slug' => Str::slug('Education'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Entertainment',
           'google_name' => 'ENTERTAINMENT',
           'google_url' => 'https://play.google.com/store/apps/category/ENTERTAINMENT',
           'slug' => Str::slug('Entertainment'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Events',
           'google_name' => 'EVENTS',
           'google_url' => 'https://play.google.com/store/apps/category/EVENTS',
           'slug' => Str::slug('Events'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Finance',
           'google_name' => 'FINANCE',
           'google_url' => 'https://play.google.com/store/apps/category/FINANCE',
           'slug' => Str::slug('Finance'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Food & Drink',
           'google_name' => 'FOOD_AND_DRINK',
           'google_url' => 'https://play.google.com/store/apps/category/FOOD_AND_DRINK',
           'slug' => Str::slug('Food & Drink'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Health & Fitness',
           'google_name' => 'HEALTH_AND_FITNESS',
           'google_url' => 'https://play.google.com/store/apps/category/HEALTH_AND_FITNESS',
           'slug' => Str::slug('Health & Fitness'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'House & Home',
           'google_name' => 'HOUSE_AND_HOME',
           'google_url' => 'https://play.google.com/store/apps/category/HOUSE_AND_HOME',
           'slug' => Str::slug('House & Home'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Lifestyle',
           'google_name' => 'LIFESTYLE',
           'google_url' => 'https://play.google.com/store/apps/category/LIFESTYLE',
           'slug' => Str::slug('Lifestyle'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Maps & Navigation',
           'google_name' => 'MAPS_AND_NAVIGATION',
           'google_url' => 'https://play.google.com/store/apps/category/MAPS_AND_NAVIGATION',
           'slug' => Str::slug('Maps & Navigation'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Medical',
           'google_name' => 'MEDICAL',
           'google_url' => 'https://play.google.com/store/apps/category/MEDICAL',
           'slug' => Str::slug('Medical'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Music & Audio',
           'google_name' => 'MUSIC_AND_AUDIO',
           'google_url' => 'https://play.google.com/store/apps/category/MUSIC_AND_AUDIO',
           'slug' => Str::slug('Music & Audio'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'News & Magazines',
           'google_name' => 'NEWS_AND_MAGAZINES',
           'google_url' => 'https://play.google.com/store/apps/category/NEWS_AND_MAGAZINES',
           'slug' => Str::slug('News & Magazines'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Parenting',
           'google_name' => 'PARENTING',
           'google_url' => 'https://play.google.com/store/apps/category/PARENTING',
           'slug' => Str::slug('Parenting'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Personalization',
           'google_name' => 'PERSONALIZATION',
           'google_url' => 'https://play.google.com/store/apps/category/PERSONALIZATION',
           'slug' => Str::slug('Personalization'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Photography',
           'google_name' => 'PHOTOGRAPHY',
           'google_url' => 'https://play.google.com/store/apps/category/PHOTOGRAPHY',
           'slug' => Str::slug('Photography'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Productivity',
           'google_name' => 'PRODUCTIVITY',
           'google_url' => 'https://play.google.com/store/apps/category/PRODUCTIVITY',
           'slug' => Str::slug('Productivity'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Shopping',
           'google_name' => 'SHOPPING',
           'google_url' => 'https://play.google.com/store/apps/category/SHOPPING',
           'slug' => Str::slug('Shopping'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Social',
           'google_name' => 'SOCIAL',
           'google_url' => 'https://play.google.com/store/apps/category/SOCIAL',
           'slug' => Str::slug('Social'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Sports',
           'google_name' => 'SPORTS',
           'google_url' => 'https://play.google.com/store/apps/category/SPORTS',
           'slug' => Str::slug('Sports'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Tools',
           'google_name' => 'TOOLS',
           'google_url' => 'https://play.google.com/store/apps/category/TOOLS',
           'slug' => Str::slug('Tools'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Travel & Local',
           'google_name' => 'TRAVEL_AND_LOCAL',
           'google_url' => 'https://play.google.com/store/apps/category/TRAVEL_AND_LOCAL',
           'slug' => Str::slug('Travel & Local'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Video Players & Editors',
           'google_name' => 'VIDEO_PLAYERS',
           'google_url' => 'https://play.google.com/store/apps/category/VIDEO_PLAYERS',
           'slug' => Str::slug('Video Players & Editors'),
           'type' => 'app',
       ]);
       $category = Category::create([
           'name' => 'Weather',
           'google_name' => 'WEATHER',
           'google_url' => 'https://play.google.com/store/apps/category/WEATHER',
           'slug' => Str::slug('Weather'),
           'type' => 'app',
       ]);
//       $category = Category::create([
//           'name' => 'Game',
//           'google_name' => 'GAME',
//           'google_url' => 'https://play.google.com/store/apps/category/GAME',
//           'slug' => Str::slug('Game'),
//           'type' => 'game',
//       ]);
       $category = Category::create([
           'name' => 'Family',
           'google_name' => 'FAMILY',
           'google_url' => 'https://play.google.com/store/apps/category/FAMILY',
           'slug' => Str::slug('Family'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Action',
           'google_name' => 'GAME_ACTION',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_ACTION',
           'slug' => Str::slug('Action'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Adventure',
           'google_name' => 'GAME_ADVENTURE',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_ADVENTURE',
           'slug' => Str::slug('Adventure'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Arcade',
           'google_name' => 'GAME_ARCADE',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_ARCADE',
           'slug' => Str::slug('Arcade'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Board',
           'google_name' => 'GAME_BOARD',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_BOARD',
           'slug' => Str::slug('Board'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Card',
           'google_name' => 'GAME_CARD',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_CARD',
           'slug' => Str::slug('Card'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Casino',
           'google_name' => 'GAME_CASINO',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_CASINO',
           'slug' => Str::slug('Casino'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Casual',
           'google_name' => 'GAME_CASUAL',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_CASUAL',
           'slug' => Str::slug('Casual'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Educational',
           'google_name' => 'GAME_EDUCATIONAL',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_EDUCATIONAL',
           'slug' => Str::slug('Educational'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Music',
           'google_name' => 'GAME_MUSIC',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_MUSIC',
           'slug' => Str::slug('Music'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Puzzle',
           'google_name' => 'GAME_PUZZLE',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_PUZZLE',
           'slug' => Str::slug('Puzzle'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Racing',
           'google_name' => 'GAME_RACING',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_RACING',
           'slug' => Str::slug('Racing'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Role Playing',
           'google_name' => 'GAME_ROLE_PLAYING',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_ROLE_PLAYING',
           'slug' => Str::slug('Role Playing'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Simulation',
           'google_name' => 'GAME_SIMULATION',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_SIMULATION',
           'slug' => Str::slug('Simulation'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Sports',
           'google_name' => 'GAME_SPORTS',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_SPORTS',
           'slug' => Str::slug('Sports Game'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Strategy',
           'google_name' => 'GAME_STRATEGY',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_STRATEGY',
           'slug' => Str::slug('Strategy'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Trivia',
           'google_name' => 'GAME_TRIVIA',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_TRIVIA',
           'slug' => Str::slug('Trivia'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Word',
           'google_name' => 'GAME_WORD',
           'google_url' => 'https://play.google.com/store/apps/category/GAME_WORD',
           'slug' => Str::slug('Word'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Family Action & Adventure',
           'google_name' => 'FAMILY_ACTION',
           'google_url' => 'https://play.google.com/store/apps/category/FAMILY_ACTION',
           'slug' => Str::slug('Family Action & Adventure'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Family Brain Games',
           'google_name' => 'FAMILY_BRAINGAMES',
           'google_url' => 'https://play.google.com/store/apps/category/FAMILY_BRAINGAMES',
           'slug' => Str::slug('Family Brain Games'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Family Creativity',
           'google_name' => 'FAMILY_CREATE',
           'google_url' => 'https://play.google.com/store/apps/category/FAMILY_CREATE',
           'slug' => Str::slug('Family Creativity'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Family Education',
           'google_name' => 'FAMILY_EDUCATION',
           'google_url' => 'https://play.google.com/store/apps/category/FAMILY_EDUCATION',
           'slug' => Str::slug('Family Education'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Family Music & Video',
           'google_name' => 'FAMILY_MUSICVIDEO',
           'google_url' => 'https://play.google.com/store/apps/category/FAMILY_MUSICVIDEO',
           'slug' => Str::slug('Family Music & Video'),
           'type' => 'game',
       ]);
       $category = Category::create([
           'name' => 'Family Pretend Play',
           'google_name' => 'FAMILY_PRETEND',
           'google_url' => 'https://play.google.com/store/apps/category/FAMILY_PRETEND',
           'slug' => Str::slug('Family Pretend Play'),
           'type' => 'game',
       ]);


//       $categories = Category::all();
//       foreach ($categories as $category) {
//           $scraper = new Scraper();
//           $apps = $scraper->getCategoryApps($category->google_name);
//           if (count($apps) > 0) {
//
//          foreach ($apps as $app) {
//
//              $check = App::where('app_id', $app['app_id'])->first();
//
//              if (!$check ) {
//                  $checkSlug = App::where('slug', Str::slug($app['title']))->first();
//                  if ($checkSlug ) {
//                     $slug = Str::slug($app['title']).$checkSlug->id;
//                  }else{
//                      $slug = Str::slug($app['title']);
//                  }
//              App::firstOrCreate([
//                  'category_id' => $category->id,
//                  'title' => $app['title'],
//                  'slug' => $slug,
//                  'app_id' => $app['app_id'],
//                  'url' => $app['app_url'],
//                  'icon_url' => $app['icon'],
//                  'rating' => $app['rating'],
//                  'type' => $app['type'],
//              ]);
//              }
//          }
//           }
//
//       }
    }
}
