{{-- Footer yang Selalu Berada di Bawah --}}
<footer class="bg-bg-dark border-t border-sienna/30 pt-16 pb-8 mt-auto w-full light:bg-white light:border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Bagian Utama Footer (Links) --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-10 border-b border-sienna/30 pb-10 mb-10">

            {{-- Kolom 1: Logo & Deskripsi Singkat --}}
            <div class="col-span-2 md:col-span-2">
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-leaf text-terracotta text-2xl"></i>
                    <span class="text-2xl font-extrabold text-cream-text light:text-light-text tracking-wider">
                        Agro<span class="text-terracotta">GISTech</span>
                    </span>
                </div>
                <p class="text-gray-400 light:text-gray-600 text-sm max-w-xs">
                    Platform informasi terdepan untuk inovasi dan solusi teknologi di industri kelapa sawit Indonesia.
                </p>
            </div>

            {{-- Kolom 2: Teknologi & Riset --}}
            <div>
                <h3 class="text-lg font-bold text-cream-text light:text-light-text mb-4">Wawasan</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Agronomi Presisi</a></li>
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Pabrik & Hilirisasi</a></li>
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">IoT & Sensor</a></li>
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Riset Terbaru</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Perusahaan --}}
            <div>
                <h3 class="text-lg font-bold text-cream-text light:text-light-text mb-4">Perusahaan</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Tentang Kami</a></li>
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Kontak</a></li>
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Karir <span class="text-sawit-green ml-1 text-xs font-semibold">HIRING</span></a></li>
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Kebijakan Privasi</a></li>
                </ul>
            </div>

            {{-- Kolom 4: Komunitas --}}
            <div>
                <h3 class="text-lg font-bold text-cream-text light:text-light-text mb-4">Dukungan</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Tanya Jawab</a></li>
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Kirim Artikel</a></li>
                    <li><a href="#" class="text-gray-400 light:text-gray-600 hover:text-terracotta transition">Dukungan Teknis</a></li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between text-sm text-gray-500 light:text-gray-600">
            <p>&copy; {{ date('Y') }} AgroGISTech. Hak Cipta Dilindungi.</p>

            {{-- Ikon Sosial Media --}}
            <div class="flex space-x-4 mt-4 md:mt-0">
                <a href="#" class="hover:text-terracotta transition"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="hover:text-terracotta transition"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/pretty_sthg" class="hover:text-terracotta transition"><i class="fab fa-instagram"></i></a>
                <a href="https://www.youtube.com/channel/UCENLhMKXdsY9aUSBKMBfqug" class="hover:text-terracotta transition"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </div>
</footer>
