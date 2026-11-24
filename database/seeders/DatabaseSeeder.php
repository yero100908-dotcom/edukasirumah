<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Superadmin
        $admin = Admin::updateOrCreate(
            ['email' => 'admin@edukasirumahan.id'],
            [
                'name' => 'Ir. Bambang Sudibyo, M.T.',
                'password' => Hash::make('admin123'),
                'role' => 'superadmin',
            ]
        );

        // 2. Kategori Edukasi (SVG Icon Lucide murni)
        $categoriesData = [
            [
                'name' => 'Konstruksi & Pondasi',
                'slug' => 'konstruksi-pondasi',
                'description' => 'Panduan struktur bangunan, perhitungan kedalaman pondasi, sloof, kolom praktis, dan ketahanan terhadap pergerakan tanah.',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
            ],
            [
                'name' => 'Keramik & Lantai',
                'slug' => 'keramik-lantai',
                'description' => 'Teknik pasang granit, keramik anti-kopong, perekat mortar khusus, nat kedap air, dan perawatan lantai basah.',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>',
            ],
            [
                'name' => 'Instalasi Pipa & Sanitasi',
                'slug' => 'instalasi-pipa-sanitasi',
                'description' => 'Standarisasi kemiringan jalur buangan air kotor, pencegahan pipa mampet, bak kontrol, septic tank, dan ventilasi udara.',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>',
            ],
            [
                'name' => 'Perawatan Rumah Tangga',
                'slug' => 'perawatan-rumah-tangga',
                'description' => 'Tips operasional harian, pemeliharaan bak cuci piring, grease trap mandiri, penanganan rembesan atap, dan efisiensi hunian.',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['slug']] = Category::updateOrCreate(['slug' => $cData['slug']], $cData);
        }

        // 3. Tags Relevan
        $tagsData = [
            'Anti Mampet',
            'Keramik Kopong',
            'Pondasi Kokoh',
            'Limbah Dapur',
            'Tanah Labil',
            'Popping Lantai',
            'Grease Trap',
            'Kemiringan Pipa',
            'Nat Kedap Air',
            'Struktur Beton',
            'Sloof Praktis',
            'Waterproofing',
        ];

        $tags = [];
        foreach ($tagsData as $tName) {
            $slug = Str::slug($tName);
            $tags[$slug] = Tag::updateOrCreate(['slug' => $slug], ['name' => $tName]);
        }

        // 4. Artikel Edukasi Lengkap (Minimal 6 Artikel Berbobot 400+ kata)
        $articles = [
            [
                'category_id' => $categories['konstruksi-pondasi']->id,
                'admin_id' => $admin->id,
                'title' => 'Anatomi Pondasi Batu Kali yang Kokoh: Mencegah Dinding Retak Rambut di Tanah Labil',
                'slug' => 'anatomi-pondasi-batu-kali-kokoh-mencegah-dinding-retak-rambut',
                'excerpt' => 'Pelajari kaidah teknis penataan batu kali, kedalaman tanah keras, dan sambungan angkur sloof untuk mencegah penurunan struktur diferensial penyebab keretakan dinding rumah.',
                'thumbnail' => 'uploads/thumbnails/pondasi-batu-kali.svg',
                'views_count' => 5420,
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'tags' => ['pondasi-kokoh', 'tanah-labil', 'struktur-beton', 'sloof-praktis'],
                'content' => '<h2>Mengapa Pondasi Batu Kali Rentan Bergerak di Wilayah Tanah Labil?</h2>
<p>Retak rambut pada dinding pasangan bata merah atau hebel kerap kali dianggap sekadar cacat plesteran luar. Padahal dalam investigasi struktur gedung dan rumah tinggal, lebih dari 70% keretakan diagonal bersudut 45 derajat berhulu pada penurunan pondasi dangkal (*differential settlement*). Di wilayah tanah ekspansif atau tanah lempung berpasir yang mengalami kembang-susut ekstrem di musim hujan dan kemarau, pondasi batu kali harus dirancang dengan geometri trapesium sempurna dan bertumpu di lapisan tanah yang tepat.</p>

<p>Kunci utama kekokohan pondasi menerus (*continuous footing*) tidak terletak pada ukuran batunya saja, melainkan pada kerapatan penguncian antar-permukaan batu serta keberadaan lapisan pasir urug dan *aanstamping* (batu kosong) di bawahnya.</p>

<div class="edu-box-tools">
    <h3 class="font-semibold text-slate-800 text-base mb-2">Spesifikasi Alat & Komposisi Bahan Standar SNI</h3>
    <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
        <li><strong>Batu Kali Belah:</strong> Batu pecah tajam bersudut (bukan batu bulat sungai licin), bebas dari tanah lempung yang menempel.</li>
        <li><strong>Mortar Pengikat:</strong> Semen Portland Tipe 1 dengan rasio adukan 1 PC : 4 Pasir Pasang bergradasi tajam.</li>
        <li><strong>Pasir Urug:</strong> Ketebalan minimal 10 cm dipadatkan dan disiram air merata (*tamping*).</li>
        <li><strong>Aanstamping (Batu Kosong):</strong> Disusun tegak satu lapis dengan ketebalan 15–20 cm dan celahnya diisi pasir bersih padat.</li>
        <li><strong>Besi Angkur Sloof:</strong> Besi ulir D10 atau polos D12 setiap jarak interval 1 meter, tertanam minimal 30 cm ke batu kali.</li>
    </ul>
</div>

<h2>Langkah Kerja Sistematis Pembuatan Pondasi Trapesium</h2>
<p>Kekuatan pondasi batu kali tercapai saat beban vertikal disalurkan merata ke tanah dasar. Ikuti urutan teknis berikut untuk hasil yang stabil:</p>

<div class="edu-box-steps">
    <ol class="list-decimal list-inside space-y-2 text-slate-700 text-sm">
        <li><strong>Penggalian & Uji Tanah Dasar:</strong> Gali tanah hingga kedalaman minimal 80 cm sampai 100 cm atau menemukan lapisan tanah padas keras. Pastikan dasar parit datar dengan lebar bawah minimal 80 cm.</li>
        <li><strong>Hamparkan Pasir Urug:</strong> Tebarkan pasir setebal 10 cm, ratakan dengan mistar kayu, basahi air, lalu tumbuk hingga tidak menyusut saat diinjak.</li>
        <li><strong>Pemasangan Aanstamping (Batu Kosong):</strong> Tata batu kali tegak berjajar rapat tanpa spesi adukan semen di dasar galian. Isi sela-sela antar-batu dengan pasir lalu siram air hingga rongga terkunci padat. Fungsi lapisan ini adalah menyalurkan rembesan air tanah (*drainase bawah pondasi*).</li>
        <li><strong>Pemasangan Profil Kayu dan Benang:</strong> Pasang mistar segitiga/profil bambu di setiap sudut galian dengan lebar atas pondasi minimal 30 cm dan lebar bawah minimal 70–80 cm.</li>
        <li><strong>Penyusunan Batu & Pengadukan Spesi:</strong> Pasang batu kali lapis demi lapis dengan adukan 1:4. Pastikan spesi semen membungkus seluruh sisi batu tanpa rongga kosong. Sisipkan besi stek/angkur sloof sebelum batu lapis teratas selesai.</li>
    </ol>
</div>

<div class="edu-box-warning">
    <h3 class="font-semibold text-amber-900 text-base mb-1">Peringatan Teknis & Kesalahan Fatal di Lapangan</h3>
    <p class="text-sm text-slate-700">Hindari penggunaan batu kali bulat tanpa dipecah, karena permukaannya yang halus tidak memiliki daya cengkeram mekanis (*interlocking*). Jangan pernah menaruh adukan spesi kering lalu menyiram air di atas parit; adukan harus diaduk homogen di luar galian menggunakan molen mini atau bak pengaduk sebelum diaplikasikan.</p>
</div>

<h2>Koneksi Kritis: Integrasi Sloof Beton Bertulang</h2>
<p>Pondasi batu kali tidak boleh dibiarkan bekerja sendiri menerima beban dinding lantai 1. Di atas permukaan batu kali yang telah rata (*leveling*), wajib dicor balok sloof beton bertulang berdimensi minimal 15x20 cm dengan tulangan pokok 4D10 dan sengkang D6-150 mm. Besi stek angkur yang telah ditanam ke batu kali harus dibengkokkan dan diikat kuat ke tulangan sloof. Inilah sabuk pengikat yang menyatukan seluruh dinding sehingga ketika terjadi penurunan tanah lokal di satu titik, bangunan tetap kaku (*rigid*) dan tidak mengalami keretakan struktural.</p>'
            ],
            [
                'category_id' => $categories['keramik-lantai']->id,
                'admin_id' => $admin->id,
                'title' => 'Teknik Pemasangan Keramik Lantai Agar Tidak Kopong dan Terangkat (Popping)',
                'slug' => 'teknik-pemasangan-keramik-lantai-anti-kopong-popping',
                'excerpt' => 'Mengapa keramik lantai sering meledak atau bersuara kopong saat diinjak? Simak teknik perendaman, penggunaan mortar instan tipis, dan pentingnya expansion joint celah nat.',
                'thumbnail' => 'uploads/thumbnails/keramik-popping.svg',
                'views_count' => 7890,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'tags' => ['keramik-kopong', 'popping-lantai', 'nat-kedap-air'],
                'content' => '<h2>Fenomena Popping: Tekanan Termal dan Rongga Udara</h2>
<p>Kasus keramik terangkat mendadak (*popping*) disertai bunyi retakan keras sering terjadi di ruang tengah atau teras rumah. Banyak pemilik rumah mengira hal tersebut akibat gempa mikro atau mistis, padahal ini murni fenomena fisika konstruksi: pemuaian termal ubin keramik yang tertahan akibat celah nat terlalu rapat (*zero joint*), diperparah oleh rongga udara di bawah keramik akibat teknik pengeleman semen konvensional yang keliru.</p>

<p>Keramik tanah liat merah (*red body tile*) dan *homogenous tile* (granit porselen) memiliki koefisien muai panjang dan tingkat penyerapan air (*water absorption*) yang sangat berbeda. Memperlakukan keduanya dengan metode yang sama adalah penyebab utama kegagalan lantai jangka panjang.</p>

<div class="edu-box-tools">
    <h3 class="font-semibold text-slate-800 text-base mb-2">Alat Kerja & Material Perekat Modern</h3>
    <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
        <li><strong>Roskam Bergerigi (Notched Trowel):</strong> Ukuran gigi 8 mm x 8 mm atau 10 mm x 10 mm untuk meratakan tebal spesi.</li>
        <li><strong>Palu Karet (Rubber Mallet):</strong> Bobot 500 gram untuk mengetuk ubin tanpa membuat micro-crack pada glasir.</li>
        <li><strong>Tile Spacer (Penyekat Nat):</strong> Ketebalan 2 mm hingga 3 mm untuk presisi celah pemuaian.</li>
        <li><strong>Tile Leveling System:</strong> Wedge dan klip penarik keramik agar tidak terjadi perbedaan ketinggian (*lippage*).</li>
        <li><strong>Semen Instan Perekat (Tile Adhesive C1/C2):</strong> Campuran semen berbasis polimer khusus pengganti pasir konvensional.</li>
    </ul>
</div>

<h2>Langkah Pemasangan Metode Double Buttering</h2>
<p>Metode *buttering* ganda memastikan 100% bidang bawah ubin tertutup perekat tanpa kantong udara:</p>

<div class="edu-box-steps">
    <ol class="list-decimal list-inside space-y-2 text-slate-700 text-sm">
        <li><strong>Pemeriksaan Subfloor:</strong> Pastikan screed lantai dasar sudah kering sempurna (minimal 14 hari setelah pengecoran), bersih dari debu, minyak, dan tidak bergelombang.</li>
        <li><strong>Penanganan Ubin Sesuai Jenis:</strong> Untuk keramik standar berpori (*red body*), rendam dalam air bersih selama 15 menit hingga gelembung udara hilang, lalu tiriskan hingga permukaan lembap (*damp dry*). <em>Catatan:</em> Ubin granit porselen/HT TIDAK BOLEH direndam, melainkan langsung dibersihkan sisi belakangnya dengan spons basah.</li>
        <li><strong>Aplikasi Mortar pada Lantai:</strong> Oleskan semen instan dengan sisi rata roskam, kemudian sisir searah (garis lurus sejajar, BUKAN melingkar) menggunakan sisi bergerigi. Arah sisiran harus tegak lurus terhadap arah bentang ruangan.</li>
        <li><strong>Back Buttering Ubin:</strong> Oleskan lapisan tipis semen perekat (tebal 1–2 mm) di seluruh punggung ubin keramik menggunakan sisi rata roskam sebelum ditempelkan.</li>
        <li><strong>Peletakan & Pengetukan:</strong> Letakkan ubin di atas pola sisiran, geser maju mundur 5 mm untuk memecah alur udara perekat, lalu ketuk lembut dari tengah ke tepi menggunakan palu karet. Pasang *tile spacer* di setiap sudut sambungan.</li>
    </ol>
</div>

<div class="edu-box-warning">
    <h3 class="font-semibold text-amber-900 text-base mb-1">Peringatan: Bahaya Pemasangan Tanpa Celah Nat (Zero Joint)</h3>
    <p class="text-sm text-slate-700">Meskipun gaya minimalis sering menuntut nat tak kasat mata, celah nat di bawah 1,5 mm sangat berisiko. Ketika suhu ruangan naik di siang hari, ubin memuai secara horizontal. Tanpa celah elastis yang diisi semen pengisi lentur, ubin akan saling menekan hingga akhirnya melenting ke atas dan pecah berkeping-keping.</p>
</div>

<h2>Peran Expansion Joint pada Area Luas</h2>
<p>Untuk ruangan dengan luas lebih dari 36 meter persegi atau panjang koridor di atas 6 meter tanpa sekat dinding, wajib disediakan *expansion joint* selebar 6–8 mm yang diisi bahan elastis seperti polyurethane atau silikon netral fleksibel. Sendi pemuaian ini menyerap tegangan deformasi termal gedung sehingga lantai keramik bebas dari ancaman meledak seumur hidup bangunan.</p>'
            ],
            [
                'category_id' => $categories['instalasi-pipa-sanitasi']->id,
                'admin_id' => $admin->id,
                'title' => 'Aturan Kemiringan Pipa Pembuangan Air Kotor dan Pemilihan Sambungan Knee yang Tepat',
                'slug' => 'aturan-kemiringan-pipa-pembuangan-air-kotor-pemilihan-knee',
                'excerpt' => 'Pipa buangan sering mampet bukan hanya karena kotoran, tetapi karena salah menentukan slope kemiringan dan penggunaan elbow 90 derajat siku yang menghambat aliran zat padat.',
                'thumbnail' => 'uploads/thumbnails/kemiringan-pipa.svg',
                'views_count' => 6540,
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'tags' => ['anti-mampet', 'kemiringan-pipa', 'sanitasi-rumah'],
                'content' => '<h2>Fisika Aliran Air Buangan: Hukum Slope 1% hingga 2%</h2>
<p>Banyak tukang bangunan awam beranggapan bahwa semakin miring posisi pipa pembuangan, semakin lancar air kotor mengalir ke saluran kota atau septic tank. Anggapan ini adalah kekeliruan hidrolika yang fatal. Pada pipa air buangan yang membawa partikel padat (seperti kotoran toilet atau sisa endapan sabun cuci), kemiringan yang terlalu curam (di atas 4%) justru membuat air meluncur terlampau cepat meninggalkan benda padat tertinggal di dasar pipa kering (*separation velocity*).</p>

<p>Sebaliknya, bila kemiringan di bawah 1%, air mengalir terlalu lambat sehingga kehilangan kecepatan bilas mandiri (*self-cleansing velocity*). Standar baku pipa drainase horizontal gravitasi mensyaratkan kemiringan ideal berada di rentang <strong>1% hingga 2%</strong> (turun 1 cm hingga 2 cm untuk setiap panjang pipa horizontal 1 meter).</p>

<div class="edu-box-tools">
    <h3 class="font-semibold text-slate-800 text-base mb-2">Komponen Pipa & Fitting Standar Plumbing</h3>
    <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
        <li><strong>Pipa PVC Kelas D atau AW:</strong> Gunakan minimal kelas AW untuk jalur tertanam di bawah lantai beton agar tahan beban injak.</li>
        <li><strong>Diameter Pipa:</strong> Minimal 2 inch untuk wastafel/floor drain, minimal 3 inch untuk buangan dapur, dan minimal 4 inch untuk kloset WC.</li>
        <li><strong>Fitting Sambungan 45 Derajat (1/8 Bend):</strong> Gunakan elbow 45° ganda atau kombinasi Y-branch (*Wye*) untuk belokan, HINDARI elbow 90° tajam.</li>
        <li><strong>Clean Out (CO Plug):</strong> Lubang akses perawatan berulir di setiap perubahan arah sudut pipa atau interval 6 meter.</li>
        <li><strong>Waterpass Digital/Mistar Ukur:</strong> Untuk mengukur gradien kemiringan per meter secara presisi sebelum pipa ditimbun.</li>
    </ul>
</div>

<h2>Panduan Pemasangan Jalur Pipa Bebas Hambatan</h2>
<p>Terapkan diagram pemipaan yang mengutamakan aerodinamika cairan kental:</p>

<div class="edu-box-steps">
    <ol class="list-decimal list-inside space-y-2 text-slate-700 text-sm">
        <li><strong>Penentuan Titik Awal & Akhir:</strong> Tentukan elevasi dasar inlet buangan dan outlet bak kontrol. Hitung selisih tinggi dengan rumus: <code>Selisih Tinggi (cm) = Panjang Jalur (m) x 2 cm</code>.</li>
        <li><strong>Pembuatan Bantalan Dasar Pasir:</strong> Dasar parit galian harus dipadatkan dan diberi bantalan pasir tebal 5 cm agar pipa PVC tidak melengkung (*sagging*) saat tertekan beban urugan tanah di kemudian hari.</li>
        <li><strong>Teknik Belokan Jalur:</strong> Ketika jalur harus berbelok 90 derajat, sambungkan dua buah fitting knee 45 derajat secara bertahap atau gunakan fitting *Sweep Tee / Long Radius Knee*. Ini memperkecil turbulensi dan mencegah gumpalan kotoran menabrak dinding siku.</li>
        <li><strong>Pemasangan Pipa Hawa (Vent Stack):</strong> Setiap jalur buangan toilet dan dapur wajib memiliki cabang pipa ventilasi vertikal yang menembus ke atap rumah. Pipa vent mencegah terbentuknya efek vakum (sifon) yang menyedot air leher angsa (*P-trap*) kloset.</li>
        <li><strong>Uji Aliran Bola & Air:</strong> Sebelum parit ditutup tanah kembali, gelindingkan bola pingpong bersama aliran air dari titik terjauh. Bola harus meluncur lancar tanpa tersangkut di sambungan pipa.</li>
    </ol>
</div>

<div class="edu-box-warning">
    <h3 class="font-semibold text-amber-900 text-base mb-1">Kesalahan Umum: Menanam Knee 90 Derajat di Bawah Plesteran Lantai</h3>
    <p class="text-sm text-slate-700">Knee 90 derajat bersudut lancip (tegak lurus) yang tertanam permanen di bawah cor lantai tanpa lubang *Clean Out* adalah mimpi buruk perawatan rumah. Jika tersumbat benda padat atau lemak beku, kabel spiral (*drain snake*) mesin pembersih pipa tidak akan bisa melewati lekukan tajam tersebut.</p>
</div>

<h2>Peran Krusial Clean Out (CO) di Titik Strategis</h2>
<p>Pasang tutup lubang kuras (*Clean Out*) berkepala kuningan atau PVC ulir yang rata dengan lantai di setiap belokan utama jalur luar. Dengan adanya CO, apabila sewaktu-waktu terjadi kemacetan saluran limbah, petugas atau pemilik rumah cukup membuka baut ulir dan memasukkan alat pendorong tanpa perlu membongkar keramik lantai ruangan utama.</p>'
            ],
            [
                'category_id' => $categories['perawatan-rumah-tangga']->id,
                'admin_id' => $admin->id,
                'title' => 'Cara Benar Mengelola Lemak dan Sisa Makanan Saat Mencuci Piring Agar Pipa Dapur Tidak Mampet',
                'slug' => 'cara-benar-mengelola-lemak-sisa-makanan-cuci-piring-bebas-mampet',
                'excerpt' => 'Lemak masakan yang dibilas dengan sabun cuci piring tetap membeku di dalam saluran pipa. Ketahui teknik dry wiping, suhu air bilas, dan instalasi grease trap portabel.',
                'thumbnail' => 'uploads/thumbnails/limbah-dapur.svg',
                'views_count' => 9120,
                'status' => 'published',
                'published_at' => now()->subDay(),
                'tags' => ['anti-mampet', 'limbah-dapur', 'grease-trap'],
                'content' => '<h2>Mitos Sabun Cuci Piring Menghancurkan Lemak di Pipa</h2>
<p>Sebagian besar penghuni rumah meyakini bahwa menuangkan sabun pencuci piring berbusa tebal atau air hangat sebaskom sudah cukup untuk melarutkan sisa minyak goreng dan kuah gulai ke wastafel. Kenyataannya, molekul deterjen sabun cuci hanya memecah minyak menjadi emulsi sementara (*temporary emulsion*). Begitu cairan melewati pipa pembuangan di bawah lantai yang suhunya lebih dingin (sekitar 24°C–27°C), minyak tersebut kembali memadat, menempel pada dinding PVC, dan bereaksi dengan kalsium air membentuk endapan sekeras batu kapur (*fatberg*).</p>

<p>Dalam kurun waktu 6 hingga 12 bulan, diameter dalam pipa 2 inch dapat menyempit hingga tersisa seukuran sedotan es, memicu luapan air kotor berbau busuk di lantai dapur Anda.</p>

<div class="edu-box-tools">
    <h3 class="font-semibold text-slate-800 text-base mb-2">Peralatan Manajemen Limbah Dapur Higienis</h3>
    <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
        <li><strong>Silicone Scraper / Karet Spatula:</strong> Untuk mengeruk sisa saus dan minyak wajan sebelum dibilas air.</li>
        <li><strong>Tisu Dapur Kertas (*Paper Towel*):</strong> Metode *dry-wipe* untuk menyerap lapisan minyak tebal.</li>
        <li><strong>Saringan Wastafel Stainless Mesh Halus (Sink Strainer):</strong> Celah lubang 1 mm pencegah butiran nasi dan ampas teh lolos.</li>
        <li><strong>Under-Sink Grease Trap:</strong> Perangkap lemak berbahan stainless 304 atau polyethelene berkapasitas 20–30 liter.</li>
        <li><strong>Wadah Minyak Jelantah Tertutup:</strong> Botol jeriken khusus untuk mendaur ulang minyak jelantah bekas pakai.</li>
    </ul>
</div>

<h2>SOP 3 Tahap Mencuci Alat Masak Berminyak</h2>
<p>Terapkan disiplin dapur praktis ini untuk memangkas 95% beban lemak masuk ke pipa sanitasi:</p>

<div class="edu-box-steps">
    <ol class="list-decimal list-inside space-y-2 text-slate-700 text-sm">
        <li><strong>Pemisahan Minyak Berlebih (Drain):</strong> Tuang sisa minyak goreng bekas dari wajan langsung ke jeriken penampung minyak jelantah. Jangan pernah menyiramkan minyak ke lubang sink!</li>
        <li><strong>Keringkan dengan Tisu (Dry Wiping):</strong> Sebelum wajan, panci, atau piring terkena kucuran air keran, seka seluruh sisa minyak dan kuah pekat dengan tisu dapur atau karet silikon. Buang tisu berminyak ke tempat sampah organik.</li>
        <li><strong>Pencucian dengan Saringan Halus Terpasang:</strong> Cuci perlengkapan dapur dengan air mengalir dan spons busa dengan kondisi saringan keranjang wastafel tetap terkunci rapat di posisinya. Bersihkan ampas makanan yang terjaring langsung ke tempat sampah setiap selesai satu sesi cuci.</li>
    </ol>
</div>

<div class="edu-box-warning">
    <h3 class="font-semibold text-amber-900 text-base mb-1">Peringatan: Hindari Penggunaan Soda Api (NaOH) Berkala</h3>
    <p class="text-sm text-slate-700">Menuangkan soda api kering ke wastafel dapur yang mampet menimbulkan reaksi eksotermik panas ekstrem hingga di atas 100°C. Suhu ini dapat melunakkan dan membengkokkan sambungan pipa PVC tipis hingga bocor di dalam dinding. Selain itu, soda api yang bereaksi dengan gumpalan lemak jenuh justru dapat membentuk gumpalan sabun padat (*saponifikasi*) yang mengkristal makin keras.</p>
</div>

<h2>Perawatan Rutin Grease Trap Bawah Bak Cuci</h2>
<p>Bagi hunian yang memasang unit *grease trap* di bawah kabinet bak cuci piring, lakukan pengurasan lapisan lemak mengapung setiap 1 hingga 2 minggu sekali. Buka keranjang saring padatan, angkat kerak minyak dengan centong khusus, lalu buang ke kantong kresek padat. Bilas dinding kotak perangkap dengan air hangat bersuhu 50°C. Langkah sederhana ini menjamin sistem perpipaan rumah tetap awet puluhan tahun tanpa perlu panggil jasa sedot pipa komersial.</p>'
            ],
            [
                'category_id' => $categories['konstruksi-pondasi']->id,
                'admin_id' => $admin->id,
                'title' => 'Mengenal Jenis Tanah dan Pengaruhnya terhadap Kedalaman Pondasi Rumah Tinggal',
                'slug' => 'mengenal-jenis-tanah-pengaruh-kedalaman-pondasi-rumah',
                'excerpt' => 'Ketahui perbedaan daya dukung tanah padas, tanah liat kembang-susut, dan tanah gambut bekas urugan, serta rumus menentukan tipe pondasi tapak atau cakar ayam yang aman.',
                'thumbnail' => 'uploads/thumbnails/jenis-tanah.svg',
                'views_count' => 4120,
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'tags' => ['pondasi-kokoh', 'tanah-labil', 'struktur-beton'],
                'content' => '<h2>Urgensi Daya Dukung Tanah (*Soil Bearing Capacity*)</h2>
<p>Sebagian besar kegagalan struktur hunian 2 lantai di Indonesia diawali oleh asumsi tanah seragam. Pemilik rumah kerap menyamakan kedalaman pondasi rumahnya dengan rumah tetangga sebelah, tanpa menyadari bahwa lapisan tanah bawah permukaan dapat berbeda kontur dan kepadatannya dalam jarak beberapa meter saja. Tanah adalah tumpuan akhir dari seluruh berat mati bangunan, beban hidup penghuni, dan beban dinamis gempa bumi.</p>

<p>Kekuatan tanah dinyatakan dalam nilai konus sondir (*Cone Penetration Test / CPT*). Bila nilai tahanan konus (qc) belum mencapai minimal 150 kg/cm² hingga 200 kg/cm², lapisan tersebut belum dapat dikategorikan sebagai tanah keras pembawa beban struktural.</p>

<div class="edu-box-tools">
    <h3 class="font-semibold text-slate-800 text-base mb-2">Metode Uji Tanah Mandiri Sederhana di Lapangan</h3>
    <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
        <li><strong>Uji Sondir Ringan (Dutch Cone):</strong> Pengujian akurat kedalaman tanah keras hingga 15 meter oleh lab uji geoteknik.</li>
        <li><strong>Pengamatan Lubang Sumur Uji (*Test Pit*):</strong> Galian terbuka sedalam 1,5–2 meter untuk mengamati stratifikasi warna dan tekstur lapisan tanah.</li>
        <li><strong>Uji Remas Tangan (*Ribbon Test*):</strong> Menentukan kadar lempung tanah dengan membasahi segumpal tanah lalu memilinnya di antara jemari tangan.</li>
        <li><strong>Bor Tangan Linggis (*Hand Auger*):</strong> Mengambil sampel tanah per interval 50 cm untuk mengecek kadar kebasahan air tanah.</li>
    </ul>
</div>

<h2>Karakteristik 4 Kategori Tanah dan Solusi Pondasinya</h2>
<p>Kenali perilaku lapisan tanah lokasi kavling rumah Anda sebelum merancang tulangan beton:</p>

<div class="edu-box-steps">
    <ol class="list-decimal list-inside space-y-2 text-slate-700 text-sm">
        <li><strong>Tanah Padas Berbatu / Berkerikil:</strong> Kategori tanah terbaik dengan daya dukung tinggi (qc &gt; 250 kg/cm²). Kedalaman pondasi cukup 80 cm hingga 1 meter menggunakan kombinasi pondasi batu kali menerus dan sloof standar.</li>
        <li><strong>Tanah Lempung Ekspansif (Tanah Labil):</strong> Karakter tanah liat hitam atau abu-abu pekat yang sangat lengket saat basah dan retak-retak merekah di musim kemarau. Wajib menggunakan pondasi dalam seperti strauss pile (*bored pile*) manual sedalam 3–6 meter hingga melewati zona fluktuasi air tanah.</li>
        <li><strong>Tanah Bekas Sawah atau Rawa (Tanah Endapan Aluvial):</strong> Sering dijumpai di kawasan suburban baru. Lapisan atas sedalam 1–2 meter merupakan tanah lunak berpasir halus. Memerlukan cerucuk bambu dolken berdiameter 10 cm dipancang rapat di bawah pondasi tapak (*footplat*).</li>
        <li><strong>Tanah Timbunan Urugan Sampah:</strong> Paling berisiko mengalami penurunan jangka panjang yang tidak merata. Wajib dikupas habis tanah urugan tidak berstrukturnya atau menggunakan pondasi tiang pancang mini (*mini pile*).</li>
    </ol>
</div>

<div class="edu-box-warning">
    <h3 class="font-semibold text-amber-900 text-base mb-1">Peringatan: Bahaya Mengandalkan Pondasi Cakar Ayam di Atas Tanah Lunak Tanpa Tiang Pancang</h3>
    <p class="text-sm text-slate-700">Istilah "cakar ayam" kerap disalahartikan masyarakat awam sebagai sekadar pelat beton tipis dengan anyaman besi yang diletakkan di kedalaman 1 meter. Tanpa pipa/kolom pengaku yang menembus ke lapisan tanah keras, pelat tersebut akan ikut amblas miring ketika tanah jenuh air menanggung beban dinding 2 lantai di atasnya.</p>
</div>

<h2>Pentingnya Balok Pengikat (Tie Beam)</h2>
<p>Pada kondisi tanah heterogen, setiap telapak pondasi (*footplat*) wajib diikat oleh balok *tie beam* dua arah (sumbu X dan sumbu Y). Balok pengikat ini berfungsi mendistribusikan momen puntir dan mencegah pergeseran lateral saat gempa, menjamin rangka bangunan tetap berdiri tegak kendati terjadi sedikit pergerakan di salah satu titik tumpuan.</p>'
            ],
            [
                'category_id' => $categories['keramik-lantai']->id,
                'admin_id' => $admin->id,
                'title' => 'Panduan Memilih Nat Keramik dan Perawatan Area Basah Agar Tidak Mudah Berjamur',
                'slug' => 'panduan-memilih-nat-keramik-perawatan-area-basah-anti-jamur',
                'excerpt' => 'Ketahui perbedaan nat semen standar, nat polimer anti-jamur, dan epoxy grout 2 komponen untuk kamar mandi dan teras luar agar tidak bocor ke lantai bawah.',
                'thumbnail' => 'uploads/thumbnails/nat-keramik.svg',
                'views_count' => 6150,
                'status' => 'published',
                'published_at' => now()->subDays(6),
                'tags' => ['nat-kedap-air', 'waterproofing', 'keramik-kopong'],
                'content' => '<h2>Mengapa Nat Kamar Mandi Cepat Hitam dan Keropos?</h2>
<p>Sambungan nat keramik sering kali menjadi celah terlemah dalam sistem pelapis lantai dan dinding ruangan basah. Banyak pemilik rumah mengeluhkan nat keramik kamar mandinya menghitam dalam 3 bulan, terkikis saat disikat sabun, hingga timbul tetesan air merembes ke plafon lantai bawah (*plafon gypsum jebol*). Masalah ini berakar pada pemilihan bahan pengisi nat (*grout*) yang tidak kedap air serta pori-pori semen yang menjadi sarang jamur lumut (*mold & mildew*).</p>

<p>Semen putih biasa tanpa aditif waterproofing memiliki pori kapiler terbuka yang menyerap air hingga 15%. Setiap tetes air sabun mandi yang masuk ke celah nat perlahan-lahan mengikis ikatan kimia semen di bawahnya.</p>

<div class="edu-box-tools">
    <h3 class="font-semibold text-slate-800 text-base mb-2">Peralatan Pengisian & Material Nat Berteknologi Tinggi</h3>
    <ul class="list-disc list-inside space-y-1 text-slate-700 text-sm">
        <li><strong>Epoxy Grout (2 Komponen):</strong> Berbahan resin epoksi dan hardener silika kristal yang 100% kedap air, anti-kimia, dan tidak berpori.</li>
        <li><strong>Nat Semen Polimer Hidrofobik:</strong> Mengandung partikel penolak air (*water repellent*) dan zat biocide anti-jamur.</li>
        <li><strong>Kape Karet (Rubber Float):</strong> Untuk menekan spesi nat masuk ke celah terdalam tanpa menggores permukaan keramik.</li>
        <li><strong>Spons Selulosa Padat:</strong> Spons pencuci berdensitas tinggi untuk merapikan alur nat setengah kering.</li>
        <li><strong>Cairan Pembersih Asam Organik Lembut:</strong> Mengangkat residu kabut semen (*grout haze*) tanpa merusak warna nat.</li>
    </ul>
</div>

<h2>Langkah Pengisian Nat Kamar Mandi yang Kedap Total</h2>
<p>Ikuti prosedur teknis pengisian nat agar tahan semprotan air bertekanan dan sikat kawat:</p>

<div class="edu-box-steps">
    <ol class="list-decimal list-inside space-y-2 text-slate-700 text-sm">
        <li><strong>Pembersihan Celah Nat:</strong> Pastikan celah nat bersih dari sisa semen perekat keramik hingga kedalaman minimal 2/3 tebal keramik (korek dengan kape pengikis nat). Sedot debu dengan vacuum cleaner basah/kering.</li>
        <li><strong>Pengecekan Kekeringan Dasar:</strong> Jangan mengisi nat pada keramik yang baru dipasang kurang dari 24 jam. Biarkan uap air dari mortar perekat menguap tuntas agar tidak terperangkap di bawah nat.</li>
        <li><strong>Pencampuran Homogen:</strong> Campurkan bubuk nat khusus area basah dengan cairan aditif polimer sintetis (hindari mencampur dengan air kotor sumur). Aduk rata hingga tekstur menyerupai pasta pasta gigi.</li>
        <li><strong>Aplikasi Diagonal 45 Derajat:</strong> Tekan pasta nat menggunakan kape karet dengan gerakan diagonal bersudut 45 derajat terhadap garis nat. Tekan kuat-kuat agar tidak ada kantong udara kosong di dasar celah.</li>
        <li><strong>Penyekaan & Pembersihan Residu:</strong> Setelah 15–20 menit (ketika nat mulai mengeras namun belum membatu), usap permukaan keramik dengan spons selulosa lembap yang diperas tuntas dengan gerakan memutar melingkar tipis.</li>
    </ol>
</div>

<div class="edu-box-warning">
    <h3 class="font-semibold text-amber-900 text-base mb-1">Peringatan: Hindari Penggunaan Pembersih Porselen Asam Klorida (HCL) Pekat</h3>
    <p class="text-sm text-slate-700">Cairan pembersih lantai instan berbahan dasar asam klorida (HCL) konsentrasi tinggi memang membersihkan noda kuning seketika, namun cairan asam tersebut bereaksi merusak senyawa kalsium semen nat. Dalam hitungan bulan, garis nat akan berongga, rapuh, dan air rembesan mulai merusak struktur beton lantai bawah.</p>
</div>

<h2>Solusi Permanen Area Shower: Penggunaan Epoxy Grout</h2>
<p>Untuk area lantai bilas shower yang terpapar air hangat setiap hari, beralih ke *epoxy grout* adalah investasi paling ekonomis jangka panjang. Nat epoksi tidak memiliki pori sama sekali, sehingga minyak sabun dan jamur tidak dapat menempel pada pori. Cukup dibilas air biasa dan diusap spons kain microfiber, nat akan selalu bersih berkilau seperti baru dipasang selama lebih dari 10 tahun.</p>'
            ],
        ];

        foreach ($articles as $aData) {
            $tagSlugs = $aData['tags'] ?? [];
            unset($aData['tags']);

            $article = Article::updateOrCreate(['slug' => $aData['slug']], $aData);

            $tagIds = [];
            foreach ($tagSlugs as $tSlug) {
                if (isset($tags[$tSlug])) {
                    $tagIds[] = $tags[$tSlug]->id;
                }
            }
            $article->tags()->sync($tagIds);

            // Tambahkan 2 komentar percontohan (1 approved, 1 pending)
            Comment::updateOrCreate(
                [
                    'article_id' => $article->id,
                    'guest_email' => 'hendra.kusuma@gmail.com',
                ],
                [
                    'guest_name' => 'Hendra Kusuma',
                    'body' => 'Penjelasan teknisnya sangat detail dan solutif! Saya kemarin sempat bingung kenapa dinding dekat teras retak diagonal, ternyata betul karena tidak ada pasangan aanstamping di bawah batu kali.',
                    'is_approved' => true,
                    'created_at' => now()->subHours(12),
                ]
            );

            Comment::updateOrCreate(
                [
                    'article_id' => $article->id,
                    'guest_email' => 'rudy.santoso@yahoo.co.id',
                ],
                [
                    'guest_name' => 'Rudy Santoso',
                    'body' => 'Izin bertanya Pak, untuk rumah 2 lantai di tanah urugan sawah, apakah balok sloof ukuran 15x20 masih cukup aman atau sebaiknya naik ke 20x30 cm?',
                    'is_approved' => false, // Pending moderasi
                    'created_at' => now()->subHours(2),
                ]
            );
        }
    }
}
