<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //Laptops
    //
    // for($i=1; $i < 5; $i)
    // {
    //   Product::create([
    //     'name' => 'MacBook Pro',
    //     'slug' => 'macbook-pro',
    //     'details' => '15 inch, 1TB SSD, 32GB RAM',
    //     'price' => 249999,
    //     'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s
    //     standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
    //     It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
    //     It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
    //     and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
    //   ]);
    // }

    for($i=1; $i < 30; $i++)
    {
      Product::create([
        'name' => 'Laptop ' . $i,
        'slug' => 'laptop-' . $i,
        'details' => [13,14,15][array_rand([13,14,15])] .' inch, '. [1,2,3][array_rand([1,2,3])] .'TB SSD, 32GB RAM',
        'price' => rand(159999, 359999),
        'featured'=> (int)round(mt_rand() / mt_getrandmax()),
        'image' => 'products/dummy1/laptop-'.$i.'.jpg',
        'images' => '',
        'description' => 'Lorem-'.$i.' is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s
        standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
        It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
        and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
      ])->categories()->attach(1);
    }

    $product = Product::find(1);
    $product->categories()->attach(2);


    //Desktops

    for($i=1; $i < 30; $i++)
    {
      Product::create([
        'name' => 'Desktop ' . $i,
        'slug' => 'desktop-' . $i,
        'details' => [24,25,27][array_rand([24,25,27])] .' inch, '. [1,2,3][array_rand([1,2,3])] .'TB SSD, 32GB RAM',
        'price' => rand(159999, 359999),
        'image' => 'products/dummy1/desktop-'.$i.'.jpg',
        'images' => '',
        'description' => 'Lorem-'.$i.' is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s
        standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
        It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
        It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
        and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
      ])->categories()->attach(2);
    }


    //Appliance


        for($i=1; $i < 30; $i++)
        {
          Product::create([
            'name' => 'Appliance ' . $i,
            'slug' => 'appliance-' . $i,
            'details' => [30,31,32][array_rand([31,32,33])] .' inch, '. [1,2,3][array_rand([1,2,3])] .'TB SSD, 32GB RAM',
            'price' => rand(159999, 359999),
            'image' => 'products/dummy1/appliance-'.$i.'.jpg',
            'images' => '',
            'description' => 'Lorem-'.$i.' is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s
            standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
            It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
          ])->categories()->attach(3);
        }



































    }

}
