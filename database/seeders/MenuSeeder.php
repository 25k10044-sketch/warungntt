<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        $menus = [
            [
                'category' => 'daging',
                'name' => 'Se’i Sapi',
                'slug' => 'sei-sapi',
                'description' => 'Irisan daging sapi asap dengan aroma kayu yang khas, disajikan bersama sambal lu’at.',
                'story' => 'Se’i adalah teknik pengasapan daging yang dikenal di Timor. Daging diasap perlahan sehingga matang dan menyerap aroma kayu.',
                'origin_region' => 'Kupang, Timor',
                'main_ingredients' => 'Daging sapi, garam, rempah, kayu pengasap',
                'price' => 65000,
            ],
            [
                'category' => 'daging',
                'name' => 'Se’i Babi',
                'slug' => 'sei-babi',
                'description' => 'Daging babi asap khas Timor dengan tekstur lembut dan rasa gurih.',
                'story' => 'Se’i babi merupakan salah satu olahan asap yang populer di Timor dan Kupang. Disajikan terpisah dari menu non-babi.',
                'origin_region' => 'Kupang, Timor',
                'main_ingredients' => 'Daging babi, garam, rempah, kayu pengasap',
                'price' => 68000,
            ],
            [
                'category' => 'ikan-seafood',
                'name' => 'Ikan Kuah Asam',
                'slug' => 'ikan-kuah-asam',
                'description' => 'Ikan segar dalam kuah bening bercita rasa asam, segar, dan berbumbu.',
                'story' => 'Hidangan berkuah ini mencerminkan kedekatan masyarakat pesisir NTT dengan hasil laut dan bahan pemberi rasa asam.',
                'origin_region' => 'Kupang',
                'main_ingredients' => 'Ikan, asam, tomat, bawang, cabai',
                'price' => 73000,
            ],
            [
                'category' => 'sayur-nasi',
                'name' => 'Jagung Bose',
                'slug' => 'jagung-bose',
                'description' => 'Jagung yang dimasak lama bersama kacang hingga lembut dan mengenyangkan.',
                'story' => 'Jagung bose dikenal sebagai makanan pokok masyarakat Timor. Jagung pipil dimasak bersama kacang-kacangan hingga teksturnya lembut.',
                'origin_region' => 'Timor',
                'main_ingredients' => 'Jagung, kacang merah, kacang tanah, santan',
                'price' => 28000,
            ],
            [
                'category' => 'sayur-nasi',
                'name' => 'Catemak Jagung',
                'slug' => 'catemak-jagung',
                'description' => 'Sup jagung dan kacang-kacangan dengan kuah ringan serta sayuran.',
                'story' => 'Catemak jagung adalah sajian rumahan dari Timor yang mengolah jagung dan kacang dengan bahan kebun yang tersedia.',
                'origin_region' => 'Timor',
                'main_ingredients' => 'Jagung, kacang-kacangan, labu, daun-daunan',
                'price' => 25000,
            ],
            [
                'category' => 'sayur-nasi',
                'name' => 'Kolo',
                'slug' => 'kolo',
                'description' => 'Nasi berbumbu yang dimasak di dalam bambu hingga harum.',
                'story' => 'Kolo merupakan nasi bambu yang dikenal di Manggarai. Beras dimasukkan ke ruas bambu beralas daun, lalu dimasak di atas api.',
                'origin_region' => 'Manggarai, Flores',
                'main_ingredients' => 'Beras, santan, garam, daun pisang, bambu',
                'price' => 22000,
            ],
            [
                'category' => 'jajanan',
                'name' => 'Jagung Titi',
                'slug' => 'jagung-titi',
                'description' => 'Camilan jagung pipil yang disangrai lalu ditumbuk hingga pipih dan renyah.',
                'story' => 'Jagung titi dikenal di Flores Timur dan wilayah sekitarnya. Jagung disangrai, kemudian dititi atau dipipihkan satu per satu.',
                'origin_region' => 'Flores Timur',
                'main_ingredients' => 'Jagung pipil, garam',
                'price' => 24000,
            ],
            [
                'category' => 'sayur-nasi',
                'name' => 'Rumpu Rampe',
                'slug' => 'rumpu-rampe',
                'description' => 'Tumis sayuran khas Flores dengan rasa gurih dan sedikit pahit yang seimbang.',
                'story' => 'Rumpu rampe mengolah daun dan bunga pepaya bersama bahan lokal. Variasi sayurannya berbeda menurut keluarga dan daerah.',
                'origin_region' => 'Flores',
                'main_ingredients' => 'Daun pepaya, bunga pepaya, daun singkong, bumbu',
                'price' => 26000,
            ],
            [
                'category' => 'sayur-nasi',
                'name' => 'Ka’pu Pantunnu',
                'slug' => 'kapu-pantunnu',
                'description' => 'Jantung pisang bakar yang ditumis dan dipadukan dengan kelapa panggang serta kemangi.',
                'story' => 'Ka’pu pantunnu dikenal sebagai masakan rumahan Sumba. Jantung pisang dibakar lalu dipotong, kemudian dicampur dengan kelapa panggang dan kemangi. Resep dan bumbu dapat berbeda antar keluarga.',
                'origin_region' => 'Pulau Sumba',
                'main_ingredients' => 'Jantung pisang, kelapa parut, daun kemangi',
                'price' => 27000,
            ],
            [
                'category' => 'sayur-nasi',
                'name' => 'Kaparak',
                'slug' => 'kaparak',
                'description' => 'Olahan jagung tumbuk yang disangrai bersama kacang, kelapa, dan gula.',
                'story' => 'Kaparak dikenal sebagai makanan tradisional Sumba Barat Daya dan juga dijumpai di wilayah lain Pulau Sumba. Jagung tumbuk disangrai; beberapa variasi menggunakan beras atau sagu.',
                'origin_region' => 'Sumba Barat Daya, Sumba',
                'main_ingredients' => 'Jagung tumbuk, kacang tumbuk, kelapa, gula',
                'price' => 24000,
            ],
            [
                'category' => 'sayur-nasi',
                'name' => 'Ubi Nuabosi',
                'slug' => 'ubi-nuabosi',
                'description' => 'Ubi kayu dari kawasan Nuabosi yang direbus sederhana untuk menonjolkan rasa alaminya.',
                'story' => 'Nuabosi di Kabupaten Ende dikenal dengan hasil ubi kayunya. Umbi ini kerap dinikmati sebagai pengganti nasi atau teman lauk.',
                'origin_region' => 'Ende, Flores',
                'main_ingredients' => 'Ubi kayu Nuabosi, garam',
                'price' => 20000,
            ],
            [
                'category' => 'jajanan',
                'name' => 'Roti Kompiang',
                'slug' => 'roti-kompiang',
                'description' => 'Roti bundar berkulit agak renyah dan bertabur wijen, cocok sebagai kudapan.',
                'story' => 'Kompiang lekat dengan Ruteng dan Manggarai. Roti ini berkembang dari pertemuan tradisi kuliner setempat dan pengaruh Tionghoa.',
                'origin_region' => 'Ruteng, Manggarai',
                'main_ingredients' => 'Tepung terigu, ragi, wijen, gula',
                'price' => 12000,
            ],
            [
                'category' => 'jajanan',
                'name' => 'Kue Rambut',
                'slug' => 'kue-rambut',
                'description' => 'Kue kering tipis berpilin dengan tekstur renyah dan rasa manis.',
                'story' => 'Kue rambut merupakan kudapan tradisional yang dijumpai di sejumlah daerah NTT, termasuk Alor, dan biasa disajikan saat berkumpul.',
                'origin_region' => 'Alor',
                'main_ingredients' => 'Tepung beras, gula, telur, minyak',
                'price' => 18000,
            ],
            [
                'category' => 'minuman',
                'name' => 'Kopi Bajawa',
                'slug' => 'kopi-bajawa',
                'description' => 'Kopi seduh dari biji arabika Bajawa dengan aroma kuat dan karakter rasa yang khas.',
                'story' => 'Dataran tinggi Bajawa di Kabupaten Ngada merupakan salah satu kawasan penghasil kopi Flores yang dikenal luas.',
                'origin_region' => 'Bajawa, Ngada',
                'main_ingredients' => 'Kopi arabika Bajawa, air',
                'price' => 22000,
            ],
        ];

        foreach ($menus as $menu) {
            $categoryId = $categories[$menu['category']];
            unset($menu['category']);

            Menu::updateOrCreate(
                ['slug' => $menu['slug']],
                [...$menu, 'category_id' => $categoryId, 'is_available' => true],
            );
        }
    }
}
